<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiActionAppCreateTest extends PHPUnit_Framework_TestCase
{
    public function testRender()
	{
		$results = BrowserTest::i()->testValidGet($this, '/app/create');
		$this->assertContains('Create App', $results);
	}
	
	public function testInvalid()
	{
		list($triggered, $results) = BrowserTest::i()->testInvalidPost($this, '/app/create', array(
			'app_name' => 'Test Job App Create',
			'app_permissions' => 'public_sso,user_profile,global_profile',
		));
		
		$this->assertFalse($triggered);
		$this->assertContains('Cannot be empty', $results);
	}
	
	public function testValid()
	{
		list($triggered, $results) = BrowserTest::i()->testValidPost($this, '/app/create', array(
			'app_name' => 'Test Job App Create',
			'app_domain' => '*.test.com',
			'app_permissions' => 'public_sso,user_profile,global_profile', 
			'profile_id' => 1
		));

		$this->assertTrue($triggered);
	}
}