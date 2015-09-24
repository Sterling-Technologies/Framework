<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\App;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Model Factory
 *
 * @vendor Api
 */
class Index extends Base
{
	/**
	 * Factory for create
	 *
	 * @return Api\Model\App\Create
	 */
	public function create()
	{
		return Create::i();
	}
	
	/**
	 * Get profile by app access token
	 * Random function needed...
	 *
	 * @param string
	 * @return array
	 */
	public function getProfileByToken($token) 
	{
		//argument test
		Argument::i()->test(1, 'string');
		
		return eve()
			->database()
			->search('app')
			->setColumns(
				'profile.*', 
				'app.*')
			->innerJoinOn(
				'app_profile', 
				'app_profile_app = app_id')
			->innerJoinOn(
				'profile', 
				'app_profile_profile = profile_id')
			->filterByAppToken($token)
			->getRow();
	}
	
	/**
	 * Factory for detail
	 *
	 * @return Api\Model\App\Detail
	 */
	public function detail()
	{
		return Detail::i();
	}
	
	/**
	 * Link app to Profile
	 *
	 * @param int app id
	 * @param int profile id
	 * @return Eden\Mysql\Model
	 */
	public function linkProfile($appId, $profileId) 
	{
		//argument test
		Argument::i()->test(1, 'int')->test(2, 'int');
		
		$model = eve()
			->database()
			->model()
			->setAppProfileProfile($profileId)
			->setAppProfileApp($appId)
			->insert('app_profile');
		
		$this->trigger('app-link-profile', $model);
		
		return $model;
	}
	
	/**
	 * Factory for search
	 *
	 * @return Api\Model\App\Search
	 */
	public function search()
	{
		return Search::i();
	}
	
	/**
	 * Check for app permissions
	 * 
	 * @param int app id
	 * @param int profile id
	 * @return bool
	 */
	public function permissions($appId, $profileId) 
	{
		//argument test
		Argument::i()->test(1, 'int')->test(2, 'int');
		
		$item = array('app_id' => $appId);
		
		$row = $this->detail()	
			->process($item)
			->innerJoinOn(
				'app_profile', 
				'app_profile_app = app_id')
			->filterByAppProfileProfile($profileId)
			->getRow();
		
		if(!$row) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Factory for refresh
	 *
	 * @return Api\Model\App\Refresh
	 */
	public function refresh()
	{
		return Refresh::i();
	}
	
	/**
	 * Factory for remove
	 *
	 * @return Api\Model\App\Remove
	 */
	public function remove()
	{
		return Remove::i();
	}
	
	/**
	 * Unlink app to Profile
	 *
	 * @param int app id
	 * @param int profile id
	 * @return Eden\Mysql\Model
	 */
	public function unlinkProfile($appId, $profileId) 
	{
		//argument test
		Argument::i()->test(1, 'int')->test(2, 'int');
		
		$model = eve()
			->database()
			->model()
			->setAppProfileProfile($profileId)
			->setAppProfileApp($appId)
			->remove('app_profile');
		
		$this->trigger('app-unlink-profile', $model);
		
		return $model;
	}
	
	/**
	 * Factory for update
	 *
	 * @return Api\Model\App\Update
	 */
	public function update()
	{
		return Update::i();
	}
}
