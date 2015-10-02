<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class BrowserTest extends Eden\Core\Base
{
	public function getResults($results) {
		$this->results = $results;
		return $this;
	}

	public function setClass($path) {
		$class = $this->getClass($path);
		$this->class = $class::i();
		return $this;
	}

	public function setData($data) {

		if($this->method == 'POST') {
			$_GET = array();
			$_POST = $data;
		} else if($this->method == 'GET') {
			$_POST = array();
			$_GET = $data;
		}

		return $this;
	}

	public function setIsTriggered($triggered) {
		$this->isTriggered = $triggered;
		return $this;
	}
	
	public function setMethod($method) {

		$_SERVER['REQUEST_METHOD'] = $method;

		$this->method = $method;
		return $this;
	}

	public function setQueryString($string) {
		$_SERVER['REQUEST_METHOD'] = $string;
		return $this;
	}

	public function setPath($path) {
		$this->path = $path;
		return $this;
	}

	public function setResults() {
		$this->results = $this->action->render();
		return $this;
	}

	public function setTest($test) {
		$this->test = $test;
		return $this;
	}

	public function setIsValid($bool) {
		$this->isValid = $bool;
		return $this;
	}

	public function setVariables($variables) {
		$this->request->set('variables', $variables);
		return $this;
	}

	public function process() 
	{
		$this->request = eve()->getRequest();
		$this->response = eve()->getResponse();

		$class = $this->getClass($this->path);
		$this->action = $class::i()
			->setRequest($this->request)
			->setResponse($this->response);

		if($this->isTriggered) {
			//listen
			$test = $this->test;
			$triggered = false;
			eve()->on('redirect', function($path, $check) use ($test, &$triggered) {
				if($this->isValid) {
					$check->stop = true;
					$triggered = true;
				} else {
					$triggered = true;
					$check->stop = true;
				}
			});
		}

		$this->setResults();

		if($this->isTriggered) {
			eve()->off('redirect');
		}

		if($this->response->isKey('body')) {
			$this->results = $this->response->get('body');
		}

		$this->data['data'] = $this->results;
		$this->data['triggered'] = $triggered;

		return $this->data;
	}

	public function testValidGet($test, $path, array $data = array(), array $variables = array()) 
	{
		$class = $this->getClass($path);

		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['REQUEST_URI'] = $path;
		$_POST = array();
		$_GET = $data;
		$request = eve()->getRequest();
		$response = eve()->getResponse();
		
		$request->set('variables', $variables);

		$action = $class::i()
			->setRequest($request)
			->setResponse($response);
		
		//listen
		$triggered = false;
		eve()->on('redirect', function($path, $check) use ($test, &$triggered) {
			$check->stop = true;
			$triggered = true;
		});

		$results = $action->render();
		
		eve()->off('redirect');

		if($response->isKey('body')) {
			$results = $response->get('body');
		}

		return $results;
	}
	
	public function testInvalidGet($test, $path, array $data = array()) 
	{
		$class = $this->getClass($path);
	}
	
	public function testInvalidPost($test, $path, array $data = array(), array $variables = array()) 
	{
		$class = $this->getClass($path);

		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['REQUEST_URI'] = $path;
		
		$results = array();
		$_GET = array();
		$_POST = $data;

		$request = eve()->getRequest();
		$response = eve()->getResponse();
		
		$request->set('variables', $variables);

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
	
	public function testValidPost($test, $path, array $data = array(), array $variables = array()) 
	{
		$class = $this->getClass($path);

		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['REQUEST_URI'] = $path;

		$results = array();
		$_GET = array();
		$_POST = $data;
		
		$request = eve()->getRequest();
		$response = eve()->getResponse();
		
		$request->set('variables', $variables);

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