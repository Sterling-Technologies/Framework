<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelProfileIndexTest extends PHPUnit_Framework_TestCase
{
	
	public function testCreate()
	{
		$class = eve()->model('profile')->create();
		$this->assertInstanceOf('OL\\Model\\Profile\\Create', $class);
	}
	
	public function testDetail()
	{
		$class = eve()->model('profile')->detail();
		$this->assertInstanceOf('OL\\Model\\Profile\\Detail', $class);
	}
	
	public function testSet()
	{
		$class = eve()->model('profile')->set();
		$this->assertInstanceOf('OL\\Model\\Profile\\Set', $class);
	}
	
	public function testRemove()
	{
		$class = eve()->model('profile')->remove();
		$this->assertInstanceOf('OL\\Model\\Profile\\Remove', $class);
	}
	
	public function testRestore()
	{
		$class = eve()->model('profile')->restore();
		$this->assertInstanceOf('OL\\Model\\Profile\\Restore', $class);
	}
	
	public function testSearch()
	{
		$class = eve()->model('profile')->search();
		$this->assertInstanceOf('OL\\Model\\Profile\\Search', $class);
	}
	
	public function testUpdate()
	{
		$class = eve()->model('profile')->update();
		$this->assertInstanceOf('OL\\Model\\Profile\\Update', $class);
	}
}