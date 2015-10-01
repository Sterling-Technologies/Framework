<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiActionUpdateTest extends PHPUnit_Framework_TestCase
{
    public function testRender()
	{
		$results = BrowserTest::i()->testValidGet($this, '/update');
		$this->assertContains('Update Account', $results);
	}
	
	public function testInvalid()
	{
		list($triggered, $results) = BrowserTest::i()->testInvalidPost($this, '/update', array(
			'profile_name' => 'Test Action Create',
			'profile_email' => 'test321@test.com',
			'auth_password' => '123',
			'confirm'		=> '1234'
		));
		
		$this->assertFalse($triggered);
		$this->assertContains('Passwords do not match!', $results);
	}
	
	public function testValid()
	{
		list($triggered, $results) = BrowserTest::i()->testValidPost($this, '/update', array(
			'profile_name' 	=> 'Test Action Create',
			'profile_email' => 'test3212@test.com',
			'auth_password' => '123',
			'confirm'		=> '123'
		));

		$this->assertTrue($triggered);
	}
}
