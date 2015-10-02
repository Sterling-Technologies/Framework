<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class BrowserTest extends Eden\Core\Base
{

	public function __construct() {
		$this->request = eve()->getRequest();
		$this->response = eve()->getResponse();
	}

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

		$this->request->set('post', $_POST);
		$this->request->set('get', $_GET);
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

	protected function getClass($path)
	{
		$str = array_map('ucfirst', explode('/', $path));
		$path = implode('\\', $str);
		$class = 'Api\Action'.$path;
		$object = new $class();
		return $object;
	}
}