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
 * Model Update
 *
 * @vendor Api
 */
class Update extends Base
{
	const INVALID_PARAMETERS = 'Invalid Parameters';
	const INVALID_EMPTY = 'Cannot be empty!';
	const INVALID_SET = 'Cannot be empty, if set';
	const INVALID_FLOAT = 'Should be a valid floating point';
	const INVALID_INTEGER = 'Should be a valid integer';
	const INVALID_NUMBER = 'Should be a valid number';
	const INVALID_BOOL = 'Should either be 0 or 1';
	const INVALID_SMALL = 'Should be between 0 and 9';
	const INVALID_ID = 'Invalid ID!';
	
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
		
		// app_id			Required
		if(!$this->isInteger($item['app_id'])) {
			$errors['app_id'] = self::INVALID_ID;
		}
		
		// app_name				Required
		if(isset($item['app_name']) 
		&& empty($item['app_name'])) {
			$errors['app_name'] = self::INVALID_SET;
		}
		
		// app_permissions		Required
		if(isset($item['app_permissions']) 
		&& empty($item['app_permissions'])) {
			$errors['app_permissions'] = self::INVALID_SET;
		}
		
		//OPTIONAL
		
		// app_flag
		if(isset($item['app_flag']) 
		&& !$this->isSmall($item['app_flag'])) {
			$errors['app_flag'] = self::INVALID_SMALL;
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
		
		//SET WHAT WE KNOW
		$model = control()->database()->model()
			// app_id
			->setAppId($item['app_id'])
			// app_updated
			->setAppUpdated($updated);
		
		//REQUIRED
		
		// app_name				Required
		if(!empty($item['app_name'])) {
			$model->setAppName($item['app_name']);
		}
			
		// app_permissions		Required
		if(!empty($item['app_permissions'])) {
			$model->setAppPermissions($item['app_permissions']);
		}
		
		//OPTIONAL
		
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
		if($this->isSmall($item['app_flag'])) {
			$model->setAppFlag($item['app_flag']);
		}
		
		//what's left ?
		$model->save('app');
		
		$this->trigger('app-update', $model);
		
		return $model;
	}
}
