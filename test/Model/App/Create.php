<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelAppCreateTest extends PHPUnit_Framework_TestCase
{
    public function testErrors() 
	{
        $errors = eve()->model('app')->create()->errors();
		$this->assertEquals('Cannot be empty', $errors['app_name']);
		$this->assertEquals('Cannot be empty', $errors['app_permissions']);
    }
	
    public function testProcess() 
	{
        $model = eve()->model('app')->create()->process(array(
			'app_name'			=> 'TEST APP',
			'app_permissions'	=> 'test_permissions_1,test_permissions_2',
			'app_domain'		=> '*.test.com' ));
        
		eve()->registry()->set('test', 'app', $model->get());
        
		$this->assertTrue(is_numeric($model['app_id']));
    }
}