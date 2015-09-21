<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\Auth;

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
	const INVALID_ID = 'Invalid ID';
	const MISMATCH = 'Passwords do not match!';
	
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
		
		// auth_id			Required
		if(!$this->isInteger($item['auth_id'])) {
			$errors['auth_id'] = self::INVALID_ID;
		}
		
		//auth_slug		Required
		if(isset($item['auth_slug']) 
		&& empty($item['auth_slug'])) {
			$errors['auth_slug'] = self::INVALID_EMPTY;
		}
		
		// auth_permissions		Required
		if(isset($item['auth_permissions']) 
		&& empty($item['auth_permissions'])) {
			$errors['auth_permissions'] = self::INVALID_SET;
		}
		
		//confirm			NOT IN SCHEMA
		if(((!empty($item['auth_password']))
		|| (!empty($item['confirm'])))
		&& $item['confirm'] !== $item['auth_password']) {
			$errors['confirm'] = self::MISMATCH;
		}
		
		//OPTIONAL
		
		// auth_flag
		if(isset($item['auth_flag']) 
		&& !$this->isSmall($item['auth_flag'])) {
			$errors['auth_flag'] = self::INVALID_SMALL;
		}
		
		return $errors;
	}
	
	/**
	 * Process the form
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
		
		$updated = date('Y-m-d H:i:s');
		
		$model = control()
			->database()
			->model()
			
			//auth_id			Required
			->setAuthId($item['auth_id'])
			
			//auth_updated		Required
			->setAuthUpdated($updated);
		
		// auth_permissions		Required
		if(!empty($item['auth_permissions'])) {
			$model->setAuthPermissions($item['auth_permissions']);
		}
		
		if(!empty($item['auth_password'])) {
			$model->setAuthPassword(md5($item['auth_password']));
		}
		
		if(!empty($item['auth_slug'])) {
			$model->setAuthSlug($item['auth_slug']);
		}
		
		//OPTIONAL
		
		// auth_type
		if(isset($item['auth_type'])) {
			$model->setAuthType($item['auth_type']);
		}
		
		// auth_flag
		if($this->isSmall($item['auth_flag'])) {
			$model->setAuthFlag($item['auth_flag']);
		}
		
		// auth_facebook_token
		if(isset($item['auth_facebook_token'])) {
			$model->setAuthFacebookToken($item['auth_facebook_token']);
		}
		
		// auth_facebook_secret
		if(isset($item['auth_facebook_secret'])) {
			$model->setAuthFacebookSecret($item['auth_facebook_secret']);
		}
		
		// auth_twitter_token
		if(isset($item['auth_twitter_token'])) {
			$model->setAuthTwitterToken($item['auth_twitter_token']);
		}
		
		// auth_twitter_secret
		if(isset($item['auth_twitter_secret'])) {
			$model->setAuthTwitterSecret($item['auth_twitter_secret']);
		}
		
		// auth_linkedin_token
		if(isset($item['auth_linkedin_token'])) {
			$model->setAuthLinkedinToken($item['auth_linkedin_token']);
		}
		
		// auth_linkedin_secret
		if(isset($item['auth_linkedin_secret'])) {
			$model->setAuthLinkedinSecret($item['auth_linkedin_secret']);
		}
		
		// auth_google_token
		if(isset($item['auth_google_token'])) {
			$model->setAuthGoogleToken($item['auth_google_token']);
		}
		
		// auth_google_secret
		if(isset($item['auth_google_secret'])) {
			$model->setAuthGoogleSecret($item['auth_google_secret']);
		}
		
		$model->save('auth');
		
		$this->trigger('auth-update', $model);
		
		return $model;
	}
}
