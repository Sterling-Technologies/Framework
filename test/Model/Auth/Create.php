<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelAuthCreateTest extends PHPUnit_Framework_TestCase
{
    public function testErrors() 
	{
        $errors = eve()->model('auth')->create()->errors();

		$this->assertEquals('Cannot be empty', $errors['auth_slug']);
		$this->assertEquals('Cannot be empty', $errors['auth_permissions']);
		$this->assertEquals('Cannot be empty', $errors['auth_password']);
		$this->assertEquals('Cannot be empty', $errors['confirm']);
    }
	
    public function testProcess() 
	{	
		$now = explode(" ", microtime());

	    $model = eve()
        	->model('auth')
        	->create()
        	->process(array(
				'auth_slug' => 'TEST AUTH ' + $now[1],
				'auth_permissions' => 'test_permissions_1,test_permissions_2',
				'auth_password'	=> '123456',
				'confirm' => '123456' ));

		$this->assertTrue(is_numeric($model['auth_id']));
		eve()->registry()->set('test', 'auth', $model->get());
    }
}