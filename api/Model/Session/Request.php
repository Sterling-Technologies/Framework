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
 * SessionModel Request
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
class Request extends Base
{	
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
		
		if(empty($data['app_id'])) {
			$errors['app_id'] = self::INVALID_REQUIRED;
		}
		
		if(empty($data['auth_id'])) {
			$errors['auth_id'] = self::INVALID_REQUIRED;
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
		$created = date('Y-m-d H:i:s');
		$updated = date('Y-m-d H:i:s');
		
		if(!$data['session_permissions']) {
			$data['session_permissions'] = 'public_sso';
		}
		
		$model = eve()
			->database()
			->model()
			//session
			->setSessionToken($token)
			->setSessionSecret($secret)
			->setSessionPermissions($data['session_permissions'])
			->setSessionState('PENDING')
			->setSessionCreated($created)
			->setSessionUpdated($updated)
			//session_app
			->setSessionAppApp($data['app_id'])
			//session_auth
			->setSessionAuthAuth($data['auth_id']);
		
		//remove user pending states
		$search = eve()->database()
			->search('session')
			->innerJoinOn('session_auth', 'session_auth_session = session_id')
			->innerJoinOn('session_app', 'session_app_session = session_id')
			->filterBySessionAppApp($data['app_id'])
			->filterBySessionAuthAuth($data['auth_id'])
			->filterBySessionStatus('PENDING');
		
		$collection = $search->getCollection();

		// Both Physical and Virtual method Eden\Sql\Collection->remove() does not exist.
		$collection->loop(function($i) {
			if(!$this[$i]) {
				return false;	
			}
			
			$this[$i]
				->remove('session')
				->remove('session_auth')
				->remove('session_app');
		});	
		
		$model
			->save('session')
			->copy('session_id', 'session_app_session')
			->copy('session_id', 'session_auth_session')
			->insert('session_app')
			->insert('session_auth');
			
		eve()->trigger('session-request', $model);
			
		return $model;
	}
}
