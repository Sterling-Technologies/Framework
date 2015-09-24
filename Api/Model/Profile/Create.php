<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\Profile;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Model Create
 *
 * @vendor Api
 */
class Create extends Base
{
	const INVALID_PARAMETERS = 'Invalid Parameters';
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
	 * @param object submitted profile object
	 * @return object error object
	 */
	public function errors(array $item = array(), array $errors = array()) 
    {
		//prepare
		$item = $this->prepare($item);
		
		//REQUIRED
		
		//profile_name		Required
		if(!isset($item['profile_name'])
		|| empty($item['profile_name'])) {
			$errors['profile_name'] = self::INVALID_EMPTY;
		}
		
		//OPTIONAL
		
		//profile_email	
		if(isset($item['profile_email'])
		&& !$this('validation', $item['profile_email'])->isType('email', true)) {
			$errors['profile_email'] = self::INVALID_EMAIL;
		}
		
		//profile_birth
		if(isset($item['profile_birth'])
		&& !$this('validation', $item['profile_birth'])->isType('date', true)) {
			$errors['profile_birth'] = self::INVALID_DATE;
		}
		
		// profile_flag
		if(isset($item['profile_flag']) 
		&& !$this('validation', $item['profile_flag'])->isType('small', true)) {
			$errors['profile_flag'] = self::INVALID_SMALL;
		}
		
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
		
		//prepare
		$item = $this->prepare($item);
		
		//generate dates
		$created = date('Y-m-d H:i:s');
		$updated = date('Y-m-d H:i:s');
		
		//SET WHAT WE KNOW
		$model = eve()
			->database()
			->model()
			
			// profile_name			Required
			->setProfileName($item['profile_name'])

			// profile_created
			->setProfileCreated($created)
			
			// profile_updated
			->setProfileUpdated($updated);
		
		// profile_email
		if(isset($item['profile_email'])) {
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
		if(isset($item['profile_flag'])
			&& $this('validation', $item['profile_flag'])->isType('small', true)) {
			$model->setProfileFlag($item['profile_flag']);
		}
		
		//what's left ?
		$model->save('profile');
		
		 $this->trigger('profile-create', $model);
		 
		 return $model;
	}
}