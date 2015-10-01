<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelAppDetailTest extends PHPUnit_Framework_TestCase
{
	public function testErrors() 
	{
        $errors = eve()->model('app')->detail()->errors();
		$this->assertEquals('Cannot be empty', $errors['app_id']);
    }
	
    public function testProcess() 
	{	
		$app = eve()->registry()->get('test', 'app');

        $row = eve()
        	->model('app')
        	->detail()
        	->process(array('app_id' => $app['app_id']))
        	->getRow();
		
		$this->assertEquals('TEST APP', $row['app_name']);
    }
}