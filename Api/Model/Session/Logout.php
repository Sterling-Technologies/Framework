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
 * Model Logout
 *
 * @vendor Api
 */
class Logout extends Base
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
		//prepare
		$item = $this->prepare($item);
		
		if(empty($item['auth_id'])) {
			$errors['auth_id'] = self::INVALID_ID;
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
		
		//remove the tokens associated with this user
		$search = eve()->database()
			->search('session')
			->innerJoinOn(
				'session_auth', 
				'session_auth_session = session_id')
			->filterBySessionAuthAuth($item['auth_id']);
		
		if(isset($item['session_token']) 
			&& $item['session_token']) {
			$search->addFilter(
				'session_token = %s OR session_status = %s', 
				$item['session_token'], 
				'PENDING');
		}
		
		$collection = $search
			->getCollection()
			->remove('session_auth')
			->remove('session');	
		
		$this->trigger('user-logout', $collection);
		
		return $collection;
	}
}
