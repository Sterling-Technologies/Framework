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
 * Model Detail
 *
 * @vendor Api
 */
class Detail extends Base
{
	const INVALID_PARAMETERS = 'Invalid Parameters';
	const INVALID_ID = 'Invalid ID';
	
	/**
	 * Returns errors if any
	 *
	 * @param array submitted item
	 * @return array error
	 */
	public function errors(array $item = array(), array $errors = array()) 
    {
		//prepare
		$item = $this->prepare($item);
		
		if(isset($item['app_id'])
			&& !$this('validation', $item['app_id'])->isType('integer', true)) {
			$errors['app_id'] = self::INVALID_ID;
		}
		
		return $errors;
	}
	
	/**
	 * Processes the form
	 *
	 * @param array item
	 * @return void
	 */
	public function process(array $item = array()) 
	{
		//prevent uncatchable error
		if(count($this->errors($item))) {
			throw new Exception(self::INVALID_PARAMETERS);
		}
		
		//prepare
		$item = $this->prepare($item);
		
		$search = eve()->database()
			->search('app')
			->filterByAppId($item['app_id']);
		
		$this->trigger('app-detail', $search);
		
		return $search;
	}
}