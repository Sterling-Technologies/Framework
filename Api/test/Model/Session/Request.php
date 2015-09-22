<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Session;

class Request extends PHPUnit_Framework_TestCase
{
     public function testValidateRequestFields() 
	{
        $errors = control()->model('session')->request()->errors();
		
		$this->assertEquals('Invalid ID!', $errors['app_id']);
		$this->assertEquals('Invalid ID!', $errors['auth_id']);
    }
	
    public function testRequest() 
	{
		$auth = control()->registry()->get('test', 'auth');

		$model = control()
        	->model('profile')
        	->request()
        	->process(array(
				'app_id' => $app['app_id'],
				'auth_id' => $auth['auth_id'],
				'session_permissions' => implode(',', $config['scope']))
        	);


		$this->assertTrue(is_string($model['session_token']));

		control()->registry()->set('test', 'session', $model->get());
	}
}