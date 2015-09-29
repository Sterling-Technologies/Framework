<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelSessionAccessTest extends PHPUnit_Framework_TestCase
{
    public function testErrors() 
	{
    	$errors = eve()->model('session')->access()->errors();
		
		$this->assertEquals('Cannot be empty', $errors['client_id']);
		$this->assertEquals('Cannot be empty', $errors['client_secret']);
		$this->assertEquals('Cannot be empty', $errors['code']);
    }
	
    public function testProcess() 
	{	
		$config = eve()->settings('test');
		$profile = eve()->registry()->get('test', 'profile');
		$session = eve()->registry()->get('test', 'session');

        $model = eve()
        	->model('session')
        	->access()
        	->process(array(
				'client_id'		=> $config['app_token'],
				'client_secret'	=> $config['app_secret'],
				'code'			=> $session['session_token']));
			
		$this->assertTrue(is_string($model['access_token']));
		$this->assertTrue(is_string($model['access_secret']));
		
		$this->assertEquals(
			$profile['profile_name'],
			$model['profile_name']);
		
		eve()->registry()->set('test', 'session', $model);
    }

}
