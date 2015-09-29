<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelProfileDetailTest extends PHPUnit_Framework_TestCase
{
    public function testErrors() 
	{
        $errors = eve()
			->model('profile')
			->detail()
			->errors();
			
		$this->assertEquals('Cannot be empty', $errors['profile_id']);
    }
	
    public function testProcess() 
	{
		$profile = eve()->registry()->get('test', 'profile');
     
        $row = eve()
			->model('profile')
			->detail()
			->process(array('profile_id' => $profile['profile_id']))
			->getRow();
		
		$this->assertEquals('123', $row['profile_name']);
    }
}