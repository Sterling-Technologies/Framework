<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Job\Auth;

use Api\Job\Base;
use Api\Job\Argument;
use Api\Job\Exception;

/**
 * Job Create
 *
 * @vendor Api
 */
class Create extends Base 
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
		// item 	- auth/profile/file item
		$item = $this->data['item'];
		$results = array();
		
		//see if the email exists first
		$exists = control()
			->model('auth')
			->exists($item['profile_email']);
		
		//if it does
		if($exists) {
			//not sure, throw an error ?
			throw new Exception('Email exists.');
		}
		
		//find the profile by email
		$row = control()
			->model('profile')
			->search()
			->process() 
			->filterByProfileEmail($item['profile_email'])
			->getRow();
		
		//if no profile
		if(empty($row)) {
			//create one
			$row = control()
				->model('profile')
				->create()
				->process($item)
				->get();
		}
		
		//store the profile
		$results['profile'] = $row;
		
		//if there's a file
		if(isset($item['file_link'])) {
			//store the file
			$results['file'] = control()
				->model('file')
				->create()
				->process(array( 
					'file_link' => $item['file_link'], 
					'file_type' => 'main_profile'))
				->get();
			
			//link the file
			control()
				->model('file')
				->linkFile(
					$results['profile']['profile_id'], 
					$results['file']['file_id']);
		}
		
		//create the auth
		$results['auth'] = control()
			->model('auth')
			->create()
			->process($item);
		
		//link profile
		control()
			->model('auth')
			->linkProfile(
				$results['auth']['auth_id'],
				$results['profile']['profile_id']);
		
		return $results;
	}
}