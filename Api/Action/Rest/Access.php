<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Api\Action\Rest;

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
class Access extends Page 
{
	const FAIL_EXPIRED = 'Expired Token';
	
	public function render() 
	{
		//get the data
		$item = $this->data['item'];
		$item['client_id'] = $this->data['source']['access_token'];
		$item['client_secret'] = $this->data['source']['access_secret'];
		
		//validate
		$errors = control()
			->model('session')
			->access()
			->errors($item);
	
		if(isset($errors['code']) {
			return $this->fail($errors['code']);
		}
		
		//process
		$results = control()
			->job('session')
			->access(array('data' => array(
				'item' => $item)));
		
			
		$this->success($results['session']);	
	}
}
