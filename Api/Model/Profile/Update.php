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
 * Model Update
 *
 * @vendor Api
 */
class Update extends Base
{
	const INVALID_PARAMETERS = 'Invalid Parameters';
	const INVALID_ID = 'Invalid ID';
	const INVALID_EMPTY = 'Cannot be empty!';
	const INVALID_SET = 'Cannot be empty, if set';
	const INVALID_FLOAT = 'Should be a valid floating point';
	const INVALID_INTEGER = 'Should be a valid integer';
	const INVALID_NUMBER = 'Should be a valid number';
	const INVALID_BOOL = 'Should either be 0 or 1';
	const INVALID_SMALL = 'Should be between 0 and 9';
	const INVALID_EMAIL = 'Invalid Email Format!';
	const INVALID_DATE = 'Invalid Date Format!';
	
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
		
		//REQUIRED
		
		// profile_id			Required
		if(!$this->isInteger($item['profile_id'])) {
			$errors['profile_id'] = self::INVALID_ID;
		}
		
		// profile_name			Required
		if(isset($item['profile_name']) 
		&& empty($item['profile_name'])) {
			$errors['profile_name'] = self::INVALID_SET;
		}
		
		//OPTIONAL
		
		//profile_email	
		if(isset($item['profile_email'])
		&& !$this->isEmail($item['profile_email'])) {
			$errors['profile_email'] = self::INVALID_EMAIL;
		}
		
		//profile_birth
		if(isset($item['profile_birth'])
		&& !$this->isDate($item['profile_birth'])) {
			$errors['profile_birth'] = self::INVALID_DATE;
		}
		
		// profile_flag
		if(isset($item['profile_flag']) 
		&& !$this->isSmall($item['profile_flag'])) {
			$errors['profile_flag'] = self::INVALID_SMALL;
		}
		
		return $errors;
	}
	
	/**
	 * 1. Update the address
	 * 2. Update the address label
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
		
		//generate dates
		$updated = date('Y-m-d H:i:s');
		
		//SET WHAT WE KNOW
		$model = control()->database()->model()
			// profile_id
			->setProfileId($item['profile_id'])
			// profile_updated
			->setProfileUpdated($updated);
		
		// profile_name
		if(!empty($item['profile_name'])) {
			$model->setProfileName($item['profile_name']);
		}
		
		// profile_email
		if($this->isEmail($item['profile_email'])) {
			$model->setProfileEmail($item['profile_email']);
		}
		
		// profile_phone
		if(isset($item['profile_phone'])) {
			$model->setProfilePhone($item['profile_phone']);
		}
		
		// profile_company		
		if(isset($item['profile_company'])) {
			$model->setProfileCompany($item['profile_company']);
		}
		
		// profile_job			
		if(isset($item['profile_job'])) {
			$model->setProfileJob($item['profile_job']);
		}
		
		// profile_gender		
		if(isset($item['profile_gender'])) {
			$model->setProfileGender($item['profile_gender']);
		}
		
		// profile_birth		
		if(isset($item['profile_birth'])) {
			$model->setProfileBirth($item['profile_birth']);
		}

		// profile_website		
		if(isset($item['profile_website'])) {
			$model->setProfileBirth($item['profile_website']);
		}

		// profile_facebook		
		if(isset($item['profile_facebook'])) {
			$model->setProfileBirth($item['profile_facebook']);
		}

		// profile_linkedin		
		if(isset($item['profile_linkedin'])) {
			$model->setProfileLinkedin($item['profile_linkedin']);
		}

		// profile_twitter		
		if(isset($item['profile_twitter'])) {
			$model->setProfileTwitter($item['profile_twitter']);
		}

		// profile_google		
		if(isset($item['profile_google'])) {
			$model->setProfileGoogle($item['profile_google']);
		}
		
		// profile_reference
		if(isset($item['profile_reference'])) {
			$model->setProfileReference($item['profile_reference']);
		}
		
		// profile_type
		if(isset($item['profile_type'])) {
			$model->setProfileType($item['profile_type']);
		}
		
		// profile_flag
		if($this->isSmall($item['profile_flag'])) {
			$model->setProfileFlag($item['profile_flag']);
		}
		
		//what's left ?
		$model->save('profile');
		
		$this->trigger('profile-update', $model);
		
		return $model;
	}
}