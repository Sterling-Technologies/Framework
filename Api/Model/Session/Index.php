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
 * Model Index
 *
 * @vendor Api
 */
class Index extends Base
{
	/**
	 * Factory for access
	 *
	 * @return Eve\Framework\Model\Session\Access
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
		return eve()
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
	 * @return Eve\Framework\Model\Session\Login
	 */
	public function login()
	{
		return Login::i();
	}
	
	/**
	 * Factory for logout
	 *
	 * @return Eve\Framework\Model\Session\Logout
	 */
	public function logout()
	{
		return Logout::i();
	}
	
	/**
	 * Factory for request
	 *
	 * @return Eve\Framework\Model\Session\Request
	 */
	public function request()
	{
		return Request::i();
	}
}
