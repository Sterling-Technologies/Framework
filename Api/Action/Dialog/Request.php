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
class Request extends Page 
{
	const FAIL_SESSION = 'Failed to create session.';
	const FAIL_VALIDATION = 'There are some errors on the form.';

	protected $title = 'Log In';

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
		
		//scope by default is public_sso
		$this->data['requestPermissions'] = $_GET['scope']  || 'public_sso';
		$this->data['requestPermissions'] = explode(',', $this->data['requestPermissions']);
		
		//okay it matches
		//make app permissions into an array
		$appPermissions = explode(',', $this->data['source']['app_permissions']);
		
		//check scopes with registered app permissions
		$permitted = true;
		foreach ($this->data['requestPermissions'] as $permission) {
			if(strpos($appPermissions, $permission) === false) {
				$permitted = false;
			}
		}
		
		//did they all match ?
		if(!$permitted) {
			$this->data['template'] = 'dialog-invalid';
			return $this->success();
		}
		
		//okay it is permitted
		//if there's not session
		if(!isset($_SESSION['me'])) {
			//go back to the login
			//pass the request query
			$query = $_SERVER['QUERY_STRING'];
			eve()->redirect('/dialog/login?' + $query);
			return;
		}
		
		//if it's a post
		if(!empty($_POST)) {
			return $this->check();
		}
		
		//no post, so we need to render
		//we want to sparse the user and other permissions(global)
		$roles = eve()->config('roles');
		
		$userPermissions 	= [];
		$globalPermissions 	= [];
		
		//give public permissions
		if(is_array($roles['Public']) {
			$globalPermissions = array_keys($roles['Public']);
		}
		
		foreach ($this->data['requestPermissions'] as $role) {
			//if its not a user permission, it's a global permission
			if(!is_array($roles['User'][$role]
				|| empty($roles['User'][$role])) {
				$globalPermissions = $role;
				return;
			}
			
			//okay it has to be a user permission
			$userPermissions = array(
				'name' => $role,
				'icon' => $roles['User'][$role]['icon'] || 'user',
				'title' => $roles['User'][$role]['title'],
				'description' => $roles['User'][$role]['description']
			});
		}
		
		//Now we can load the page
		$this->data['app'] = $this->data['source'];
		$this->data['user_permissions'] = $userPermissions;
		$this->data['global_permissions'] = $globalPermissions;
		
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
		//get the item
		$item = $this->data['item'];
		$item['app_id'] = this.request.source.app_id;
		$item['auth_id'] = $_SESSION['me']['auth_id'];
		
		if(is_array($item['session_permissions'])) {
			$item['session_permissions'] = implode(',', $item['session_permissions']);
		}
		
		//no need to process if the action is not allow
		if($item['action'] !== 'allow') {
			//go back to the app
			return $this->redirect(array('error' => 'access_denied'));
		}

		$errors = eve()
			->model('session')
			->request()
			->errors($item);
	
		if(!empty($errors)) {
			return $this->fail(self::FAIL_VALIDATION, $errors, $item);
		}
		
		//process
		$results = eve()
			->job('session')
			->request(array(
				'data' => array(
					'item' => $item)));

		if(!isset($results['session']) {
			return $this->fail(self::FAIL_SESSION);
		}
		
		//success
		$this->redirect(array('code': $results['session']['session_token']));
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
