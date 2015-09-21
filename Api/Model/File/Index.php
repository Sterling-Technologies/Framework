<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\File;

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
	 * @return Api\Model\File\Create
	 */
	public function create()
	{
		return Create::i();
	}
	
	/**
	 * Factory for detail
	 *
	 * @return Api\Model\File\Detail
	 */
	public function detail()
	{
		return Detail::i();
	}
	
	/**
	 * Factory for search
	 *
	 * @return Api\Model\File\Search
	 */
	public function search()
	{
		return Search::i();
	}
	
	/**
	 * Factory for remove
	 *
	 * @return Api\Model\File\Remove
	 */
	public function remove()
	{
		return Remove::i();
	}
}
