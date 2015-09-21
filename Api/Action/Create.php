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
class Index extends Page 
{
	const FAIL_EXISTS = 'Email exists.';
	const FAIL_VALIDATION = 'There are some errors on the form.';
	const SUCCESS = 'You can now Log In!';


	protected $title = 'Sign Up';

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
		$item['auth_slug'] = $item['profile_email'];
		$item['auth_permissions'] = implode(',', control()->config('scope'));
		$item['profile_type'] = 'buyer';
		
		$config = control()->config('s3');
		
		$item['file_link'] = $config['host'] + '/' 
			+ $config['bucket'] + '/avatar/avatar-' 
			+ ((floor(rand() * 1000) % 11) + 1) + '.png';
		
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
		
		$auth = control()
			->job('auth')
			->create(
				array('data' => array(
				'item' => $item)));

		return $this->success(self::SUCCESS, '/developer/login');
	}
}

