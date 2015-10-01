<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiActionLogoutTest extends PHPUnit_Framework_TestCase
{
    public function testRender()
	{
		$self = $this;
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['REQUEST_URI'] = '/logout';
		
		$request = eve()->getRequest();
		$response = eve()->getResponse();
		
		$action = Api\Action\Logout::i()
			->setRequest($request)
			->setResponse($response);

		//listen
		$triggered = false;
		eve()->on('redirect', function($path, $check) use ($self, &$triggered) {
			$check->stop = true;
			$triggered = true;
			$self->assertTrue($triggered);
		});
	}
}
