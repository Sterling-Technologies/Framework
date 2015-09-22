<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Address;

class Remove extends PHPUnit_Framework_TestCase
{
    public function testRemovePublicAddress() 
	{	
		$publicAddress = control()->registry()->get('test', 'public_address');

        $row = control()->model('address')->remove()->process(array( 
			'address_id' => $publicAddress));

        // TODO
        $this->assertEquals();
    }

    public function testRemovePrivvateAddress() 
	{	
		$privateAddress = control()->registry()->get('test', 'private_address');

        $row = control()->model('address')->remove()->process(array( 
			'address_id' => $privateAddress));

        //TODO
        $this->assertEquals();
    }
}