<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Api\Action;

use Eve\Framework\Action\Json;
use Eve\Framework\Action\Html;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Login extends Html 
{
	const FAIL_NOT_EXISTS = 'User or Password is incorrect';
	const FAIL_VALIDATION = 'There are some errors on the form.';
	
	protected $title = 'Log In';
	protected $layout = '_blank';
	
	/**
	 * Main method used for rendering output
	 *
	 * @return void
	 */
	public function render() 
	{
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
		$item = $this->request->get('post');

		//get errors
		$errors = eve()
			->model('session')
			->login()
			->errors($item);

		if(!empty($errors)) {
			return $this->fail($response, self::FAIL_VALIDATION, $errors, $item);
		}
		
		$row = eve()
			->model('session')
			->login()
			->process($item);

		if(empty($row)) {
			return $this->fail(self::FAIL_NOT_EXISTS);
		}

		unset($row['auth_password']);

		//assign a new session
		$_SESSION['me'] = $row;

		eve()->redirect('/app/list');
	}
}

