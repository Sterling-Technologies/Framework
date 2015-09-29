<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Api\Action;

use Eve\Framework\Action\Json;
use Eve\Framework\Action\Html;

/**
 * Auth Action Update
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
class Update extends Page 
{
	const FAIL_401 = 'You do not have permissions to update';
	const FAIL_406 = 'There are some errors on the form.';
	const SUCCESS_200 = 'Account settings updated!';

	protected $title = 'Update Account';

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
		//-----------------------//
        // 1. Get Data
		$data = array();
		
		$data['item'] = $this->request->get('post');

		$data['item']['auth_id'] = $_SESSION['me']['auth_id'];
		$data['item']['profile_id'] = $_SESSION['me']['profile_id'];

		//-----------------------//
        // 2. Validate
		$errors = eve()
			->model('auth')
			->update()
			->errors($data['item']);
			
		$errors = eve()
			->model('profile')
			->update()
			->errors(
				$data['item'], 
				$errors);
		
		//if there are errors
		if(!empty($errors)) {
			return $this->fail(
				self::FAIL_406, 
				$errors, 
				$data['item']);
		}	

		$exists = eve()
			->model('auth')
			->exists($data['item']['profile_email']);

		//if exists, make sure it's me
		if(!empty($exists) 	&& $_SESSION['me']['auth_slug'] !== $item['profile_email']) {
			return $this->fail(self::FAIL_401);
		}
		
		//-----------------------//
        // 3. Process
		try {
			$results = eve()
				->job('auth-update')
				->setData($data['item'])
				->run();
		} catch(\Exception $e) {
			return $this->fail(
				$e->getMessage(),
				array(),
				$data['item']
			);	
		}
		
		$_SESSION['me']['auth_slug'] = $data['item']['auth_slug'];
		$_SESSION['me']['auth_updated']	= $results['auth']['auth_updated'];
		$_SESSION['me']['profile_name']	= $data['item']['profile_name'];
		$_SESSION['me']['profile_email']= $data['item']['profile_email'];
		$_SESSION['me']['profile_updated'] = $results['profile']['profile_updated'];

		//success
		return $this->success(
			self::SUCCESS_200, 
			'/app/search');
	}
}
