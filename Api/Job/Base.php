<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Job;

/**
 * Exception
 *
 * @package Api
 */
abstract class Base extends \Api\Base 
{
	protected $data = null;
	
	/**
	 * Executes the job
	 *
	 * @return void
	 */
	abstract public function run();
	
	/**
	 * Sets data needed for the job
	 *
	 * @param mixed data
	 * @return this
	 */
	public function setData($data) 
	{
		$this->data = $data;
		return $this;
	}
}