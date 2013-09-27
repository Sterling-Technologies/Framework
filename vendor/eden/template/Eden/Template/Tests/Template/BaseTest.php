<?php //-->
/*
 * This file is part of the Utility package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
 
class Eden_Template_Tests_Template_BaseTest extends \PHPUnit_Framework_TestCase
{
    public function testSet() 
    {
		$class = eden('template')->set('foo', 'bar');
		$this->assertInstanceOf('Eden\\Template\\Base', $class);
    }
	
	public function testParseEngine() 
    {
		//simple row
		$row = array(
			'title' => 'Post 1', 
			'detail' => 'Some Post',
			'comments' => array(
			array('detail' => 'Comment 1'),
			array('detail' => 'Comment 1')));
		
		$template = '<h1>{title/}</h2><p>{detail/}</p>'
		.'{!comments}<span>{#comments}</span>{/!comments}';
		
        $string = eden('template')->set($row)->parseEngine($template);
		
		$this->assertContains('<span>2</span>', $string);
		
		//complex row
		$data = array(
			'rows' => array(
				array(
					'title' => 'Post 1', 
					'detail' => 'Some Post 1',
					'comments' => array(
					array('detail' => 'Comment 1'),
					array('detail' => 'Comment 1'))),
				array(
					'title' => 'Post 2', 
					'detail' => 'Some Post 2',
					'comments' => array()),
				array(
					'title' => 'Post 3', 
					'detail' => 'Some Post 3',
					'comments' => array(
					array('detail' => 'Comment 1')))
		));
		
		$template = '{rows}<h1>{title/}</h1><p>{detail/}</p>'
		.'{!comments}<span>{#comments}</span>{/!comments}{/rows}';
		
        $string = eden('template')->set($data)->parseEngine($template);
		
		$this->assertContains('<h1>Post 1</h1><p>Some Post 1</p><span>2</span>', $string);
		$this->assertContains('<h1>Post 2</h1><p>Some Post 2</p>', $string);
		$this->assertContains('<h1>Post 3</h1><p>Some Post 3</p><span>1</span>', $string);
    }
	
    public function testParseString() 
    {
        $string = eden('template')->set('[SOME]', 'no')->parseString('[SOME]thing');
		$this->assertEquals('nothing', $string);
    }
	
	public function testParsePhp() 
	{
		$string = eden('template')
			->set('test', array('key' => 'something'))
			->parsePhp(__DIR__.'/../assets/template.php');
		
		$this->assertEquals('something', $string);
	}
}