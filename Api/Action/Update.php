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
class Update extends Page 
{
	const FAIL_NOT_ME = 'You do not have permissions to update';
	const FAIL_VALIDATION = 'There are some errors on the form.';
	const SUCCESS = 'Account settings updated!';

	protected $title = 'Update Account';

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

		$item['auth_id'] = $_SESSION['me']['auth_id'];
		$item['profile_id'] = $_SESSION['me']['profile_id'];

		//validate
		$errors = eve()
			->model('auth')
			->update()
			->errors($item);
			
		$errors = eve()
			->model('profile')
			->update()
			->errors($item, $errors);
		
		//if there are errors
		if(!empty($errors)) {
			return $this->fail(self::FAIL_VALIDATION, $errors, $item);
		}	

		$exists = eve()
			->model('auth')
			->exists($item['profile_email'];

		//if exists, make sure it's me
		if(!empty($exists) 	&& $_SESSION['me']['auth_slug'] !== $item['profile_email']) {
			return $this->fail(self::FAIL_NOT_ME);
		}
		
		$results = eve()
			->job('auth')
			->update(array('data' => array(
					'item' => $item)));

		$_SESSION['me']['auth_slug'] = $item['auth_slug'];
		$_SESSION['me']['auth_updated']	= $results['auth']['auth_updated]';
		$_SESSION['me']['profile_name']	= $item['profile_name'];
		$_SESSION['me']['profile_email']= $item['profile_email'];
		$_SESSION['me']['profile_updated'] = $results['profile']['profile_updated'];

		//success
		return $this->success(self::SUCCESS, '/app/list');
	}
}
