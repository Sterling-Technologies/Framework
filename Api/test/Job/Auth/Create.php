<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiJobAuthCreateTest extends PHPUnit_Framework_TestCase
{
    public function testRun()
	{
		$thrown = false;
		try {
			eve()
				->job('auth-create')
				->run();
		} catch(Exception $e) {
			$this->assertInstanceOf('Eve\\Framework\\Job\\Exception', $e);
			$thrown = true;
		}
		
		$this->assertTrue($thrown);
		
		$results = eve()
			->job('auth-create')
			->setData(array(
				'profile_email'		=> 'test456@test.com',
				'profile_name'		=> 'Test Job Auth Create',
				'auth_slug'			=> 'test456@test.com',
				'auth_password'		=> '123',
				'confirm'			=> '123',
				'auth_permissions' 	=> 'user_profile,personal_profile,global_profile'
			))
			->run();
		
		$this->assertTrue(is_int($results['auth']['auth_id']));
		$this->assertTrue(is_int($results['profile']['profile_id']));
		
		$this->assertEquals('Test Job Auth Create', $results['profile']['profile_name']);
		$this->assertEquals('test456@test.com', $results['profile']['profile_email']);
		$this->assertEquals('test456@test.com', $results['auth']['auth_slug']);
		$this->assertEquals(md5('123'), $results['auth']['auth_password']);
		
		eve()->registry()->set('test', 'auth', $results['auth']);
		eve()->registry()->set('test', 'profile', $results['profile']);
	}
}