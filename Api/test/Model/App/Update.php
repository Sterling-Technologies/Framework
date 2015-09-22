<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\App;

class Update extends PHPUnit_Framework_TestCase
{
    public function testUpdateApp() 
	{
		$app = control()->registry()->get('test', 'app');

        $model = control()
        	->model('app')
        	->update()
        	->process(array(
				'app_id' => $app['app_id'],
				'app_website' => 'http://example.com'));

		$this->assertEquals('http://example.com', $model['app_website']);
    }
}