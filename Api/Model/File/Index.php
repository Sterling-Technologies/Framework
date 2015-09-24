<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\File;

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
	 * @return Eve\Framework\Model\File\Create
	 */
	public function create()
	{
		return Create::i();
	}
	
	/**
	 * Factory for detail
	 *
	 * @return Eve\Framework\Model\File\Detail
	 */
	public function detail()
	{
		return Detail::i();
	}
	
	/**
	 * Factory for search
	 *
	 * @return Eve\Framework\Model\File\Search
	 */
	public function search()
	{
		return Search::i();
	}
	
	/**
	 * Factory for remove
	 *
	 * @return Eve\Framework\Model\File\Remove
	 */
	public function remove()
	{
		return Remove::i();
	}
}
