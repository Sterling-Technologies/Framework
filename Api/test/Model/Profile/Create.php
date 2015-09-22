<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Profile;

class Create extends PHPUnit_Framework_TestCase
{
    public function testValidateFileFields() 
	{
        $errors = control()->model('profile')->create()->errors();
		
		$this->assertEquals('Cannot be empty!', $errors['profile_name']);
    }
	
     public function testCreateProfile() 
	{
        $model = control()
        	->model('profile')
        	->create()
        	->process(array(
				'profile_name' => '123'));

		$this->assertTrue(is_int($mode['profile_id']));
		control()->registry()->set('test', 'profile', $model->get());
    }
}