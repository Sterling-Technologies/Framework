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
 * Model Search
 *
 * @vendor Api
 */
class Search extends Base
{
	const INVALID_PARAMETERS = 'Invalid Parameters';
	
	/**
	 * Returns errors if any
	 *
	 * @param array submitted item
	 * @return array error
	 */
	public function errors(array $item = array(), array $errors = array()) 
    {
		return $errors;
	}
	
	/**
	 * Processes the form
	 *
	 * @param object profile object
	 * @return void
	 */
	public function process(array $item = array()) 
	{
		//prevent uncatchable error
		if(count($this->errors($item))) {
			throw new Exception(self::INVALID_PARAMETERS);
		}
		
		$item = $this->prepare($item);
	
		$filter = array();
        $range = 50;
        $start = 0;
        $order = array();
        $count = 0;
        $keyword = null;
        
        if(isset($item['filter']) && is_array($item['filter'])) {
        	$filter = $item['filter'];
        }
        
        if(isset($item['range']) && is_numeric($item['range'])) {
        	$range = $item['range'];
        }
        
        if(isset($item['start']) && is_numeric($item['start'])) {
        	$start = $item['start'];
        }
        
        if(isset($item['order']) && is_array($item['order'])) {
        	$order = $item['order'];
        }
        
        if(isset($item['keyword']) && is_scalar($item['keyword'])) {
        	$keyword = $item['keyword'];
        }
		
		$search = control()->database()
			->search('file')
			->setStart($start)
			->setRange($range);
		
		if(!isset($filter['file_active'])) {
			$filter['file_active'] = 1;
		}
		
		//add filters
		foreach($filter as $column => $value) {
            if(preg_match('^[a-zA-Z0-9-_]+$', $column)) {
                $search->addFilter($column + ' = %s', $value);
            }
		}
		
		//keyword?
		if($keyword) {
			$search->addFilter('(' + implode(' OR ', array(
				'file_link LIKE %s',
				'file_path LIKE %s'
			)) + ')', 
				'%'+keyword+'%', 
				'%'+keyword+'%');
		}
		
		//add sorting
		foreach($order as $sort => $direction) {
			$search->addSort($sort, $direction);
		}
		
		$this->trigger('file-list', $search);
		
		return $search;
	}
}