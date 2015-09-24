<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\Address;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Model Factoty
 *
 * @vendor Api
 */
class Index extends Base
{
	/**
	 * Factory for create
	 *
	 * @return Api\Model\Address\Create
	 */
	public function create()
	{
		return Create::i();
	}
	
	/**
	 * Factory for detail
	 *
	 * @return Api\Model\Address\Detail
	 */
	public function detail()
	{
		return Detail::i();
	}
	
	/**
	 * Checks to see if someone has 
	 * permissions to modify the address
	 *
	 * @param int address id
	 * @param int profile id
	 * @return bool
	 */
	public function permissions($addressId, $profileId) 
    {
		//argument test
		Argument::i()->test(1, 'int')->test(2, 'int');
			
		$item = array('address_id' => $addressId);
		
		$row = $this->detail()	
			->process($item)
			->innerJoinOn(
				'profile_address', 
				'profile_address_address = address_id')
			->filterByProfileAddressProfile($profileId)
			->getRow();
        
		
        if(!$row) {
            return false;
        }
        
        return true;
	}
	
	/**
	 * Factory for remove
	 *
	 * @return Api\Model\Address\Remove
	 */
	public function remove()
	{
		return Remove::i();
	}
	
	/**
	 * Factory for search
	 *
	 * @return Api\Model\Address\Search
	 */
	public function search()
	{
		return Search::i();
	}
	
	/**
	 * Factory for update
	 *
	 * @return Api\Model\Address\Update
	 */
	public function update()
	{
		return Update::i();
	}	
}