<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\App;

class Create extends PHPUnit_Framework_TestCase
{
    public function testValidateAppFields() 
	{
        $errors = control()->model('app')->create()->errors();
		
		$this->assertEquals('Cannot be empty!', $errors['app_name']);
		$this->assertEquals('Cannot be empty!', $errors['app_permissions']);
    }
	
    public function testCreateApp() 
	{
        $model = control()->model('app')->create()->process(array(
			'app_name'	=> 'TEST APP',
			'app_permissions'	=> 'test_permissions_1,test_permissions_2' ));

        $this->assertTrue(is_int($model['app_id']));
		control()->registry()->set('test', 'app', $model->get());
    }
}