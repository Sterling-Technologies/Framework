<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Api\Model\Auth;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Model Create
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
 */
class Create extends Base
{
	const MISMATCH = 'Passwords do not match!';
	
	/**
     * Returns errors if any
     *
     * @param array submitted data
     * @param array existing errors
     * @return array error
     */
	public function errors(array $data = array(), array $errors = array()) 
    {
		//prepare
		$data = $this->prepare($data);
		
		//REQUIRED
		
		//auth_slug		Required
		if(!isset($data['auth_slug']) || empty($data['auth_slug'])) {
			$errors['auth_slug'] = self::INVALID_REQUIRED;
		}
		
		// auth_permissions		Required
		if(!isset($data['auth_permissions']) || empty($data['auth_permissions'])) {
			$errors['auth_permissions'] = self::INVALID_REQUIRED;
		}
		
		//auth_password		Required
		if(!isset($data['auth_password']) || empty($data['auth_password'])) {
			$errors['auth_password'] = self::INVALID_REQUIRED;
		}
		
		//confirm		NOT IN SCHEMA
		if(!isset($data['confirm']) || empty($data['confirm'])) {
			$errors['confirm'] = self::INVALID_REQUIRED;
		} else if($data['confirm'] !== $data['auth_password']) {
			$errors['confirm'] = self::MISMATCH;
		}
		
		//OPTIONAL
		
		// auth_flag
		if(isset($data['auth_flag']) 
		&& !empty($data['auth_flag'])
		&& !$this('validation', $data['auth_flag'])->isType('small', true)) {
			$errors['auth_flag'] = self::INVALID_SMALL;
		}
		
		return $errors;
	}
	
	/**
	 * Process the form
	 *
	 * @param array data
	 * @return void
	 */
	public function process(array $data = array()) 
	{
		//prevent uncatchable error
        if(count($this->errors($data))) {
            throw new Exception(self::FAIL_406);
        }
		
		//prepare
		$data = $this->prepare($data);
		
		$password = md5($data['auth_password']);
		$token = md5(uniqid());
		$secret = md5(uniqid());
		$created = date('Y-m-d H:i:s');
		$updated = date('Y-m-d H:i:s');
		
		$model = eve()->database()->model()
			//auth_slug			Required
			->setAuthSlug($data['auth_slug'])
			
			//auth_slug			Required
			->setAuthPassword($password)
			
			//auth_token		Required
			->setAuthToken($token)
			
			//auth_secret		Required
			->setAuthSecret($secret)
			
			//auth_permissions	Required
			->setAuthPermissions($data['auth_permissions'])
			
			//auth_created		Required
			->setAuthCreated($created)
			
			//auth_updated		Required
			->setAuthUpdated($updated);
		
		// auth_type
		if(isset($data['auth_type']) && !empty($data['auth_type'])) {
			$model->setAuthType($data['auth_type']);
		}
		
		// auth_flag
		if(isset($data['auth_flag']) && !empty($data['auth_flag'])) {
			$model->setAuthFlag($data['auth_flag']);
		}
		
		// auth_facebook_token
		if(isset($data['auth_facebook_token']) && !empty($data['auth_facebook_token'])) {
			$model->setAuthFacebookToken($data['auth_facebook_token']);
		}
		
		// auth_facebook_secret
		if(isset($data['auth_facebook_secret']) && !empty($data['auth_facebook_secret'])) {
			$model->setAuthFacebookSecret($data['auth_facebook_secret']);
		}
		
		// auth_twitter_token
		if(isset($data['auth_twitter_token']) && !empty($data['auth_twitter_token'])) {
			$model->setAuthTwitterToken($data['auth_twitter_token']);
		}
		
		// auth_twitter_secret
		if(isset($data['auth_twitter_secret']) && !empty($data['auth_twitter_secret'])) {
			$model->setAuthTwitterSecret($data['auth_twitter_secret']);
		}
		
		// auth_linkedin_token
		if(isset($data['auth_linkedin_token']) && !empty($data['auth_linkedin_token'])) {
			$model->setAuthLinkedinToken($data['auth_linkedin_token']);
		}
		
		// auth_linkedin_secret
		if(isset($data['auth_linkedin_secret']) && !empty($data['auth_linkedin_secret'])) {
			$model->setAuthLinkedinSecret($data['auth_linkedin_secret']);
		}
		
		// auth_google_token
		if(isset($data['auth_google_token']) && !empty($data['auth_google_token'])) {
			$model->setAuthGoogleToken($data['auth_google_token']);
		}
		
		// auth_google_secret
		if(isset($data['auth_google_secret']) && !empty($data['auth_google_secret'])) {
			$model->setAuthGoogleSecret($data['auth_google_secret']);
		}
		
		$model->save('auth');
		
		eve()->trigger('auth-create', $model);
		
		return $model;
	}
}
