<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Job\Profile;

use Api\Job\Base;
use Api\Job\Argument;
use Api\Job\Exception;

/**
 * Job Update
 *
 * @vendor Api
 */
class Update extends Base 
{
	/**
	 * Executes the job
	 *
	 * @return void
	 */
	public function run() 
	{
		if(!isset($this->data['item'])) {
			throw new Exception('Missing item key in data.');
		}
		
		//need to have
		// item 	- profile item
		$item = $this->data['item'];
		$results = array('image' => array());
		
		//update profile
		$results['profile'] = control()
			->model('profile')
			->update()
			->process($item)
			->get();
		
		//if there are images
		if(!isset($item['images']) || !is_array($item['images'])) {
			return $results;
		}
	
		//first unlink all files
		control()
			->model('profile')
			->unlinkAllFiles(
				$item['profile_id'], 
				array(
					'main_profile', 
					'profile_image'));
		
		foreach($item['images'] as $i => $file) {
			$file['file_type'] = 'profile_image';
		
			if($i === 0) {
				$file['file_type'] = 'main_profile';
			}
			
			//1. Validate
			$file['imageOnly'] = true;
			
			$errors = control()
				->model('file')
				->create()
				->errors($file);
			
			if(count($errors)) {
				continue;
			}
			
			// 2. Process
			$results['images'][] = $model = control()
				->model('file')
				->create()
				->process($file);
				
			//link
			control()
				->model('profile')
				->linkFile(
					$item['profile_id'], 
					$model['file_id']);
		}
		
		return $results;
	}
}