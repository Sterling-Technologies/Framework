<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Api\Job\Auth;

use Eve\Framework\Job\Base;
use Eve\Framework\Job\Argument;
use Eve\Framework\Job\Exception;

/**
 * Auth Job Create
 *
 * GUIDE:
 * -- eve() - The current server controller
 *    use this to access the rest of the framework
 *
 *    -- eve()->database() - Returns the current database
 *
 *    -- eve()->model('noun') - Returns the given model factory
 *
 *    -- eve()->job('noun-action') - Returns a job following noun/action
 *
 *    -- eve()->settings('foo') - Returns a settings data originating
 *    from the settings path. ie. settings/foo.php
 *
 *    -- eve()->registry() - Returns Eden\Registry\Index used globally
 *
 * -- $this->data - Provides all raw data
 *    originally passed into the job
 */
class Create extends Base 
{
    const FAIL_406 = 'Invalid Data';
	
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