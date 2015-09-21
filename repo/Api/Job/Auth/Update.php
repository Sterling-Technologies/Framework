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
		// item 	- auth/profile item
		$item = $this->data['item'];
		$results = array();
		
		//update profile
		$results['profile'] = control()
			->model('profile')
			->update()
			->process($item);
		
		//update auth
		$results['auth'] = control()
			->model('auth')
			->update()
			->process($item);
		
		return $results;
	}
}