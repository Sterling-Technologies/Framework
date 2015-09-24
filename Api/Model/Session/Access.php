<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\Session;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Model Access
 *
 * @vendor Api
 */
class Access extends Base
{
	const INVALID_PARAMETERS = 'Invalid Parameters';
	const INVALID_EMPTY = 'Cannot be empty!';
	const EXPIRED = 'Expired Token';
	
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
		
		if(empty($item['client_id'])) {
			$errors['client_id'] = self::INVALID_EMPTY;
		}
		
		if(empty($item['client_secret'])) {
			$errors['client_secret'] = self::INVALID_EMPTY;
		}
		
		if(empty($item['code'])) {
			$errors['code'] = self::INVALID_EMPTY;
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
			->innerJoinOn('profile_file', 'profile_file_profile = profile_id')
			->innerJoinOn('file', "profile_file_file = file_id AND file_type='main_profile'")
			->filterByAppToken($item['client_id'])
			->filterByAppSecret($item['client_secret'])
			->filterBySessionToken($item['code']);
			
		$model = $search->getModel();

		if(!$model || $model['session_status'] !== 'PENDING') {
			throw new Exception(self::EXPIRED);
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
		
		$this->trigger('user-request', $model);
		
		return array(
			'profile_id' => $model['profile_id'],
			'profile_name' => $model['profile_name'],
			'profile_email' => $model['profile_email'],
			'profile_phone' => $model['profile_phone'],
			'profile_detail' => $model['profile_detail'],
			'profile_birth' => $model['profile_birth'],
			'profile_gender' => $model['profile_gender'],
			'profile_website' => $model['profile_website'],
			'profile_facebook' => $model['profile_facebook'],
			'profile_twitter' => $model['profile_twitter'],
			'profile_linkedin' => $model['profile_linkedin'],
			'profile_google' => $model['profile_google'],
			'profile_type' => $model['profile_type'],
			'profile_image'	=> $model['file_link'],
			'access_token' => $model['session_token'],
			'access_secret' => $model['session_secret'],
			'access_permissions' => explode(',', $model['session_permissions']) );
	}
}