<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelAppRemoveTest extends PHPUnit_Framework_TestCase
{
    public function testErrors() 
	{
        $errors = eve()
			->model('app')
			->remove()
			->errors();
			
		$this->assertEquals('Cannot be empty', $errors['app_id']);
    }
	
    public function testProcess() 
	{
		$app = eve()->registry()->get('test', 'app');

        $row = eve()
        	->model('app')
        	->remove()
        	->process(array( 
				'app_id' => $app['app_id'] ));

		$this->assertEquals(0, $row['app_active']);
    }
}