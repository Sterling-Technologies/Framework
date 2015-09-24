<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Api\Action\Rest\Profile;

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
class Detail extends Page 
{
	const FAIL = 'Failed to get profile';
	
	public function render() 
	{
		//get data
		$item = $this->data['item'];

		$id = eve()->registry()->get('request', 'variables', 0);
		$profileId = eve()->registry()->get('source', 'profile_id');
		
		$item = array('profile_id' => $id ||  $profileId);
	
		if((int) $item['profile_id'] !== (int) $profileId) {
			$item['public'] = true;
		}
		
		//validate
		$errors = eve()
			->model('profile')
			->detail()
			->errors($item);
		
		if(!empty($errors)) {
			return $this->fail(self::FAIL, $errors);
		}
		
		//process
		$row = eve()
			->model('profile')
			->detail()
			->process($item)
			->getRow();
		
		if(empty($row) || !isset($row['profile_id']) {
			return $this->fail(self::FAIL);
		}
		
		//success
		$this->success($row);	
	}
}
