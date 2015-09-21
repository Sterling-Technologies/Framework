<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\App;

use Api\Model\Base;
use Api\Model\Argument;
use Api\Model\Exception;

/**
 * Model TODO
 *
 * @vendor Api
 */
class TODO extends Base
{
	const INVALID_PARAMETERS = 'Invalid Parameters';
	const INVALID_ID = 'Invalid ID';
	
	/**
	 * Returns errors if any
	 *
	 * @param array submitted item
	 * @return array error
	 */
	public function errors(array $item = array(), array $errors = array()) 
    {
		if(!$this->isInteger($item['app_id'])) {
			$errors['app_id'] = self::INVALID_ID;
		}
		
		return $errors;
	}
	
	/**
	 * 1. Update the app
	 *
	 * @param array item
	 * @return void
	 */
	public function process(array $item = array()) 
	{
		//prevent uncatchable error
		if(count($this->errors($item))) {
			throw new Exception(self::INVALID_PARAMETERS);
		}
		
		//prepare
		$item = $this->prepare($item);
		
		//generate dates
		$updated = date('Y-m-d H:i:s');
		
		$token = control()->uid();
		$secret = control()->uid(); 
		
		//SET WHAT WE KNOW
		$model = control()
			->database()
			->model()
			
			// app_id
			->setAppId($item['app_id'])
			
			// app_token		Required
			->setAppToken($token)
			
			// app_secret		Required
			->setAppSecret($secret)
			
			// app_updated
			->setAppUpdated($updated);
		
		//what's left ?
		$model->save('app');
		
		$this->trigger('app-refresh', $model);
		
		return $model;
	}
}
