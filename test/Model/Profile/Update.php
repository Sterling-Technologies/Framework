<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelProfileUpdateTest extends PHPUnit_Framework_TestCase
{
    public function testErrors() 
	{
        $errors = eve()
			->model('profile')
			->remove()
			->errors();
			
		$this->assertEquals('Cannot be empty', $errors['profile_id']);
    }
	
    public function testProcess() 
	{
		$profile = eve()->registry()->get('test', 'profile');

        $model = eve()
        	->model('profile')
        	->update()
        	->process(array(
				'profile_id' => $profile['profile_id'],
				'profile_name' => '0987654321'));

		$this->assertEquals('0987654321', $model['profile_name']);
    }
}