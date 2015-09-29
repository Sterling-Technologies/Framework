<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelAuthUpdateTest extends PHPUnit_Framework_TestCase
{
	public function testErrors() 
	{
        $errors = eve()->model('auth')->update()->errors();
		$this->assertEquals('Cannot be empty', $errors['auth_id']);
    }
	
    public function testProcess() 
	{
		$auth = eve()->registry()->get('test', 'auth');
		
        $model = eve()->model('auth')->update()->process(array(
			'auth_id' => $auth['auth_id'],
			'auth_facebook_token' => '1234567890'));

		$this->assertEquals('1234567890', $model['auth_facebook_token']);
    }
}