<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\App;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Model Create
 *
 * @vendor Api
 */
class Create extends Base
{
	const INVALID_PARAMETERS = 'Invalid Parameters';
	const INVALID_EMPTY = 'Cannot be empty!';
	const INVALID_SET = 'Cannot be empty, if set';
	const INVALID_FLOAT = 'Should be a valid floating point';
	const INVALID_INTEGER = 'Should be a valid integer';
	const INVALID_NUMBER = 'Should be a valid number';
	const INVALID_BOOL = 'Should either be 0 or 1';
	const INVALID_SMALL = 'Should be between 0 and 9';
	
	/**
	 * Returns errors if any
	 *
	 * @param array submitted item
	 * @return array error
	 */
	public function errors(array $item = array(), array $errors = array()) 
    {
		//prepare
		$item = $this->prepare($item);
		
		//REQUIRED
		
		// app_name				Required
		if(empty($item['app_name'])) {
			$errors['app_name'] = self::INVALID_EMPTY;
		}
		
		// app_permissions		Required
		if(empty($item['app_permissions'])) {
			$errors['app_permissions'] = self::INVALID_EMPTY;
		}
		
		//OPTIONAL
		
		// app_flag
		if(isset($item['app_flag']) 
		&& !$this('validation', $item['app_flag'])->isType('small', true)) {
			$errors['app_flag'] = self::INVALID_SMALL;
		}
		
		return $errors;
	}
	
	/**
	 * Processes the form
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
		$created = date('Y-m-d H:i:s');
		$updated = date('Y-m-d H:i:s');
		
		$token = md5(uniqid());
		$secret = md5(uniqid()); 
		
		//SET WHAT WE KNOW
		$model = eve()
			->database()
			->model()
	
			// app_token		Required
			->setAppToken($token)
			
			// app_secret		Required
			->setAppSecret($secret)
			
			// app_name			Required
			->setAppName($item['app_name'])
			
			// app_permissions	Required
			->setAppPermissions($item['app_permissions'])

			// app_created
			->setAppCreated($created)
			
			// app_updated
			->setAppUpdated($updated);
		
		// app_domain
		if(isset($item['app_domain'])) {
			$model->setAppDomain($item['app_domain']);
		}

		// app_website		
		if(isset($item['app_website'])) {
			$model->setAppWebsite($item['app_website']);
		}

		// app_type
		if(isset($item['app_type'])) {
			$model->setAppType($item['app_type']);
		}
		
		// app_flag
		if(isset($item['app_flag'])
			&& $this('validation', $item['app_flag'])->isType('small', true)) {
			$model->setAppFlag($item['app_flag']);
		}
		
		//what's left ?
		$model->save('app');
		
		$this->trigger('app-create', $model);
		
		return $model;
	}
}
