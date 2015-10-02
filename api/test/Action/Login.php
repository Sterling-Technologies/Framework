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
		$results = BrowserTest::i()->setPath('/login')
			->setMethod('GET')
			->setIsTriggered(false)
			->process();

		$this->assertContains('Developer Login', $results['data']);
	}

	public function testInvalid()
	{

		$data = array(
			'profile_email' => 'admin@openovate.com',
		);

		$results = BrowserTest::i()->setPath('/login')
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
			'auth_slug' => 'admin@openovate.com',
			'auth_password' => 'admin'
		);

		$results = BrowserTest::i()->setPath('/login')
			->setMethod('POST')
			->setData($data)
			->setIsValid(true)
			->setIsTriggered(true)
			->process();

		$this->assertTrue($results['triggered']);
	}
}