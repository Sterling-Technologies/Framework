<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelSessionRequestTest extends PHPUnit_Framework_TestCase
{
    public function testErrors() 
	{
        $errors = eve()->model('session')->request()->errors();
		
		$this->assertEquals('Cannot be empty', $errors['app_id']);
		$this->assertEquals('Cannot be empty', $errors['auth_id']);
    }
	
    public function testProcess() 
	{
		$auth = eve()->registry()->get('test', 'auth');
		$app = eve()->registry()->get('test', 'app');
		$config = eve()->settings('test');
		
		$model = eve()
        	->model('session')
        	->request()
        	->process(array(
				'app_id' => $app['app_id'],
				'auth_id' => $auth['auth_id'],
				'session_permissions' => implode(',', $config['scope'])));

		$this->assertTrue(is_string($model['session_token']));

		eve()->registry()->set('test', 'session', $model->get());
	}
}