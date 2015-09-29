<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiJobAppRestoreTest extends PHPUnit_Framework_TestCase
{
    public function testRun()
	{
		$thrown = false;
		try {
			eve()
				->job('app-restore')
				->run();
		} catch(Exception $e) {
			$this->assertInstanceOf('Eve\\Framework\\Job\\Exception', $e);
			$thrown = true;
		}
		
		$this->assertTrue($thrown);
		
		$app = eve()->registry()->get('test', 'app');
		
		$results = eve()
			->job('app-restore')
			->setData(array('app_id' => $app['app_id']))
			->run();
		
		$this->assertTrue(is_int($results['app']['app_id']));
	}
}