<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace OL\Model\Profile;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Profile Model Search
 *
 * GUIDE:
 * -- eve() - The current server controller
 *    use this to access the rest of the framework
 *
 *    -- eve()->database() - Returns the current database
 *
 *    -- eve()->model('noun') - Returns the given model factory
 *
 *    -- eve()->job('noun-action') - Returns a job following noun/action
 *
 *    -- eve()->settings('foo') - Returns a settings data originating
 *    from the settings path. ie. settings/foo.php
 *
 *    -- eve()->registry() - Returns Eden\Registry\Index used globally
 */
class Search extends Base
{
    /**
     * Returns errors if any
     *
     * @param array submitted data
     * @param array existing errors
     * @return array error
     */
    public function errors(array $data = array(), array $errors = array()) 
    {
        return $errors;
    }
    
    /**
     * Processes the form
     *
     * @param array data
     * @return mixed
     */
    public function process(array $data = array()) 
    {
        //prevent uncatchable error
        if(count($this->errors($data))) {
            throw new Exception(self::FAIL_406);
        }
        
        //prepare
        $data = $this->prepare($data);
    
        $filter = array();
        $range = 50;
        $start = 0;
        $order = array();
        $count = 0;
        $keyword = null;
        
        if(isset($data['filter']) && is_array($data['filter'])) {
            $filter = $data['filter'];
        }
        
        if(isset($data['range']) && is_numeric($data['range'])) {
            $range = $data['range'];
        }
        
        if(isset($data['start']) && is_numeric($data['start'])) {
            $start = $data['start'];
        }
        
        if(isset($data['order']) && is_array($data['order'])) {
            $order = $data['order'];
        }
        
        if(isset($data['keyword']) && is_scalar($data['keyword'])) {
            $keyword = $data['keyword'];
        }
            
        $search = eve()
            ->database()
            ->search('profile')
            ->setStart($start)
            ->setRange($range);
        
        if(!isset($filter['profile_active'])) {
            $filter['profile_active'] = 1;
        }
        
        //add filters
        foreach($filter as $column => $value) {
            if(preg_match('/^[a-zA-Z0-9-_]+$/', $column)) {
                $search->addFilter($column . ' = %s', $value);
            }
        }
        
        //keyword?
        if($keyword) {
            $search->addFilter('(' . implode(' OR ', array(
                'profile_name LIKE %s', 
            )) . ')' 
                , '%'.$keyword.'%' 
            );
        }
        
        //add sorting
        foreach($order as $sort => $direction) {
            $search->addSort($sort, $direction);
        }
        
        eve()->trigger('profile-search', $search);
        
        return $search;
    }
}
