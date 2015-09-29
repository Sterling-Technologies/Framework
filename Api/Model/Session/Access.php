<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Api\Model\Session;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Session Model Access
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
class Access extends Base
{
	const FAIL_410 = 'Expired Token';
	
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

		// client_id		required
		if(!isset($data['client_id']) || empty($data['client_id'])) {
			$errors['client_id'] = self::INVALID_REQUIRED;
		}
		
		// client_secret		required
		if(!isset($data['client_secret']) || empty($data['client_secret'])) {
			$errors['client_secret'] = self::INVALID_REQUIRED;
		}
		
		// code		required
		if(!isset($data['code']) || empty($data['code'])) {
			$errors['code'] = self::INVALID_REQUIRED;
		}
		
		return $errors;
	}
	
	/**
	 * Processes the form
	 *
	 * @param array data
	 * @return mixed
	 */
	public function process(array $data = array()) 
	{
		//prevent uncatchable error
		if(count($this->errors($data))) {
			throw new Exception(self::FAIL_406);
		}
		
		//prepare
		$data = $this->prepare($data);
		
		$token = md5(uniqid());
		$secret = md5(uniqid());
		$updated = date('Y-m-d H:i:s');
		
		//check the session first
		$search = eve()
			->database()
			->search('session')
			->innerJoinOn('session_app', 'session_app_session = session_id')
			->innerJoinOn('app', 'session_app_app = app_id')
			->innerJoinOn('session_auth', 'session_auth_session = session_id')
			->innerJoinOn('auth_profile', 'session_auth_auth = auth_profile_auth')
			->innerJoinOn('profile', 'auth_profile_profile = profile_id')
			->filterByAppToken($data['client_id'])
			->filterByAppSecret($data['client_secret'])
			->filterBySessionToken($data['code']);
			
		$model = $search->getModel();

		if(!$model || $model['session_status'] !== 'PENDING') {
			throw new Exception(self::FAIL_410);
		}
		
		//okay it matches
		//Vulnerability lets assume the session permissions is valid
		//we just process from here
		$model
			->setSessionToken($token)
			->setSessionSecret($secret)
			->setSessionStatus('ACCESS')
			->setSessionUpdated($updated);
		
		$model->save();
		
		eve()->trigger('session-access', $model);
		
		return array(
			'profile_id' => $model['profile_id'],
			'profile_name' => $model['profile_name'],
			'profile_email' => $model['profile_email'],
			'profile_phone' => $model['profile_phone'],
			'profile_detail' => $model['profile_detail'],
			'access_token' => $model['session_token'],
			'access_secret' => $model['session_secret'],
			'access_permissions' => explode(',', $model['session_permissions']) );
	}
}