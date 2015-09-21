<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\Profile;

use Api\Model\Base;
use Api\Model\Argument;
use Api\Model\Exception;

/**
 * Model Set
 *
 * @vendor Api
 */
class Set extends Base
{
	const INVALID_PARAMETERS = 'Invalid Parameters';
	const INVALID_REFERENCE = 'You need to provide either an email or id.';
	
	/**
	 * Returns errors if any
	 *
	 * @param object submitted profile object
	 * @return object error object
	 */
	public function errors(array $item = array(), array $errors = array()) 
    {
		//prepare
		$item = $this->prepare($item);
		
		//the uniqueness of a profile is from their id or email
		//if they change their email, they must provide a profile id
		//because there is no way to reference what to update
		//Simply put,
		//if profile id, we simply update it
		//if no profile id and email, $search for the email
		//	and if found, update it
		//	otherwise, insert it
		if(!is_numeric($item['profile_id'])
		&& !$this->isEmail($item['profile_email'])) {
			$errors['profile_id'] 		= self::INVALID_REFERENCE;
			$errors['profile_email'] 	= self::INVALID_REFERENCE;
		}
		
		//if we do have a number, just update it
		if(is_numeric($item['profile_id'])) {
			return $this->model('profile')->update()->errors($item, $errors);
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
	public function process(array $item = array()) 
	{
		//prevent uncatchable error
		if(count($this->errors($item))) {
			throw new Exception(self::INVALID_PARAMETERS);
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
		if(is_numeric($item['profile_id'])) {
			return Update::i()->process($item);
		}
		
		//at this point we should have an email at least
		//search for it
		$search = control()
			->database()
			->search('profile')
			->filterByProfileEmail($item['profile_email']);
			
		$row = $search->getRow();
		
		//if we found it
		if($row) {
			//update it
			$item['profile_id'] = $row['profile_id'];
			
			return Update::i()->process($item);
		}
		
		//insert it
		return Create::i()->process($item);
	}
}