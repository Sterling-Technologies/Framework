<?php //-->
/*
 * A Custom Library
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
			'auth_password'	=> '123456'
		));

		// fixture.app 				= results.app;
		// fixture.auth 			= results.auth;
		// fixture.profile 			= results.profile;
		// fixture.session 			= results.session;
		// fixture.profile_name 	= results.profile_name;
		// fixture.auth_slug 		= results.auth_slug;
		// fixture.auth_password 	= results.auth_password;
		eve()->registry()->set('test', 'app', $settings['app']);
		eve()->registry()->set('test', 'auth', $settings['auth']);
		eve()->registry()->set('test', 'profile', $settings['profile']);
		eve()->registry()->set('test', 'profile', $settings['profile']);
		eve()->registry()->set('test', 'session', $settings['session']);
	}

    public function testErrors() 
	{
        $errors = eve()->model('session')->login()->errors();
		
		$this->assertEquals('Cannot be empty', $errors['auth_slug']);
    }
	
    public function testProcess() 
	{
		$auth = eve()->registry()->get('test', 'auth');
		$profile = eve()->registry()->get('test', 'profile');

		$row = eve()
        	->model('session')
        	->login()
        	->process(array(
				'auth_slug' 	=> $auth['auth_slug'],
				'auth_password' => '123456'));
				
		$this->assertEquals($profile['profile_name'], $row['profile_name']);
    }
}