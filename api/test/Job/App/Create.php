<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiJobAppCreateTest extends PHPUnit_Framework_TestCase
{
    public function testRun()
	{
		$thrown = false;
		try {
			eve()
				->job('app-create')
				->run();
		} catch(Exception $e) {
			$this->assertInstanceOf('Eve\\Framework\\Job\\Exception', $e);
			$thrown = true;
		}
		
		$this->assertTrue($thrown);
		
		$results = eve()
			->job('app-create')
			->setData(array(
				'app_name' => 'Test Job App Create',
				'app_domain' => '*.test.com',
				'app_permissions' => 'public_sso,user_profile,global_profile', 
				'profile_id' => 1
			))
			->run();
		
		$this->assertTrue(is_numeric($results['app']['app_id']));
		$this->assertEquals('Test Job App Create', $results['app']['app_name']);
		$this->assertEquals('*.test.com', $results['app']['app_domain']);
		$this->assertEquals('public_sso,user_profile,global_profile', $results['app']['app_permissions']);
		
		eve()->registry()->set('test', 'app', $results['app']);
	}
}