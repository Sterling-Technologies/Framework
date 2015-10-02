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
		$results = BrowserTest::i()->setPath('/app/create')
			->setMethod('GET')
			->setIsTriggered(false)
			->process();

		$this->assertContains('Create App', $results['data']);
	}
	
	public function testInvalid()
	{
		$data = array(
			'app_name' => 'Test Job App Create',
			'app_permissions' => 'public_sso,user_profile,global_profile',
		);

		$results = BrowserTest::i()->setPath('/app/create')
			->setMethod('POST')
			->setData($data)
			->setIsValid(false)
			->setIsTriggered(true)
			->process();
		
		$this->assertFalse($results['triggered']);
		$this->assertContains('Cannot be empty', $results['data']);
	}
	
	public function testValid()
	{
		$data = array(
			'app_name' => 'Test Job App Create',
			'app_domain' => '*.test.com',
			'app_permissions' => 'public_sso,user_profile,global_profile', 
			'profile_id' => 1
		);

		$results = BrowserTest::i()->setPath('/app/create')
			->setMethod('POST')
			->setData($data)
			->setIsValid(true)
			->setIsTriggered(true)
			->process();
		
		$this->assertTrue($results['triggered']);
	}
}