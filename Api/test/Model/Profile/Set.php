<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Profile;

class Set extends PHPUnit_Framework_TestCase
{
    public function testValidateProfileFields() 
	{
    	$errors = control()->model('profile')->create()->errors();
		
		$this->assertEquals('You need to provide either an email or id.', $errors['profile_id']);
		$this->assertEquals('You need to provide either an email or id.', $errors['profile_email']);
    }
	
    public function testCreateProfile() 
	{
		$model = control()
        	->model('profile')
        	->create()
        	->process(array(
				'profile_name' => 'Automated Test',
				'profile_email' => 'test@test.com'));

		$this->assertTrue(is_int($model['profile_id']));
		control()->registry()->set('test', 'set', $model->get());
    }

    public function testUpdateProfile() 
	{
		$set = control()->registry()->get('test', 'set');

        $model = control()
        	->model('profile')
        	->update()
        	->process(array(
				'profile_name' => 'test@test.com'));

		$this->assertEquals($set['profile-id'], $model['profile_id']);
    }

    public function testChangeEmail() 
	{
		$set = control()->registry()->get('test', 'set');

		$model = control()
			->model('profile')
			->set()
			->process(array(
				'profile_id' => $set['profile_id'],
				'profile_email'	=> 'test2@test.com' ));


		$this->assertEquals($set['profile_id'], $model['profile_id']);
		$this->assertEquals('test2@test.com', $model['profile_email']);
    }
}