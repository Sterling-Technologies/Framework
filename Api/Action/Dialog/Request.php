<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Api\Action\Dialog;

use Eve\Framework\Action\Json;
use Eve\Framework\Action\Html;

/**
 * Action
 *
 * GUIDE:
 * -- eve() - The current server controller
 *    use this to access the rest of the framework
 *
 *    -- eve()->database() - Returns the current database
 *
 *    -- eve()->model('noun') - Returns the given model factory
 *
 *    -- eve()->job('noun-action') - Returns a job following noun/action
 *
 *    -- eve()->settings('foo') - Returns a settings data originating
 *    from the settings path. ie. settings/foo.php
 *
 *    -- eve()->registry() - Returns Eden\Registry\Index used globally
 *
 * -- $this->request - The Request Object using Eden\Registry\Index
 *
 *    -- $this->request->get('post') - $_POST data
 *       You are free to use the $_POST variable if you like
 *
 *    -- $this->request->get('get') - $_GET data
 *       You are free to use the $_GET variable if you like
 *
 *    -- $this->request->get('server') - $_SERVER data
 *       You are free to use the $_SERVER variable if you like
 *
 *    -- $this->request->get('body') - raw body for 
 *       POST requests that provide JSON data for example
 *       instead of the default x-form-data
 *
 *    -- $this->request->get('method') - GET, POST, PUT or DELETE
 *
 * -- $this->response - The Response Object using Eden\Registry\Index
 *
 *    -- $this->response->set('body', 'Foo') - Sets the response body.
 *       Alternative for returning a string in render()
 *
 *    -- $this->response->set('headers', 'Foo', 'Bar') - Sets a 
 *       header item to 'Foo: Bar' given key/value
 *
 *    -- $this->response->set('headers', 'Foo', '') - Sets a 
 *       header item to 'Foo' given that no value is present
 *       QUIRK: $this->response->set('headers', 'Foo') will erase
 *       all existing headers
 */
class Request extends Html
{
	const FAIL_400 = 'Failed to create session.';
	const FAIL_406 = 'There are some errors on the form.';

	protected $title = 'Requesting Access';
	protected $layout = '_blank';

	public function render() 
	{
		$data = array('app' => $this->request->get('source'));
		
		//there should be a client_id, redirect_uri
		//client_id is already checked in the router
		//state is optional
		if(!isset($_GET['redirect_uri'])) {
			$this->template = 'dialog/invalid';
			return $this->success();
		}
		
		//scope by default is public_sso
		$data['request_permissions'] = 'public_sso';
		
		if(isset($_GET['scope'])) {
			$data['request_permissions'] = $_GET['scope'];
		}
		
		$data['request_permissions'] = explode(',', $data['request_permissions']);
		$data['app_permissions'] = $this->request->get('source', 'app_permissions');
		
		//make app permissions into an array
		$data['app_permissions'] = explode(',', $data['app_permissions']);
		
		//check scopes with registered app permissions
		$permitted = true;
		foreach ($data['request_permissions'] as $permission) {
			if(!in_array($permission, $data['app_permissions'])) {
				$permitted = false;
			}
		}
		
		//did they all match ?
		if(!$permitted) {
			$this->template = 'dialog/invalid';
			return $this->success();
		}
		
		//okay it is permitted
		//if there's not session
		if(!isset($_SESSION['me'])) {
			//go back to the login
			//pass the request query
			$query = $this->request->get('query');
			eve()->redirect('/dialog/login?' . $query);
			return;
		}
		
		//if it's a post
		if(!empty($_POST)) {
			return $this->check();
		}
		
		//no post, so we need to render
		//we want to sparse the user and other permissions(global)
		$routes = eve()->settings('routes');
		$roles = $routes['roles'];
		
		$data['user_permissions'] = array();
		$data['global_permissions'] = array();
		
		foreach($roles as $role => $meta) {
			if(strpos($role, 'user_') !== 0) {
				$data['global_permissions'][] = $role;
			}
		}
		
		foreach($data['request_permissions'] as $role) {
			//prevent random roles from being assigned
			if(!isset($roles[$role])) {
				continue;
			}
			
			//if its not a user permission, it's a global permission
			if(strpos($role, 'user_') !== 0) {
				continue;
			}
			
			//okay it has to be a user permission
			$data['user_permissions'][$role] = $roles[$role];
		}
		
		//Now we can load the page
		$this->body = $data;
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
		$data = array('item' => $this->request->get('post'));
		$data['item']['app_id'] = $this->request->get('source', 'app_id');
		$data['item']['auth_id'] = $_SESSION['me']['auth_id'];
		
		if(is_array($data['item']['session_permissions'])) {
			$data['item']['session_permissions'] = implode(',', $data['item']['session_permissions']);
		}
		
		//no need to process if the action is not allow
		if($data['item']['action'] !== 'allow') {
			//go back to the app
			return $this->redirect(array('error' => 'access_denied'));
		}

		$errors = eve()
			->model('session')
			->request()
			->errors($data['item']);
	
		if(!empty($errors)) {
			return $this->fail(
				self::FAIL_406, 
				$errors, 
				$data['item']);
		}
		
		//process
		$results = eve()
			->job('session-request')
			->setData($data['item'])
			->run();

		if(!isset($results['session'])) {
			return $this->fail(self::FAIL_400);
		}
		
		//success
		$this->redirect(array('code' => $results['session']['session_token']));
	}

	/**
	 * Creates a redirect url
	 *
	 * @param object extra parameters
	 * @return string
	 */
	protected function redirect(array $query = array()) 
	{
		$url = $_GET['redirect_uri'];
		
		if(isset($_GET['state'])) {
			$query['state'] = $_GET['state'];
		}
		
		$query = http_build_query($query);
		
		if(empty($query)) {
			eve()->redirect($url);
		}
		
		$separator = '?';
		if(strpos($url, '?') !== false) {
			$separator = '&';
		}
		
		eve()->redirect($url . $separator . $query);
	}
}
