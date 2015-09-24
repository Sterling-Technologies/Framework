<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelSessionAccessTest extends PHPUnit_Framework_TestCase
{
	

    public function testValidateProfileFields() 
	{
    	$errors = eve()->model('session')->access()->errors();
		
		$this->assertEquals('Cannot be empty!', $errors['client_id']);
		$this->assertEquals('Cannot be empty!', $errors['client_secret']);
		$this->assertEquals('Cannot be empty!', $errors['code']);
    }
	
    public function testAccess() 
	{	
		$config = eve()->settings('test');
		$file = eve()->registry()->get('test', 'file');
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

		$this->assertEquals(
			$file['file_link'], 
			$model['profile_image']);
		
		eve()->registry()->set('test', 'session', $model);
    }

}
