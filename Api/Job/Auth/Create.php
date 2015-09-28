<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Job\Auth;

use Eve\Framework\Job\Base;
use Eve\Framework\Job\Argument;
use Eve\Framework\Job\Exception;

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
		//if no data
        if(empty($this->data)) {
            //there should be a global catch somewhere
            throw new Exception(self::FAIL_406);
        }
        
        //this will be returned at the end
        $results = array();
        
		//see if the email exists first
		$exists = eve()
			->model('auth')
			->exists($this->data['profile_email']);
		
		//if it does
		if($exists) {
			//not sure, throw an error ?
			throw new Exception('Email exists.');
		}
		
		//find the profile by email
		$row = eve()
			->model('profile')
			->search()
			->process() 
			->filterByProfileEmail($this->data['profile_email'])
			->getRow();
		
		//if no profile
		if(empty($row)) {
			//create one
			$row = eve()
				->model('profile')
				->create()
				->process($this->data)
				->get();
		}
		
		//store the profile
		$results['profile'] = $row;
		
		//if there's a file
		if(isset($this->data['file_link'])) {
			//store the file
			$results['file'] = eve()
				->model('file')
				->create()
				->process(array( 
					'file_link' => $this->data['file_link'], 
					'file_type' => 'main_profile'))
				->get();
			
			//link the file
			eve()
				->model('profile')
				->linkFile(
					$results['profile']['profile_id'], 
					$results['file']['file_id']);
		}
		
		//create the auth
		$results['auth'] = eve()
			->model('auth')
			->create()
			->process($this->data);
		
		//link profile
		eve()
			->model('auth')
			->linkProfile(
				$results['auth']['auth_id'],
				$results['profile']['profile_id']);
		
		return $results;
	}
}