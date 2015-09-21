<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Api\Action\Dialog;

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
	const FAIL_VALIDATION = 'There are some errors on the form.';
	const FAIL_NOT_EXISTS = 'User or Password is incorrect';
	
	protected $title = 'Log In';

	public function render() 
	{
		//there should be a client_id, redirect_uri
		//client_id is already checked in the router
		//state is optional
		if(!isset($_GET['redirect_uri'])) {
			$this->data['template'] = 'dialog-invalid';
			return $this->success();
		}
		
		//okay it is permitted
		//if there's a session
		if(isset($_SESSION['me'])) {
			//no need to login
			$query = $_SERVER['QUERY_STRING'];
			control()->redirect('/dialog/request?' + $query);
		}
		
		$this->data['logo'] = true;
		
		//if it's a post
		if(!empty($_POST)) {
			return $this->check();
		}
		
		$this->data['query'] = $_SERVER['QUERY_STRING'];
		
		//Just load the page
		return $this->success();
	}

	/* Methods
	-------------------------------*/
	/**
	 * When the form is submitted
	 *
	 * @return void
	 */
	protected function check() {
		//validate
		//get errors
		$errors = control()
			->model('session')
			->login()
			->errors($item);
	
		//if there are errors
		if(!empty($errors)) {
			return $this->fail(self::FAIL_VALIDATION, $errors, $item);
		}
		
		//login
		$row = control()
			->model('session')
			->login()
			->process($item);
		
		if(empty($row)) {
			return $this->fail(self::FAIL_NOT_EXISTS);
		}

		unset($row['auth_password'];
			
		//assign a new session
		$_SESSION['me'] = $row;
		
		//pass the request query
		$query = $_SERVER['QUERY_STRING'];
		control()->redirect('/dialog/request?' + $query);
	
	}
}
