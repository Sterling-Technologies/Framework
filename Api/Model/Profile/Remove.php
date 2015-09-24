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
 * Model Remove
 *
 * @vendor Api
 */
class Remove extends Base
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
		
		// profile_id			Required
		if(isset($item['profile_id'])
			&& !$this('validation', $item['profile_id'])->isType('integer', true)) {
			$errors['profile_id'] = self::INVALID_ID;
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
		
		$model = eve()->database()
			->model()
			->setProfileId($item['profile_id'])
			->setProfileActive('0');
		
		$model->save('profile');
		
		$this->trigger('profile-remove', $model);
		
		return $model;
	}
}