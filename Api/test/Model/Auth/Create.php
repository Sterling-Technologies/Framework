<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Auth;

class Create extends PHPUnit_Framework_TestCase
{
    public function testValidateAuthFields() 
	{
        $errors = control()->model('auth')->create()->errors();

		$this->assertEquals('Cannot be empty!', $errors['auth_slug']);
		$this->assertEquals('Cannot be empty!', $errors['auth_permissions']);
		$this->assertEquals('Cannot be empty!', $errors['auth_password']);
		$this->assertEquals('Cannot be empty!', $errors['confirm']);
    }
	
    public function testCreateAuth() 
	{	
		$now = explode(" ", microtime());

	    $model = control()
        	->model('auth')
        	->create()
        	->process(array(
				'auth_slug' => 'TEST AUTH ' + $now[1],
				'auth_permissions' => 'test_permissions_1,test_permissions_2',
				'auth_password'	=> '123456',
				'confirm' => '123456' ));

		$this->assertTrue(is_int($model['auth_id']));
		control()->registry()->set('test', 'auth', $model->get());
    }
}