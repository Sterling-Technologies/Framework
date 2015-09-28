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
class Create extends Html 
{
	const FAIL_406 = 'There are some errors on the form.';
	const SUCCESS_200 = 'You can now Log In!';

	protected $layout = '_blank';
	protected $title = 'Sign Up';

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

		$data['item']['auth_slug'] = $data['item']['profile_email'];
		$data['item']['auth_permissions'] = implode(',', eve()->settings('scope'));
		
		$config = eve()->settings('s3');
		
		$data['item']['file_link'] = $config['host'] . '/' 
			. $config['bucket'] . '/avatar/avatar-' 
			. ((floor(rand() * 1000) % 11) + 1) . '.png';
		
        //-----------------------//
        // 2. Validate
		$errors = eve()
			->model('auth')
			->create()
			->errors($data['item']);
		
		$errors = eve()
			->model('profile')
			->create()
			->errors($data['item'], $errors);
		
		//if there are errors
		if(!empty($errors)) {
			return $this->fail(
				self::FAIL_406, 
				$errors, 
				$data['item']);
		}
		
        //-----------------------//
        // 3. Process
		try {
			$auth = eve()
				->job('auth-create')
				->setData($data['item'])
				->run();
		} catch(\Exception $e) {
			return $this->fail(
				$e->getMessage(),
				array(),
				$data['item']
			);
		}
		
		return $this->success(self::SUCCESS_200, '/login');
	}
}

