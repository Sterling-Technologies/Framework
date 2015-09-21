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
 * Model Request
 *
 * @vendor Api
 */
class Request extends Base
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
		
		if(empty($item['app_id'])) {
			$errors['app_id'] = self::INVALID_ID;
		}
		
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
		
		$token = control()->help()->uid();
		$secret = control()->help()->uid();
		$created = date('Y-m-d H:i:s');
		$updated = date('Y-m-d H:i:s');
		
		if(!$item['session_permissions']) {
			$item['session_permissions'] = 'public_sso';
		}
		
		$model = control()
			->database()
			->model()
			//session
			->setSessionToken($token)
			->setSessionSecret($secret)
			->setSessionPermissions($item['session_permissions'])
			->setSessionState('PENDING')
			->setSessionCreated($created)
			->setSessionUpdated($updated)
			//session_app
			->setSessionAppApp($item['app_id'])
			//session_auth
			->setSessionAuthAuth($item['auth_id']);
		
		//remove user pending states
		$search = control()->database()
			->search('session')
			->innerJoinOn('session_auth', 'session_auth_session = session_id')
			->innerJoinOn('session_app', 'session_app_session = session_id')
			->filterBySessionAppApp($item['app_id'])
			->filterBySessionAuthAuth($item['auth_id'])
			->filterBySessionStatus('PENDING');
		
		$collection = $search->getCollection();
		
		$collection
			->remove('session')
			->remove('session_auth')
			->remove('session_app');
		
		$model
			->save('session')
			->copy('session_id', 'session_app_session')
			->copy('session_id', 'session_auth_session')
			->insert('session_app')
			->insert('session_auth');
			
		$this->trigger('user-request', $model);
			
		return $model;
	}
}
