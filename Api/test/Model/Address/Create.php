<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelAddressCreateTest extends PHPUnit_Framework_TestCase
{
    public function testValidateAddressFields() 
	{
        $errors = eve()->model('address')->create()->errors();
		
		$this->assertEquals('Cannot be empty!', $errors['address_street']);
		$this->assertEquals('Cannot be empty!', $errors['address_city']);
		$this->assertEquals('Cannot be empty!', $errors['address_country']);
		$this->assertEquals('Cannot be empty!', $errors['address_postal']);
    }
	
    public function testCreatePrivateAddress() 
	{	
		$model = eve()->model('address')->create()->process(array(
			'address_street'	=> 'TEST PRIVATE 123 Sesame Street',
			'address_city'		=> 'New York',
			'address_country'	=> 'PH',
			'something_rand'	=> 'okay random',
			'address_postal'	=> '12345' 
		));

		$this->assertTrue(is_numeric($model['address_id']));
		eve()->registry()->set('test', 'private_address', $model['address_id']);
	}

    public function testCreatePublicAddress() 
	{	
		$model = eve()->model('address')->create()->process(array(
			'address_street'	=> 'TEST PUBLIC 123 Sesame Street',
			'address_city'	=> 'New York',
			'address_country'	=> 'PH',
			'something_rand'	=> 'okay random',
			'address_postal'	=> '12345',
			'address_public'	=> 1 
		));

		$this->assertTrue(is_numeric($model['address_id']));
		eve()->registry()->set('test', 'public_address', $model['address_id']);
	}
}