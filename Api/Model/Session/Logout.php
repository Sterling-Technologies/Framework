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
 * Session Model Logout
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
class Logout extends Base
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
		
		if(!isset($data['auth_id']) || empty($data['auth_id'])) {
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
		
		//remove the tokens associated with this user
		$search = eve()->database()
			->search('session')
			->innerJoinOn(
				'session_auth', 
				'session_auth_session = session_id')
			->filterBySessionAuthAuth($data['auth_id']);
		
		if(isset($data['session_token']) 
			&& $data['session_token']) {
			$search->addFilter(
				'session_token = %s OR session_status = %s', 
				$data['session_token'], 
				'PENDING');
		}
		
		$collection = $search
			->getCollection()
			->loop(function($i) {
				if(!$this[$i]) {
					return false;	
				}
				
				$this[$i]
					->remove('session_auth')
					->remove('session');
			});	
		
		eve()->trigger('session-logout', $collection);
		
		return $collection;
	}
}
