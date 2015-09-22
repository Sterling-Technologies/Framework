<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Session;

class Login extends PHPUnit_Framework_TestCase
{
    public function testValidateLoginFields() 
	{
        $errors = control()->model('session')->login()->errors();
		
		$this->assertEquals('Cannot be empty!', $errors['auth_slug']);
    }
	
    public function testLogin() 
	{
		$auth = control()->registry()->get('test', 'auth');
		$profile = control()->registry()->get('test', 'profile');

		$row = control()
        	->model('profile')
        	->login()
        	->process(array(
				'auth_slug' => $auth['auth_slug'],
				'auth_passwod' => $auth['auth_passwod']));

		$this->assertEquals($profile['profile_name'], $row['profile_name']);
    }
}