<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\Auth;

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
	 * @return this
	 */
	public function create()
	{
		return Create::i();
	}
	
	/**
	 * Checks to see if slug exists
	 *
	 * @param string slug
	 * @param bool
	 */
	public function exists($slug) 
	{
		//argument test
		Argument::i()->test(1, 'string');
			
		$total = eve()
			->database()
			->search('auth')
			->filterByAuthSlug($slug)
			->getTotal();
			
		return $total > 0;
	}
	
	/**
	 * Link auth to Profile
	 *
	 * @param int auth id
	 * @param int profile id
	 * @return Eden\Mysql\Model
	 */
	public function linkProfile($authId, $profileId) 
	{
		//argument test
		Argument::i()->test(1, 'int')->test(2, 'int');
		
		$model = eve()
			->database()
			->model()
			->setAuthProfileProfile($profileId)
			->setAuthProfileAuth($authId)
			->insert('auth_profile');
		
		$this->trigger('auth-link-profile', $model);
		
		return $model;
	}
	
	/**
	 * Factory for remove
	 *
	 * @return Eve\Framework\Model\Auth\Remove
	 */
	public function remove()
	{
		return Remove::i();
	}
	
	/**
	 * Unlink auth to Profile
	 *
	 * @param int auth id
	 * @param int profile id
	 * @return Eden\Mysql\Model
	 */
	public function unlinkProfile($authId, $profileId) 
	{
		//argument test
		Argument::i()->test(1, 'int')->test(2, 'int');
			
		$model = eve()
			->database()
			->model()
			->setAuthProfileProfile($profileId)
			->setAuthProfileAuth($authId)
			->remove('auth_profile');
		
		$this->trigger('auth-unlink-profile', $model);
		
		return $model;
	}
	
	/**
	 * Factory for update
	 *
	 * @return Eve\Framework\Model\Auth\Update
	 */
	public function update()
	{
		return Update::i();
	}
}
