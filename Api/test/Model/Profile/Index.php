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
		$this->assertInstanceOf('Api\\Model\\Profile\\Create', $class);
	}
	
	public function testDetail()
	{
		$class = eve()->model('profile')->detail();
		$this->assertInstanceOf('Api\\Model\\Profile\\Detail', $class);
	}
	
	public function testSet()
	{
		$class = eve()->model('profile')->set();
		$this->assertInstanceOf('Api\\Model\\Profile\\Set', $class);
	}
	
	public function testRemove()
	{
		$class = eve()->model('profile')->remove();
		$this->assertInstanceOf('Api\\Model\\Profile\\Remove', $class);
	}
	
	public function testRestore()
	{
		$class = eve()->model('profile')->restore();
		$this->assertInstanceOf('Api\\Model\\Profile\\Restore', $class);
	}
	
	public function testSearch()
	{
		$class = eve()->model('profile')->search();
		$this->assertInstanceOf('Api\\Model\\Profile\\Search', $class);
	}
	
	public function testUpdate()
	{
		$class = eve()->model('profile')->update();
		$this->assertInstanceOf('Api\\Model\\Profile\\Update', $class);
	}
}