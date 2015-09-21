<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Job\App;

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
		
		if(!isset($this->data['profile_id'])) {
			throw new Exception('Missing profile_id key in data.');
		}
		
		//need to have
		// item 	- app item
		// profile_id
		$item = $this->data['item'];
		$profileId = $this->data['profile_id'];
		
		$model = control()
			->model('app')
			->create()
			->process($item);
			
		control()
			->model('app')
			->linkProfile(
				$model['app_id'], 
				$profileId);
		
		return array('app' => $model->get());
	}
}