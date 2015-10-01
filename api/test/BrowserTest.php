<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class BrowserTest extends Eden\Core\Base
{
	public function testValidGet($test, $path, array $data = array()) 
	{
		$class = $this->getClass($path);

		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['REQUEST_URI'] = $path;
		
		$request = eve()->getRequest();
		$response = eve()->getResponse();
		
		$action = $class::i()
			->setRequest($request)
			->setResponse($response);
		
		$results = $action->render();
		
		if($response->isKey('body')) {
			$results = $response->get('body');
		}
		
		return $results;
	}
	
	public function testInvalidGet($test, $path, array $data = array()) 
	{
		$class = $this->getClass($path);

	}
	
	public function testInvalidPost($test, $path, array $data = array()) 
	{
		$class = $this->getClass($path);

		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['REQUEST_URI'] = $path;
		
		$results = array();
		$_POST = $data;
		
		$request = eve()->getRequest();
		$response = eve()->getResponse();
		
		$action = $class::i()
			->setRequest($request)
			->setResponse($response);
		
		//listen
		$triggered = false;
		eve()->on('redirect', function($path, $check) use ($test) {
			$triggered = true;
			$check->stop = true;
		});
		
		//trigger
		$results = $action->render();
		
		eve()->off('redirect');
		
		if($response->isKey('body')) {
			$results = $response->get('body');
		}

		return array($triggered, $results);
	}
	
	public function testValidPost($test, $path, array $data = array()) 
	{
		$class = $this->getClass($path);

		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['REQUEST_URI'] = $path;

		$results = array();
		$_POST = $data;
		
		$request = eve()->getRequest();
		$response = eve()->getResponse();
		
		$action = $class::i()
			->setRequest($request)
			->setResponse($response);
		
		//listen
		$triggered = false;
		eve()->on('redirect', function($path, $check) use ($test, &$triggered) {
			$check->stop = true;
			$triggered = true;
		});
		
		
		//trigger
		$action->render();

		eve()->off('redirect');

		return array($triggered, $results);
	}
	
	protected function getClass($path)
	{
		$str = array_map('ucfirst', explode('/', $path));
		$path = implode('\\', $str);
		$class = 'Api\Action'.$path;
		$object = new $class();
		return $object;
	}
}