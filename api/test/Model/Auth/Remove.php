<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelAuthRemoveTest extends PHPUnit_Framework_TestCase
{
    public function testErrors() 
	{
        $errors = eve()
			->model('auth')
			->remove()
			->errors();
			
		$this->assertEquals('Cannot be empty', $errors['auth_id']);
    }
	
    public function testProcess() 
	{
		$auth = eve()->registry()->get('test', 'auth');

        $row = eve()
        	->model('auth')
        	->remove()
        	->process(array( 
				'auth_id' => $auth['auth_id']));

		$this->assertEquals(0, $row['auth_active']);
    }
}