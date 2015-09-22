<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Session;

class Access extends PHPUnit_Framework_TestCase
{
    public function testValidateProfileFields() 
	{
    	$errors = control()->model('session')->create()->errors();
		
		$this->assertEquals('Cannot be empty!', $errors['client_id']);
		$this->assertEquals('Cannot be empty!', $errors['client_sercret']);
		$this->assertEquals('Cannot be empty!', $errors['code']);
    }
	
    public function testAccess() 
	{	
		$config = control()->config('test');
		$file = control()->registry()->get('test', 'file');
		$profile = control()->registry()->get('test', 'profile');
		$session = control()->registry()->get('test', 'session');

        $model = control()
        	->model('session')
        	->access()
        	->process(array(
				'client_id'		=> $config['app_token'],
				'client_secret'	=> $config['app_secret'],
				'code'			=> $session['session_token']);
			
		$this->assertEquals(is_string($model['access_token']);
		$this->assertEquals(is_string($model['access_secret']);
		
		$this->assertEquals(
			$profile['profile_name'],
			$model['profile_name']);

		$this->assertEquals(
			$file['file_link'], 
			$model['profile_image']);
		
		control()->registry()->set('test', 'session', $model->get());
    }
}
