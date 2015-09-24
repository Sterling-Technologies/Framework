<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelProfileSetTest extends PHPUnit_Framework_TestCase
{
    public function testValidateProfileFields() 
	{
    	$errors = eve()->model('profile')->create()->errors();
		
		$this->assertEquals('Cannot be empty!', $errors['profile_name']);
    }
	
    public function testCreateProfile() 
	{
		$model = eve()
        	->model('profile')
        	->create()
        	->process(array(
				'profile_name' => 'Automated Test',
				'profile_email' => 'test@test.com'));

		$this->assertTrue(is_numeric($model['profile_id']));
		eve()->registry()->set('test', 'set', $model->get());
    }

    public function testUpdateProfile() 
	{
		$set = eve()->registry()->get('test', 'set');

        $model = eve()
        	->model('profile')
        	->update()
        	->process(array(
				'profile_id' => $set['profile_id'],
				'profile_email' => 'test@test.com'));

		$this->assertEquals($set['profile_id'], $model['profile_id']);
    }

    public function testChangeEmail() 
	{
		$set = eve()->registry()->get('test', 'set');
		
		$model = eve()
			->model('profile')
			->set()
			->process(array(
				'profile_id' => $set['profile_id'],
				'profile_email'	=> 'test2@test.com' ));


		$this->assertEquals($set['profile_id'], $model['profile_id']);
		$this->assertEquals('test2@test.com', $model['profile_email']);
    }
}