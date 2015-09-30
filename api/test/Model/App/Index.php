<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelAppIndexTest extends PHPUnit_Framework_TestCase
{
	public function testCreate()
	{
		$class = eve()->model('app')->create();
		$this->assertInstanceOf('Api\\Model\\App\\Create', $class);
	}
	
	public function testDetail()
	{
		$class = eve()->model('app')->detail();
		$this->assertInstanceOf('Api\\Model\\App\\Detail', $class);
	}
	
	public function testRefresh()
	{
		$class = eve()->model('app')->refresh();
		$this->assertInstanceOf('Api\\Model\\App\\Refresh', $class);
	}
	
	public function testRemove()
	{
		$class = eve()->model('app')->remove();
		$this->assertInstanceOf('Api\\Model\\App\\Remove', $class);
	}
	
	public function testRestore()
	{
		$class = eve()->model('app')->restore();
		$this->assertInstanceOf('Api\\Model\\App\\Restore', $class);
	}
	
	public function testSearch()
	{
		$class = eve()->model('app')->search();
		$this->assertInstanceOf('Api\\Model\\App\\Search', $class);
	}
	
	public function testUpdate()
	{
		$class = eve()->model('app')->update();
		$this->assertInstanceOf('Api\\Model\\App\\Update', $class);
	}
	
    public function testLinkProfile() 
	{
		$app = eve()->registry()->get('test', 'app');
		
		//link
		$model = eve()
			->model('app')
			->linkProfile(
				$app['app_id'],
				400);
		
		//test
		$this->assertEquals(
			$app['app_id'],
			$model['app_profile_app']);

		$this->assertEquals(
			400,
			$model['app_profile_profile']);
    }
	
    public function testGetProfileByToken() 
	{	
		$config = eve()->settings('test');
		
		$profile = eve()
			->model('app')
			->getProfileByToken($config['app_token']);

		$this->assertEquals('Admin', $profile['profile_name']);
    }

    public function testPermissions() 
    {	
    	$app = eve()->registry()->get('test', 'app');

		$yes = eve()
			->model('app')
			->permissions(
				$app['app_id'], 
				400);
		
		$this->assertTrue($yes);

		$yes = eve()
			->model('app')
			->permissions($app['app_id'], 222);

		$this->assertFalse($yes);
    }

    public function testUnlinkProfile() 
    {
    	$app = eve()->registry()->get('test', 'app');
    	
		$model = eve()
			->model('app')
			->unlinkProfile(
				$app['app_id'],
				400);
		
		$this->assertEquals(
			$app['app_id'],
			$model['app_profile_app']);

		$this->assertEquals(
			400,
			$model['app_profile_profile']);
    }
}