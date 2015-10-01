<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiActionLoginTest extends PHPUnit_Framework_TestCase
{
    public function testRender()
	{
		$results = BrowserTest::i()->testValidGet($this, '/login');
		$this->assertContains('Developer Login', $results);
	}

	public function testInvalid()
	{
		list($triggered, $results) = BrowserTest::i()->testInvalidPost($this, '/login', array(
			'profile_email' => 'admin@openovate.com',
		));
		
		$this->assertFalse($triggered);
		$this->assertContains('Cannot be empty', $results);
	}
	
	public function testValid()
	{
		list($triggered, $results) = BrowserTest::i()->testValidPost($this, '/login', array(
			'auth_slug' => 'admin@openovate.com',
			'auth_password' => 'admin'
		));
		
		$this->assertTrue($triggered);
	}
}