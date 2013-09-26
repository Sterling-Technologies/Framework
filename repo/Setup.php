<?php //-->
/*
 * This file is part of the Eve package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

/**
 * Abstract Setup Class
 *
 * @package    Eve
 * @category   module
 * @author     Christian Blanquera cblanquera@openovate.com
 */
abstract class Setup extends Eden\Core\Base 
{
	protected $errors = array();
	protected $database = NULL;
	
	/**
	 * Construct - store database
	 *
	 * @return void
	 */
	public function __construct($database = null) 
	{
		$this->database = $database;
	}
	
	/**
	 * Returns errors if any
	 *
	 * @return array
	 */
	public function getErrors() 
	{
		return $this->errors;
	}
	
	/**
	 * Procedure to install module
	 *
	 * @return this
	 */
	abstract public function install();
	
	/**
	 * Procedure to uninstall module
	 *
	 * @return this
	 */
	abstract public function uninstall();
	
	/**
	 * Upgrades module from a version 
	 * to specific version in sequence
	 *
	 * @param string
	 * @param string
	 * @return this
	 */
	public function upgrade($from = '0') 
	{
		//get available upgrades
		$methods = $this->ReflectionClass(get_class($this))->getMethods();
		
		$versions = array();
		foreach($methods as $method) {
			if(strpos($method, 'upgradeTo') !== 0) {
				continue;
			}
			
			$versions[] = str_replace('_', '.', substr($method, 9));
		}
		
		sort($versions);
		
		foreach($versions as $version) {
			if(version_compare($version, $from, '>')) {
				$method = 'upgradeTo'.str_replace('.', '_', $version);
				$this->$method();
			}
		}
		
		return $this;
	}
	
	/* Protected Methods
	-------------------------------*/
	/* Private Methods
	-------------------------------*/
}