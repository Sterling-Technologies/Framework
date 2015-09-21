<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Api\Action;

use Api\Action;
use Api\Page;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Logout extends Page 
{
	const SUCCESS = 'You are now Logged Out!';
	public function render() 
	{
		if(!isset($_SESSION['me'])) {

			return $this->success(self:SUCCESS, '/developer/login');
		}
		
		$item = array('auth_id' => $_SESSION['me']['auth_id']);

		$errors = control()
			->model('session')
			->logout()
			->errors($item);
		
		if(isset($errors['auth_id'])) {
			return $this->fail($errors['auth_id'], '/app/list');
		}
		
		control()->model('session')
			->logout()
			->process($item);
		
		unset($_SESSION['me']);
		
		return $this->success(self::SUCCESS, '/developer/login');
	}
}
