<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Address;

class Create extends PHPUnit_Framework_TestCase
{
    public function testValidateAddressFields() 
	{
        $errors = control()->model('address')->create()->errors();
		
		$this->assertEquals('Cannot be empty!', $errors['address_street']);
		$this->assertEquals('Cannot be empty!', $errors['address_city']);
		$this->assertEquals('Cannot be empty!', $errors['address_country']);
		$this->assertEquals('Cannot be empty!', $errors['address_postal']);
    }
	
    public function testCreatePrivateAddress() 
	{	
		$model = control()->model('address')->create()->process(array(
			'address_street'	=> 'TEST PRIVATE 123 Sesame Street',
			'address_city'		=> 'New York',
			'address_country'	=> 'PH',
			'something_rand'	=> 'okay random',
			'address_postal'	=> '12345' 
		));

		$this->assertTrue(is_int($model['address_id']));
		control()->registry()->set('test', 'private_address', $model['address_id']);
	}

    public function testCreatePublicAddress() 
	{	
		$model = control()->model('address')->create()->process(array(
			'address_street'	=> 'TEST PUBLIC 123 Sesame Street',
			'address_city'	=> 'New York',
			'address_country'	=> 'PH',
			'something_rand'	=> 'okay random',
			'address_postal'	=> '12345',
			'address_public'	=> 1 
		));

		$this->assertTrue(is_int($model['address_id']));
		control()->registry()->set('test', 'public_address', $model['address_id']);
	}
}