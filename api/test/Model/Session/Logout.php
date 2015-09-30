<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelSessionLogoutTest extends PHPUnit_Framework_TestCase
{
    public function testErrors() 
	{
        $errors = eve()->model('session')->logout()->errors();
		
		$this->assertEquals('Cannot be empty', $errors['auth_id']);
    }
	
    public function testProcess() 
	{
		$auth = eve()->registry()->get('test', 'auth');

		$model = eve()
        	->model('session')
        	->logout()
        	->process(array(
				'auth_id' => $auth['auth_id']));

		$this->assertTrue(is_array($model->get()));
	}
}