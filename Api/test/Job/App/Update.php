<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiJobAppUpdateTest extends PHPUnit_Framework_TestCase
{
	public function testRun()
	{
		$thrown = false;
		try {
			eve()
				->job('app-update')
				->run();
		} catch(Exception $e) {
			$this->assertInstanceOf('Eve\\Framework\\Job\\Exception', $e);
			$thrown = true;
		}
		
		$this->assertTrue($thrown);
		
		$app = eve()->registry()->get('test', 'app');
		
		$results = eve()
			->job('app-refresh')
			->setData(array(
				'app_id' => $app['app_id'],
				'app_name' => 'Test Job App Update'
			))
			->run();
		
		$this->assertTrue(is_int($results['app']['app_id']));
		$this->assertEquals('Test Job App Create', $results['app']['app_domain']);
	}
}