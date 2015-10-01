<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiJobSessionRequestTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
		$callback = include(__DIR__.'/../../helper/create-session.php');
		$settings = $callback(array(
			'profile_name'	=> 'Test Job Session Request',
			'auth_slug'		=> 'test23@test.com',
			'auth_password'	=> '123'
		));
		
		eve()->registry()->set('test', 'app', $settings['app']);
		eve()->registry()->set('test', 'auth', $settings['auth']);
		eve()->registry()->set('test', 'profile', $settings['profile']);
		eve()->registry()->set('test', 'profile', $settings['profile']);
		eve()->registry()->set('test', 'session', $settings['session']);
	}
	
    public function testRun()
	{
		$thrown = false;
		try {
			eve()
				->job('session-request')
				->run();
		} catch(Exception $e) {
			$this->assertInstanceOf('Eve\\Framework\\Job\\Exception', $e);
			$thrown = true;
		}
		
		$this->assertTrue($thrown);
		
		$results = eve()
			->job('session-request')
			->setData(array(
				'app_id' => eve()->registry()->get('test', 'app', 'app_id'),
				'auth_id' => eve()->registry()->get('test', 'auth', 'auth_id'),
				'session_permissions' => 'public_sso,user_profile,global_profile,personal_profile'
			))
			->run();
		
		$this->assertTrue(is_string($results['session']['session_token']));
		
		eve()->registry()->set('test', 'session', $results['session']);
	}
}