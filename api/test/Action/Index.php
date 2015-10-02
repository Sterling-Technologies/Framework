<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiActionIndexTest extends PHPUnit_Framework_TestCase
{
    public function testRender()
	{
		$results = BrowserTest::i()->setPath('/index')
			->setMethod('GET')
			->setIsTriggered(false)
			->process();

		$this->assertContains('<!DOCTYPE html>', $results['data']);
	}
}