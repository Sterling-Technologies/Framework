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
		BrowserTest::i()->testInvalidPost($this, '/create', array(
			'profile_name' => 'Test Action Create',
			'profile_email' => 'test321@test.com',
			'auth_password' => '123'
		));
		
		$self = $this;
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['REQUEST_URI'] = '/create';
		
		$_POST = array(
			'profile_name' => 'Test Action Create',
			'profile_email' => 'test321@test.com',
			'auth_password' => '123'
		);
		
		$request = eve()->getRequest();
		$response = eve()->getResponse();
		
		$action = Api\Action\Create::i()
			->setRequest($request)
			->setResponse($response);
		
		//listen
		$triggered = false;
		eve()->on('redirect', function($path, $check) use ($self) {
			$triggered = true;
			$check->stop = true;
		});
		
		//trigger
		$results = $action->render();
		
		eve()->off('redirect');
		
		if($response->isKey('body')) {
			$results = $response->get('body');
		}
		
		$this->assertFalse($triggered);
		$this->assertContains('Cannot be empty', $results);
	}
	
	public function testValid()
	{
		$self = $this;
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['REQUEST_URI'] = '/create';
		
		$_POST = array(
			'profile_name' 	=> 'Test Action Create',
			'profile_email' => 'test321@test.com',
			'auth_password' => '123',
			'confirm'		=> '123'
		);
		
		$request = eve()->getRequest();
		$response = eve()->getResponse();
		
		$action = Api\Action\Create::i()
			->setRequest($request)
			->setResponse($response);
		
		//listen
		$triggered = false;
		eve()->on('redirect', function($path, $check) use ($self, &$triggered) {
			$check->stop = true;
			$self->assertEquals('/login', $path);
			$triggered = true;
		});
		
		//trigger
		$action->render();
		
		eve()->off('redirect');
		
		$this->assertTrue($triggered);
	}
}