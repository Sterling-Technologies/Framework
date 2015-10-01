<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Api\Model\Profile;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Model Set
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
class Set extends Base
{
	const INVALID_REFERENCE = 'You need to provide either an email or id.';
	
	/**
	 * Returns errors if any
	 *
	 * @param object submitted profile object
	 * @return object error object
	 */
	public function errors(array $data = array(), array $errors = array()) 
    {
		//prepare
		$data = $this->prepare($data);
		
		//the uniqueness of a profile is from their id or email
		//if they change their email, they must provide a profile id
		//because there is no way to reference what to update
		//Simply put,
		//if profile id, we simply update it
		//if no profile id and email, $search for the email
		//	and if found, update it
		//	otherwise, insert it
		if(!is_numeric($data['profile_id'])
		&& !$this('validation', $data['profile_email'])->isType('email', true)) {
			$errors['profile_id'] 		= self::INVALID_REFERENCE;
			$errors['profile_email'] 	= self::INVALID_REFERENCE;
		}
		
		//if we do have a number, just update it
		if(isset($data['profile_id']) 
			&& is_numeric($data['profile_id'])) {
			return eve()->model('profile')->update()->errors($data, $errors);
		}
		
		//at this point we should have an email at least
		//we don't know if we should test for create or update
		//best to just return what we got
		return $errors;
	}
	
	/**
	 * Processes the form
	 *
	 * @param object profile object
	 * @return void
	 */
	public function process(array $data = array()) 
	{
		//prevent uncatchable error
        if(count($this->errors($data))) {
            throw new Exception(self::FAIL_406);
        }
		
		//the uniqueness of a profile is from their id or email
		//if they change their email, they must provide a profile id
		//because there is no way to reference what to update
		//Simply put,
		//if profile id, we simply update it
		//if no profile id and email, $search for the email
		//	and if found, update it
		//	otherwise, insert it
		
		//if we do have a number, just update it
		if(is_numeric($data['profile_id'])) {
			return Update::i()->process($data);
		}
		
		//at this point we should have an email at least
		//search for it
		$search = eve()
			->database()
			->search('profile')
			->filterByProfileEmail($data['profile_email']);
			
		$row = $search->getRow();
		
		//if we found it
		if($row) {
			//update it
			$data['profile_id'] = $row['profile_id'];
			
			return Update::i()->process($data);
		}
		
		//insert it
		return Create::i()->process($data);
	}
}