<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\Session;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Model Login
 *
 * @vendor Api
 */
class Login extends Base
{
	const INVALID_PARAMETERS = 'Invalid Parameters';
	const INVALID_EMPTY = 'Cannot be empty!';
	
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
		
		if(empty($item['auth_slug'])) {
			$errors['auth_slug'] = self::INVALID_EMPTY;
		}
		
		if(empty($item['auth_password'])) {
			$errors['auth_password'] = self::INVALID_EMPTY;
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
		
		$search = eve()
			->database()
			->search('auth')
			->setColumns('profile.*', 'file_link AS profile_image', 'auth.*')
			->innerJoinOn('auth_profile', 'auth_profile_auth = auth_id')
			->innerJoinOn('profile', 'auth_profile_profile = profile_id')
			->innerJoinOn('profile_file', 'profile_file_profile = profile_id')
			->innerJoinOn('file', 'profile_file_file = file_id AND file_type = \'main_profile\'')
			->filterByAuthSlug($item['auth_slug'])
			->filterByAuthPassword(md5($item['auth_password']));
		

		$row = $search->getRow();
		
		$this->trigger('app-login', $row);

		return $row;
	}
}
