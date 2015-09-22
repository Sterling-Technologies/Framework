<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\App;

class Detail extends PHPUnit_Framework_TestCase
{
    public function testGetApp() 
	{	
		$app = control()->registry()->get('test', 'app');

        $row =control()
        	->model('app')
        	->detail()
        	->process(array('app_id' => $app['app_id']))
        	->getRow();
		
		$this->assertEquals('TEST APP', $row['app_name']);
    }
}