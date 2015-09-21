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
	
	public function render() {
		throw new Exception('ummm, okay');
		
		$this->_body = array(
			array(
				'profile_id' => 1,
				'profile_name' => 'Ana',
				'profile_age' => 18,
				'profile_sex' => 'yes',
			),
			array(
				'profile_id' => 1,
				'profile_name' => 'Jane',
				'profile_age' => 16,
				'profile_sex' => 'no',
			));
		
		return $this->_success();
	}
}