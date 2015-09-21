<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\Session;

use Api\Model\Base;
use Api\Model\Argument;
use Api\Model\Exception;

/**
 * Model Index
 *
 * @vendor Api
 */
class Index extends Base
{
	/**
	 * Factory for access
	 *
	 * @return Api\Model\Session\Access
	 */
	public function access()
	{
		return Access::i();
	}
	
	/**
	 * Get profile by access token
	 * Random function needed...
	 *
	 * @param string
	 * @return array
	 */
	public function getProfileByToken($token) 
	{
		return control()
			->database()
			->search('session')
			->setColumns('profile.*')
			->innerJoinOn(
				'session_auth', 
				'session_auth_session = session_id')
			->innerJoinOn(
				'auth_profile', 
				'auth_profile_auth = session_auth_auth')
			->innerJoinOn(
				'profile', 
				'auth_profile_profile = profile_id')
			->filterBySessionToken($token)
			->getRow();
	}
	
	/**
	 * Factory for login
	 *
	 * @return Api\Model\Session\Login
	 */
	public function login()
	{
		return Login::i();
	}
	
	/**
	 * Factory for logout
	 *
	 * @return Api\Model\Session\Logout
	 */
	public function logout()
	{
		return Logout::i();
	}
	
	/**
	 * Factory for request
	 *
	 * @return Api\Model\Session\Request
	 */
	public function request()
	{
		return Request::i();
	}
}
