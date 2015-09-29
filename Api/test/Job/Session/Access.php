<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiJobSessionAccessTest extends PHPUnit_Framework_TestCase
{
    public function testRun()
	{
		$thrown = false;
		try {
			eve()
				->job('session-access')
				->run();
		} catch(Exception $e) {
			$this->assertInstanceOf('Eve\\Framework\\Job\\Exception', $e);
			$thrown = true;
		}
		
		$this->assertTrue($thrown);
		
		$results = eve()
			->job('session-access')
			->setData(array(
				'app_token' => eve()->registry()->get('test', 'app', 'app_token'),
				'app_secret' => eve()->registry()->get('test', 'app', 'app_secret'),
				'code' => eve()->registry()->get('test', 'session', 'session_token')
			))
			->run();
			
		$this->assertEquals(
			eve()->registry()->get('test', 'profile', 'profile_name'),
			$results['sesion']['profile_name']);
	}
}
