<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelAppRefreshTest extends PHPUnit_Framework_TestCase
{
    public function testErrors() 
	{
        $errors = eve()
			->model('app')
			->refresh()
			->errors();
			
		$this->assertEquals('Cannot be empty', $errors['app_id']);
    }
	
    public function testProcess() 
	{
        $app = eve()->registry()->get('test', 'app');

        $model = eve()
        	->model('app')
        	->refresh()
        	->process(array('app_id' => $app['app_id'] ));
		
		$this->assertEquals($app['app_id'], $model['app_id']);
    }
}