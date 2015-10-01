<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class BrowserScaffold extends PHPUnit_Extensions_Selenium2TestCase 
{
    protected function setUp() 
	{
		$test = eve()->settings('test');
		
        $this->setBrowser('phantomjs');
        $this->setBrowserUrl($test['host']);
    }
}