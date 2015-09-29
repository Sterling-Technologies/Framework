<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiJobAppRemoveTest extends PHPUnit_Framework_TestCase
{
    public function testRun()
	{
		$thrown = false;
		try {
			eve()
				->job('app-remove')
				->run();
		} catch(Exception $e) {
			$this->assertInstanceOf('Eve\\Framework\\Job\\Exception', $e);
			$thrown = true;
		}
		
		$this->assertTrue($thrown);
		
		$app = eve()->registry()->get('test', 'app');
		
		$results = eve()
			->job('app-remove')
			->setData(array('app_id' => $app['app_id']))
			->run();
		
		$this->assertTrue(is_numeric($results['app']['app_id']));
	}
}