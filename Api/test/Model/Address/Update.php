<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Address;

class Update extends PHPUnit_Framework_TestCase
{
    public function testUpdateAddress() 
	{
		$publicAddress = control()->registry()->get('test', 'public_address');
     	
     	$model = control()
     		->model('address')
     		->update()
     		->process(array(
				'address_id'		=> $publicAddress,
				'address_country'	=> 'US' ));

		$this->assertEquals('US', $model['address_country']);
    }
}