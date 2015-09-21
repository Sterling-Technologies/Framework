<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\Address;

use Api\Model\Base;
use Api\Model\Argument;
use Api\Model\Exception;

/**
 * Model Create
 *
 * @vendor Api
 */
class Create extends Base
{
	const INVALID_PARAMETERS = 'Invalid Parameters';
	const INVALID_EMPTY = 'Cannot be empty!';
	const INVALID_SET	= 'Cannot be empty, if set';
	const INVALID_FLOAT = 'Should be a valid floating point';
	const INVALID_INTEGER	= 'Should be a valid integer';
	const INVALID_NUMBER = 'Should be a valid number';
	const INVALID_BOOL = 'Should either be 0 or 1';
	const INVALID_SMALL = 'Should be between 0 and 9';
	
	/**
	 * Returns errors if any
	 *
	 * @param array submitted item array
	 * @param array existing errors
	 * @return array
	 */
	public function errors(array $item = array(), array $errors = array()) 
	{	
		//prepare
		$item = $this->prepare($item);
		
		//REQUIRED
		
		// address_street			Required
		if(!isset($item['address_street']) || empty($item['address_street'])) {
			$errors['address_street'] = self::INVALID_EMPTY;
		}
		
		// address_city			Required
		if(!isset($item['address_city']) || empty($item['address_city'])) {
			$errors['address_city'] = self::INVALID_EMPTY;
		}
		
		// address_country		Required
		if(!isset($item['address_country']) || empty($item['address_country'])) {
			$errors['address_country'] = self::INVALID_EMPTY;
		}
		
		// address_postal			Required
		if(!isset($item['address_postal']) || empty($item['address_postal'])) {
			$errors['address_postal'] = self::INVALID_EMPTY;
		}
		
		//OPTIONAL
		
		// address_flag
		if(isset($item['address_flag']) 
		&& !$this->isSmall($item['address_flag'])) {
			$errors['address_flag'] = self::INVALID_SMALL;
		}
		
		// address_public
		if(isset($item['address_public']) 
		&& !$this->isBool($item['address_public'])) {
			$errors['address_public'] = self::INVALID_BOOL;
		}
		
		// address_latitude
		if(isset($item['address_latitude'])
		&& !is_numeric($item['address_latitude'])) {
			$errors['address_latitude'] = self::INVALID_NUMBER;
		}
		
		// address_longitude
		if(isset($item['address_longitude']) 
		&& !is_numeric($item['address_longitude'])) {
			$errors['address_longitude'] = self::INVALID_NUMBER;
		}
		
		return $errors;
	}
	
	/**
	 * Processes the form
	 *
	 * @param array item
	 * @return this
	 */
	public function process(array $item = array()) 
	{
		//prevent uncatchable error
		if(count($this->errors($item))) {
			throw new Exception(self::INVALID_PARAMETERS);
		}
		
		//prepare
		$item = $this->prepare($item);
		
		//generate dates
		$created = date('Y-m-d H:i:s');
		$updated = date('Y-m-d H:i:s');
		
		$label = implode(' ', array(
			$item['address_street'],
			$item['address_city'],
			$item['address_country'],
			$item['address_postal']
		));
		
		//SET WHAT WE KNOW
		$model = control()
			->database()
			->model()
			// address_label
			->setAddressLabel($label)
			
			// address_street			Required
			->setAddressStreet($item['address_street'])
			
			// address_city			Required
			->setAddressCity($item['address_city'])
			
			// address_country		Required
			->setAddressCountry($item['address_country'])
			
			// address_postal			Required
			->setAddressPostal($item['address_postal'])

			// address_created
			->setAddressCreated($created)
			
			// address_updated
			->setAddressUpdated($updated);
		
		// address_flag
		if($this->isSmall($item['address_flag'])) {
			$model->setAddressFlag($item['address_flag']);
		}
		
		// address_type
		if(isset($item['address_type'])) {
			$model->setAddressType($item['address_type']);
		}
		
		// address_public
		if($this->isBool($item['address_public'])) {
			$model->setAddressPublic($item['address_public']);
		}
		
		// address_neighborhood		
		if(isset($item['address_neighborhood'])) {
			$model->setAddressNeighborhood($item['address_neighborhood']);
		}
		
		// address_state			
		if(isset($item['address_state'])) {
			$model->setAddressState($item['address_state']);
		}
		
		// address_region		
		if(isset($item['address_region'])) {
			$model->setAddressRegion($item['address_region']);
		}
		
		// address_latitude
		if(is_numeric($item['address_latitude'])) {
			$model->setAddressLatitude($item['address_latitude']);
		}
		
		// address_longitude
		if(is_numeric($item['address_longitude'])) {
			$model->setAddressLongitude($item['address_longitude']);
		}
		
		// address_landmarks
		if(isset($item['address_landmarks'])) {
			$model->setAddressLandmarks($item['address_landmarks']);
		}
		
		//what's left ?
		$model->save('address');
		
		$this->trigger('address-create', $model);
		
		return $model;
	}
}