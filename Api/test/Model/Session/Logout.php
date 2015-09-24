<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelSessionLogoutTest extends PHPUnit_Framework_TestCase
{
    public function testValidateLogoutFields() 
	{
        $errors = eve()->model('session')->logout()->errors();
		
		$this->assertEquals('Invalid ID', $errors['auth_id']);
    }
	
    public function testLogout() 
	{
		$auth = eve()->registry()->get('test', 'auth');

		$model = eve()
        	->model('session')
        	->logout()
        	->process(array(
				'auth_id' => $auth['auth_id']));

		$this->assertTrue(is_array($model->get()));
	}
}