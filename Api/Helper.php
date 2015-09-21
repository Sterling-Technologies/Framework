<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api;

/**
 * Other random methods
 *
 * @vendor Api
 */
class Helper extends Base
{
	/**
	 * Generates an all pupose uid
	 *
	 * @return string
	 */
	public function uid() 
	{
		return md5('control'.time().'-'.self::$uid++);
	}
}