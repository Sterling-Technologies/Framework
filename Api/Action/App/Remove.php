<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Api\Action\App;

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
class Remove extends Page 
{
	const FAIL_NOT_EXISTS = 'App does not exist';
	const FAIL_PERMISSIONS = 'You do not have permissions to remove';
	const SUCCESS = 'Tokens successfully removed!';

	public function render() 
	{
		//get the item
		$item = array(
			'app_id'		=> (int) $this->data['params']['id'],
			'profile_id'	=> $_SESSION['me']['profile_id']);

		//validate
		//get errors
		$errors = eve()
			->model('app')
			->remove()
			->errors($item);
		
		//if errors, fail
		if(isset($errors['app_id']) && !empty($errors['app_id'])) {
			return $this->fail($errors['app_id'], '/app/list');
		}

		//check permissions
		$yes = eve()->model('app')
			->permissions(
				$item['app_id'], 
				$item['profile_id']);

		//if not permitted, fail
		if(empty($yes)) {
			return $this->fail(self::FAIL_PERMISSIONS, '/app/list');
		}

		eve()->job('app')
			->remove(array(
				'data' => array(
					'item' => $item))
			);

		$this->success(self::SUCCESS, '/app/list');

	}
}
