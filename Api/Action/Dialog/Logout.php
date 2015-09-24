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
class Index extends Page 
{
	
	public function render() 
	{
		//there should be a client_id, redirect_uri
		//client_id is already checked in the router
		//state is optional
		if(!isset($_GET['redirect_uri'])) {
			$this->data['template'] = 'dialog-invalid';
			return $this->success();
		}
		
		if(!isset($_SESSION['me'])) {
			return $this-redirect(array('error' => 'user_invalid'));
		}
		
		$item = array('auth_id' => $_SESSION['me']['auth_id']);
		
		if(isset($_GET['session_token'])) {
			$item['session_token'] = $_GET['session_token'];
		}
		
		$errors = eve()
			->model('session')
			->logout()
			->errors($item);
		
		if(isset($errors['auth_id']) {
			return $this->redirect(array('error' => 'user_invalid'));
		}
		
		eve()
			->model('session')
			->logout()
			->process($item);

		unset($_SESSION['me'];
		
		$this->redirect(array( 'success': 1 ));	
		
	}

	/**
	 * Creates a redirect url
	 *
	 * @param string the url
	 * @param object extra parameters
	 * @return string
	 */
	protected function redirect(array $query = array()) {

		$url = $_GET['redirect_uri'];
		
		if(isset($_GET['state'])) {
			$query['state'] = $_GET['state'];
		}
		
		$query = http_build_query($query);
		
		if(empty($query)) {
			eve()->redirect($url);
		}
		
		$separator = '?';
		if(strpos($url, '?') === false) {
			$separator = '&';
		}
		
		eve()->redirect($url + $separator + $query);
	}
}
