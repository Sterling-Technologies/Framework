<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelProfileRestoreTest extends PHPUnit_Framework_TestCase
{
    public function testErrors() 
	{
        $errors = eve()
			->model('profile')
			->restore()
			->errors();
			
		$this->assertEquals('Cannot be empty', $errors['profile_id']);
    }
	
    public function testProcess() 
	{
		$profile = eve()->registry()->get('test', 'profile');

        $row = eve()
        	->model('profile')
        	->restore()
        	->process(array( 
				'profile_id' => $profile['profile_id'] ));

		$this->assertEquals(1, $row['profile_active']);
    }
}