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
class Search extends Page 
{
	protected $title = 'Apps';
	
	public function render() 
	{
		//get rows
		$rows = eve()->model('app')
			->list()
			->process()
			->innerJoinOn(
				'app_profile', 
				'app_profile_app = app_id')
			->filterByAppProfileProfile($_SESSION['me']['profile_id'])
			->getRows();

		$this->data['rows'] = $rows || [];
		$this->success();
	}
}
