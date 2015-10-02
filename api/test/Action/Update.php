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
		$results = BrowserTest::i()->setPath('/update')
			->setMethod('GET')
			->setIsTriggered(false)
			->process();

		$this->assertContains('Update Account', $results['data']);
	}
	
	public function testInvalid()
	{
		$data = array(
			'profile_name' => 'Test Action Create',
			'profile_email' => 'test321@test.com',
			'auth_password' => '123',
			'confirm'		=> '1234'
		);

		$results = BrowserTest::i()->setPath('/update')
			->setMethod('POST')
			->setData($data)
			->setIsValid(false)
			->setIsTriggered(true)
			->process();

		$this->assertFalse($results['triggered']);
		$this->assertContains('Passwords do not match!', $results['data']);
	}

	public function testValid()
	{
		$data = array(
			'profile_name' 	=> 'Test Action Create',
			'profile_email' => 'test3212@test.com',
			'auth_password' => '123',
			'confirm'		=> '123'
		);

		$results = BrowserTest::i()->setPath('/update')
			->setMethod('POST')
			->setData($data)
			->setIsValid(true)
			->setIsTriggered(true)
			->process();

		$this->assertTrue($results['triggered']);
	}
}
