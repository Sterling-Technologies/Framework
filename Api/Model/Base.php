<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model;

/**
 * Exception
 *
 * @package Api
 */
class Base extends \Api\Base 
{
	/**
	 * Test if 0 or 1
	 *
	 * @param string|number
	 * @return this
	 */
	public function isBool($string) 
	{
		if(!is_scalar($string) || $string === null) {
			return false;
		}
		
		$string = (string) $string;
		
		return $string == '0' || $string == '1';
	}
	
	/**
	 * Test date
	 *
	 * @param string
	 * @return this
	 */
	public function isDate($string) 
	{
		if(!is_scalar($string) || $string === null) {
			return false;
		}
		
		$string = (string) $string;
		
		return preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}/is', $string);
	}
	
	/**
	 * Test email
	 *
	 * @param string|number
	 * @return this
	 */
	public function isEmail($string)  
	{
		if(!is_scalar($string) || $string === null) {
			return false;
		}
		
		$string = (string) $string;
		
		return preg_match('/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]'
			.'{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/is', $string);
	}
	
	/**
	 * Test if float
	 *
	 * @param string|number
	 * @return this
	 */
	public function isFloat($number)
	{
		if(!is_scalar($number) || $number === null) {
			return false;
		}
		
		$number = (string) $number;
		
		return preg_match('/^[-+]?(\d*)?\.\d+$/', $number);
	}
	
	/**
	 * Test if integer
	 *
	 * @param string|number
	 * @return this
	 */
	public function isInteger($number)
	{
		if(!is_scalar($number) || $number === null) {
			return false;
		}
		
		$number = (string) $number;
		
		return preg_match('/^[-+]?\d+$/', $number);
	}
	
	/**
	 * Test if number
	 *
	 * @param string|number
	 * @return this
	 */
	public function isNumber($number)
	{
		if(!is_scalar($number) || $number === null) {
			return false;
		}
		
		$number = (string) $number;
		
		return preg_match('/^[-+]?(\d*[.])?\d+$/', $number);
	}
	
	/**
	 * Test if 0-9
	 *
	 * @param string|number
	 * @return this
	 */
	public function isSmall($number)
	{
		if(!is_scalar($number) || $number === null) {
			return false;
		}
		
		$number = (float) $number;
		
		return $number >= 0 && $number <= 9;
	}
	
	/**
	 * make everything into a string
	 * remove empty strings
	 *
	 * @param object
	 * @return object
	 */
	public function prepare($item)
	{
		$prepared = array();
		
		foreach($item as $key => $value) {
			//if it's null
			if($value === null) {
				//set it and continue
				$prepared[$key] = null;
				continue;
			}
			
			//if is array
			if(is_array($value)) {
				//recursive
				$prepared[$key] = $this->prepare($value);
				continue;
			}
			
			//if it can be converted
			if(is_scalar($value)) {
				$prepared[$key] = (string) $value;
				continue;
			}
			
			//we tried our best ...
			$prepared[$key] = $value;
		}
		
		return $prepared;
	}
}