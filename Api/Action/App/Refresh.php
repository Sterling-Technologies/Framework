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
class Refresh extends Page 
{
	const FAIL_NOT_EXISTS = 'App does not exist';
	const FAIL_PERMISSIONS = 'You do not have permissions to update.';
	const SUCCESS = 'App successfully refreshed!';
	
	protected $title = 'Updating App';

	public function render() 
	{
		$item = $this->data['item'];

		$item['app_id'] = (int) $this->data['params']['id'];
		
		//add profile_id
		$item['profile_id'] = $_SESSION['me']['profile_id'];

		//get app
		$row = eve()
			->model('app')
			->detail()
			->process($item)
			->innerJoinOn(
					'app_profile', 
					'app_profile_app = app_id')
			->getRow();

		if(empty($row)) {
			return $this->fail(self::FAIL_NOT_EXISTS, '/app/list');
		}

		//if not matched, fail
		if($row['app_profile_profile'] !== $this->data['me']['profile_id']) {
			return $this->fail(self::FAIL_PERMISSIONS, '/app/list');
		}
		
		eve()->job('app')
			->referesh(array(
				'data' => array(
					'item' => $item)));
		//success
		$this->success(self::SUCCESS, '/app/list');
	}
}
