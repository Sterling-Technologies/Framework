<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\App;

class Remove extends PHPUnit_Framework_TestCase
{
    public function testRemoveApp() 
	{
		$app = control()->registry()->get('test', 'app');

        $row = control()
        	->model('app')
        	->remove()
        	->process(array( 
				'app_id' => $app['app_id'] ));

        // TODO
		// $this->assertEquals(null, error);
    }
}