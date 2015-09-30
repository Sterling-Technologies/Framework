<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiActionIndexTest extends PHPUnit_Framework_TestCase
{
    public function testRender()
	{
		$self = $this;
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['REQUEST_URI'] = '/';
		
		$request = eve()->getRequest();
		$response = eve()->getResponse();
		
		$action = Api\Action\Index::i()
			->setRequest($request)
			->setResponse($response);
		
		$results = $action->render();
		
		if($response->isKey('body')) {
			$results = $response->get('body');
		}
		
		$this->assertContains('<!DOCTYPE html>', $results);
	}
}