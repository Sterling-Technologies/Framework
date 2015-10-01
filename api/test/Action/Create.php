<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiActionCreateTest extends PHPUnit_Framework_TestCase
{
    public function testRender()
	{
		$results = BrowserTest::i()->testValidGet($this, '/create');
		$this->assertContains('Developer Sign Up', $results);
	}
	
	public function testInvalid()
	{
		list($triggered, $results) = BrowserTest::i()->testInvalidPost($this, '/create', array(
			'profile_name' => 'Test Action Create',
			'profile_email' => 'test321@test.com',
			'auth_password' => '123'
		));
		
		$this->assertFalse($triggered);
		$this->assertContains('Cannot be empty', $results);
	}
	
	public function testValid()
	{
		list($triggered, $results) = BrowserTest::i()->testValidPost($this, '/create', array(
			'profile_name' 	=> 'Test Action Create',
			'profile_email' => 'test321@test.com',
			'auth_password' => '123',
			'confirm'		=> '123'
		));

		$this->assertTrue($triggered);
	}
}