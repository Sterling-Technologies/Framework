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
class Login extends Page 
{
	const FAIL_NOT_EXISTS = 'User or Password is incorrect';
	const FAIL_VALIDATION = 'There are some errors on the form.';
	
	protected $title = 'Log In';

	public function render() 
	{
		$this->data['logo'] = true;
		
		//if it's a post
		if(!empty($_POST)) {
			return $this->check();
		}
		
		//Just load the page
		return $this->success();
	}

	/**
	 * When the form is submitted
	 *
	 * @return void
	 */
	protected function check() 
	{
		$item = $this->data['item'];

		//get errors
		$errors = control()->model('session')
			->login()
			->errors($item);

		if(!empty($errors)) {
			return $this->fail(self::FAIL_VALIDATION, $errors, $item);
		}
		
		$row = control()
			->model('session')
			->login()
			->process($item);

		if(empty($row)) {
			return $this->fail(self::FAIL_NOT_EXISTS);
		}

		unset($row['auth_password']);

		//assign a new session
		$_SESSION['me'] = $row;

		control()->redirect('/app/list');
	}
}

