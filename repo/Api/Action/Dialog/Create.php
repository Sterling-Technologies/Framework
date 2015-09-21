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
class Create extends Page 
{
	const FAIL_EXISTS = 'Email exists.';
	const FAIL_VALIDATION = 'There are some errors on the form.';
	const SUCCESS = 'You can now Log In!';

	protected $title = 'Sign Up';

	public function render() 
	{
		$this->data['logo'] = true;
		
		//if there's a session
		if(isset($_SESSION['me'])) {
			//no need to login
			return $this->success();
		}

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
	protected function check()
	{
		//get the item
		$item = $this->data['item'];
		
		$item['auth_slug'] = $item['profile_email'];
		$item['auth_permissions'] = implode(',', control()->config('scope'));
		$item['profile_type'] = 'buyer';
		
		//validate
		$errors = control()
			->model('auth')
			->create()
			->errors($item);
		
		$errors = control()
			->model('profile')
			->create()
			->errors($item, $errors);
		
		//if there are errors
		if(!empty($errors)) {
			return $this->fail(self::FAIL_VALIDATION, $errors, $item);
		}
			
		
		//process
		$results = control()
			->job('auth')
			->create(array(
				'data' => array(
					'item' => $item))
			);
		
		//success
		$query = $_SERVER['QUERY_STRING']
		
		$_SESSION['message'] = self::SUCCESS;
		$_SESSION['type'] = 'success';

		control()->redirect('/dialog/login?' + $query);
	}
}
