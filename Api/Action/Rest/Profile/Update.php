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
class Update extends Page 
{
	const FAIL = 'Could not update profile';
	const TITLE = 'Profile Update by %s';

	public function render() 
	{
		//get the data
		$item = $this->data['item'];
		$profileId = eve()->registry()->get('source', 'profile_id');

		//they cannot change their profile type
		if($item['profile_type']) {
			unset($item['profile_type'];
		}
		
		//validate
		$errors = eve()
			->model('profile')
			->update()
			->errors($item);
		
		if(!empty($errors)) {
			return $this->fail(self::FAIL, $errors);
		}
		
		// TODO 
		
		// //process
		// this.controller.queue.create('profile-update', {
		// 	title	: this.TITLE.replace('%s', this.source.profile_name),
		// 	item	: this.item,
		// }).save(next);
			
		//success
		$this->success();
	}
}
