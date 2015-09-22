<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Session;

class Logout extends PHPUnit_Framework_TestCase
{
    public function testValidateLogoutFields() 
	{
        $errors = control()->model('session')->logout()->errors();
		
		$this->assertEquals('Invalid ID!', $errors['auth_id']);
    }
	
    public function testLogout() 
	{
		$auth = control()->registry()->get('test', 'auth');

		$model = control()
        	->model('profile')
        	->logout()
        	->process(array(
				'auth_id' => $auth['auth_id']));

		$this->assertTrue(is_array($model));
	}
}