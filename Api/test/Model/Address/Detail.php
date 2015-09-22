<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Address;

class Detail extends PHPUnit_Framework_TestCase
{
    public function testGetPublicAddress() 
	{	
		$publicAddress = control()->registry()->get('test', 'public_address');
     	
     	$row = control()
     		->model('address')
			->detail()
			->process(array('address_id' => $publicAddress))
			->getRow();
		
		$this->assertEquals('TEST PUBLIC 123 Sesame Street', $row['address_street']);
    }
	
    public function testGetPrivateAddress() 
	{	
		$privateAddress = control()->registry()->get('test', 'private_address');
     	$row = control()
     		->model('address')
			->detail()
			->process(array('address_id' => $privateAddress))
			->getRow();
		
		$this->assertEquals('TEST PUBLIC 123 Sesame Street', $row['address_street']);
    }
}