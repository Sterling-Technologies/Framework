<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelAppUpdateTest extends PHPUnit_Framework_TestCase
{
    public function testUpdateApp() 
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