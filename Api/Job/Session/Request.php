<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Api\Job\Session;

use Eve\Framework\Job\Base;
use Eve\Framework\Job\Exception;

/**
 * Session Job Request
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
class Request extends Base 
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
		
		$results['session'] = eve()
			->model('session')
			->request()
			->process($this->data)
			->get();
		
		return $results;
	}
}