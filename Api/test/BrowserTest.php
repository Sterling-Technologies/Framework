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
		$action = $this->getClass($path);
		
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['REQUEST_URI'] = $path;
		
		$request = eve()->getRequest();
		$response = eve()->getResponse();
		
		$action
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
	}
	
	public function testValidPost($test, $path, array $data = array()) 
	{
		$class = $this->getClass($path);
	}
	
	protected function getClass($path)
	{
		
	}
}