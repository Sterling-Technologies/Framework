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
 * Model Remove
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
class Remove extends Base
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
		
		//REQUIRED
		
		// auth_id			Required
		if(!isset($data['auth_id'])
			|| !$this('validation', $data['auth_id'])->isType('integer', true)
		) {
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
		
		$model = eve()
			->database()
			->model()
			->setAuthId($data['auth_id'])
			->setAuthActive('0');
			
		$model->save('auth');
		
		eve()->trigger('auth-remove', $model);
		
		return $model;
	}
}