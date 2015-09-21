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
class Index extends Page 
{
	const FAIL = 'Failed to get products';
	
	public function render() 
	{
		//get data
		$item = $this->data['item'];
		$item['public'] = true;
		
		//validate
		$errors = control()
			->model('profile')
			->list()
			->errors($item);
	
		if(!empty($errors)) {
			return $this->fail(self::FAIL, $errors);
		}
			
		//get total
		$search = control()
			->model('profile')
			->list()
			->process($item);

		//get total
		$total = $search->getTotal();
		$this->data['_total'] = $total;

		//get rows
		$rows = $search->getRows();
		$this->data['_rows'] = $rows;

		$this->success(array( 
			'total'	=> $this->data['_total'], 
			'rows'	=> $this->data['_rows']));
	}
}
