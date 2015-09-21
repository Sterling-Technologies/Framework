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
		
		// file_id			Required
		if(!$this->isInteger($item['file_id'])) {
			$errors['file_id'] = self::INVALID_ID;
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
		
		$model = control()->database()
			->model()
			->setFileId($item['file_id'])
			->setFileActive('0');
		
		$model->save('file');
		
		$this->trigger('file-remove', $model);
		
		return $model;
	}
}