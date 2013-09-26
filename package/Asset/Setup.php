<?php //-->

namespace Asset;

/**
 * Asset Module
 */
class Setup extends \Setup
{
	const TITLE = 'Asset Package';
	const VERSION = '0.1.0';
	const CATEGORY = 'Core';
	const DESCRIPTION = 'Allows assets to be referred though located behind the DMZ.';
	const REQUIRES = '';
	const AUTHOR = 'Openovate Labs';
	const WEBSITE = 'http://www.openovate.com';
	const DEMO = 'http://www.openovate.com';
	const DOCS = 'http://www.openovate.com';
	
	/**
	 * Procedure to install module
	 *
	 * @return this
	 */
	public function install() 
	{
		return $this;
	}
	
	/**
	 * Procedure to uninstall module
	 *
	 * @return this
	 */
	public function uninstall() 
	{
		return $this;
	}
}