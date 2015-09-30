<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelAppUpdateTest extends PHPUnit_Framework_TestCase
{
	public function testErrors() 
	{
        $errors = eve()->model('app')->update()->errors();
		$this->assertEquals('Cannot be empty', $errors['app_id']);
    }
	
    public function testProcess() 
	{
		$app = eve()->registry()->get('test', 'app');
        
        $model = eve()
        	->model('app')
        	->update()
        	->process(array(
				'app_id' => $app['app_id'],
				'app_website' => 'http://example.com'));

		$this->assertEquals('http://example.com', $model['app_website']);
    }
}