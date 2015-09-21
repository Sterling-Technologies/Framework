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
	const FAIL_NOT_ME = 'You do not have permissions to update';
	const FAIL_VALIDATION = 'There are some errors on the form.';
	const SUCCESS = 'Account settings updated!';

	protected $title = 'Update Accoun';

	/**
	 * Main action call
	 *
	 * @param object Request Object
	 * @param object Response Object
	 * @param function callback to pass to next Middleware
	 * @return void
	 */
	public function render() 
	{
		$this->data['blank'] = true;
		
		//there should be a client_id, redirect_uri
		//client_id is already checked in the router
		//state is optional
		if(!isset($_GET['redirect_uri'])) {
			$this->data['template'] = 'dialog-invalid';
			return $this->success();
		}
		
		//if they are not logged in
		//we cannot redirect them to be logged in
		//because we need to know the permissions
		if(!isset($_SESSION['me'])) {
			$this->redirect(array('error' => 'user_invalid' );
			return;
		}
		
		//if it's a post
		if(!empty($_POST)) {
			return $this->check();
		}
		
		$this->data['item'] = $_SESSION['me'];
		$this->data['cancel'] = $this->redirect(array('error' => 'user_cancel'), true);
		
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
		$item['auth_id'] = $_SESSION['me']['auth_id'];
		$item['profile_id'] = $_SESSION['me']['profile_id'];
		
		//validate 
		$errors = control()
			->model('auth')
			->update()
			->errors($item);
		
		$errors = control()
			->model('profile')
			->update()
			->errors($item, $errors);
		
		//if there are errors
		if(!empty($errors)) {
			return $this->fail(self::FAIL_VALIDATION, $errors, $item);
		}
		
		//validate exists
		$exists = control()
			->model('auth')
			->exists($item['profile_email']);
		
		//process
		//if exists, make sure it's me
		if($exists && $_SESSION['me']['auth_slug'] !== $item['profile_email']) {
			return $this->fail(self::FAIL_NOT_ME);	
		}
		
		$results = control()
			->job('auth')
			->update(array('data' => array(
				'item' => $item)));
		
		//end
		//assign a update session
		$_SESSION['me']['auth_slug'] = $item['auth_slug'];
		$_SESSION['me']['auth_updated']	= $results['auth']['auth_updated'];
		$_SESSION['me']['profile_name']	= $item['profile_name'];
		$_SESSION['me']['profile_email'] = $item['profile_email'];
		$_SESSION['me']['profile_updated'] = $results['profile']['profile_updated'];
		
		//success
		$this->redirect(array('success' => 1));
	}

	/**
	 * Creates a redirect url
	 *
	 * @param string the url
	 * @param object extra parameters
	 * @return string
	 */
	protected function redirect(array $query = array(), $returnUrl = null) {
		$url = $_GET['redirect_uri'];
		
		if(isset($_GET['state'])) {
			$query['state'] = $_GET['state'];
		}
		
		$query = http_build_query($query);
		
		if(empty($query)) {
			if(!empty($returnUrl)) {
				return $url;
			}
			
			control()->redirect($url);
			return;
		}
		
		$separator = '?';
		if(strpos($url, '?') === false) {
			$separator = '&';
		}
		
		control()->redirect($url + $separator + $query);
	}
}
