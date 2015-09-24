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
 * Job Remove
 *
 * @vendor Api
 */
class Remove extends Base 
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
		// item 	- app item
		$item = $this->data['item'];
		
		$model = eve()
			->model('app')
			->remove()
			->process($item);
		
		return array('app' => $model->get());
	}
}