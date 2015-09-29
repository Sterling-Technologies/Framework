<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiJobAuthUpdateTest extends PHPUnit_Framework_TestCase
{
    public function testRun()
	{
		$thrown = false;
		try {
			eve()
				->job('auth-update')
				->run();
		} catch(Exception $e) {
			$this->assertInstanceOf('Eve\\Framework\\Job\\Exception', $e);
			$thrown = true;
		}
		
		$this->assertTrue($thrown);
		
		$auth = eve()->registry()->get('test', 'auth');
		$profile = eve()->registry()->get('test', 'profile');
		
		$results = eve()
			->job('auth-update')
			->setData(array(
				'auth_id'		=> $auth['auth_id'],
				'profile_id'	=> $profile['profile_id'],
				'profile_name'	=> 'Test Job Auth Update'
			))
			->run();
		
		$this->assertTrue(is_int($results['auth']['auth_id']));
		$this->assertTrue(is_int($results['profile']['profile_id']));
		
		$this->assertEquals('Test Job Auth Update', $results['profile']['profile_name']);
	}
}