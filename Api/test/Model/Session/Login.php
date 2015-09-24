<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelSessionLoginTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
		$callback = include(__DIR__.'/../../helper/create-session.php');
		$settings = $callback(array(
			'profile_name'	=> 'TEST AUTH '.microtime(),//. Date.now(),
			'auth_slug'		=> 'TEST AUTH '.microtime(),// . Date.now(),
			'auth_password'	=> '123456',
			'file_link'		=> 'http://example.com/sample.gif'));

				// fixtu/re.app 			= results.app;
				// fixture.auth 			= results.auth;
				// fixture.file 			= results.file;
				// fixture.profile 		= results.profile;
				// fixture.session 		= results.session;
				// fixture.profile_name 	= results.profile_name;
				// fixture.auth_slug 		= results.auth_slug;
				// fixture.auth_password 	= results.auth_password;
				// fixture.file_link 		= results.file_link;
		eve()->registry()->set('test', 'app', $settings['app']);
		eve()->registry()->set('test', 'auth', $settings['auth']);
		eve()->registry()->set('test', 'file', $settings['file']);
		eve()->registry()->set('test', 'profile', $settings['profile']);
		eve()->registry()->set('test', 'profile', $settings['profile']);
		eve()->registry()->set('test', 'session', $settings['session']);
				
	}

    public function testValidateLoginFields() 
	{
        $errors = eve()->model('session')->login()->errors();
		
		$this->assertEquals('Cannot be empty!', $errors['auth_slug']);
    }
	
    public function testLogin() 
	{
		$auth = eve()->registry()->get('test', 'auth');
		$profile = eve()->registry()->get('test', 'profile');

		$row = eve()
        	->model('session')
        	->login()
        	->process(array(
				'auth_slug' => $auth['auth_slug'],
				'auth_password' => $auth['auth_password']));
        	
		$this->assertEquals($profile['profile_name'], $row['profile_name']);
    }


    // public function tearDownAfterClass() {

    // }
}