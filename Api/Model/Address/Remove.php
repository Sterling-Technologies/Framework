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
 * Model Remove
 *
 * @vendor Api
 */
class Remove extends Base
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
		
		if(isset($item['address_id'])
			&&!$this('validation', $item['address_id'])->isType('integer', true)) {
			$errors['address_id'] = self::INVALID_ID;
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
		
		$model = eve()
			->database()
			->model()
			->setAddressId($item['address_id'])
			->setAddressActive('0')
			->save('address');
		
		$this->trigger('address-remove', $model);
		
		return $model;
	}	
}