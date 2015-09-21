<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\Profile;

use Api\Model\Base;
use Api\Model\Argument;
use Api\Model\Exception;

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
	 * @return Api\Model\Profile\Create
	 */
	public function create()
	{
		return Create::i();
	}
	
	/**
	 * Factory for detail
	 *
	 * @return Api\Model\Profile\Detail
	 */
	public function detail()
	{
		return Detail::i();
	}
	
	/**
	 * Link file to profile
	 *
	 * @param int profile id
	 * @param int file id
	 * @return Eden\Mysql\Model
	 */
	public function linkFile($profileId, $fileId) 
	{
		//argument test
		Argument::i()->test(1, 'int')->test(2, 'int');
		
		$model = control()
			->database()
			->model()
			->setProfileFileProfile($profileId)
			->setProfileFileFile($fileId)
			->insert('profile_file');
		
		$this->trigger('profile-link-file', $model);
		
		return $model;
	}
	
	/**
	 * Factory for search
	 *
	 * @return Api\Model\Profile\Search
	 */
	public function search()
	{
		return Search::i();
	}
	
	/**
	 * Factory for remove
	 *
	 * @return Api\Model\Profile\Remove
	 */
	public function remove()
	{
		return Remove::i();
	}
	
	/**
	 * Factory for set
	 *
	 * @return Api\Model\Profile\Set
	 */
	public function set()
	{
		return Set::i();
	}
	
	/**
	 * Unlink all Files to Profile
	 *
	 * @param int profile id
	 * @param array types
	 * @return Eden\Mysql\Model
	 */
	public function unlinkAllFiles($profileId, array $types = array()) 
	{
		//argument test
		Argument::i()->test(1, 'int');
		
		$search = control()
			->database()
			->search('profile_file')
			->innerJoinOn(
				'file', 
				'profile_file_file = file_id')
			->filterByProfileFileProfile($profileId);
		
		if(!empty($types)) {
			$or = array();
			$where = array();
			
			foreach($types as $type) {
				$where[] = 'file_type = %s';
				$or[] = $type;
			}
			
			array_unshift($or, '(' + implode(' OR ', $where) + ')');
			
			$search->callArray('addFilter', $or);
		}	
		
		$rows = $search->getRows();
		
		for($i = 0; $i < count($rows); $i++) {
			$this->unlinkFile(array(
				'profile_id' => $profileId,
				'file_id' => $rows[$i]['file_id'] ));
		}
		
		return $rows;
	}
	
	/**
	 * Unlink address to profile
	 *
	 * @param int profile id
	 * @param int address id
	 * @return Eden\Mysql\Model
	 */
	public function unlinkFile($profileId, $addressId) 
	{
		//argument test
		Argument::i()->test(1, 'int')->test(2, 'int');
		
		$model = control()
			->database()
			->model()
			->setProfileFileProfile($item['profile_id'])
			->setProfileFileFile($item['file_id'])
			->remove('profile_file');
		
		$this->trigger('profile-unlink-file', $model);
		
		return $model;
	}
	
	/**
	 * Factory for update
	 *
	 * @return Api\Model\Profile\Update
	 */
	public function update()
	{
		return Update::i();
	}
}
