<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelAppCreateTest extends PHPUnit_Framework_TestCase
{
    public function testValidateAppFields() 
	{
        $errors = eve()->model('app')->create()->errors();
		
		$this->assertEquals('Cannot be empty!', $errors['app_name']);
		$this->assertEquals('Cannot be empty!', $errors['app_permissions']);
    }
	
    public function testCreateApp() 
	{
        $model = eve()->model('app')->create()->process(array(
			'app_name'	=> 'TEST APP',
			'app_permissions'	=> 'test_permissions_1,test_permissions_2' ));
        
		eve()->registry()->set('test', 'app', $model->get());
        $this->assertTrue(is_numeric($model['app_id']));
    }
}