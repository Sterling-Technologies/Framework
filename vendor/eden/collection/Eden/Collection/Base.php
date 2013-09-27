<?php //-->
/*
 * This file is part of the Collection package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Eden\Collection;

use Eden\Core\Base as CoreBase;

/**
 * The base class for all classes wishing to integrate with Eden.
 * Extending this class will allow your methods to seemlessly be
 * overloaded and overrided as well as provide some basic class
 * loading patterns.
 *
 * @vendor Eden
 * @package Collection
 * @author Christian Blanquera cblanquera@openovate.com
 */
class Base extends CoreBase implements \ArrayAccess, \Iterator, \Serializable, \Countable
{

	const ERROR_NOT_SUB_MODEL = 'Class %s is not a child of Eden\\Model\\Base';

    const FIRST = 'first';
    const LAST = 'last';
    const MODEL = 'Eden\\Model\\Base';

    protected $list = array();
    protected $model = self::MODEL;

	/**
     * The magic behind setN and getN
     *
     * @param *string
     * @param *array
     * @return mixed
     */
    public function __call($name, $args) 
    {
		Argument::i()
			//argument 1 must be a string
            ->test(1, 'string') 
			//argument 2 must be an array
            ->test(2, 'array'); 

        //if the method starts with get
        if(strpos($name, 'get') === 0) {
            //getUserName('-') - get all rows column values
            $value = isset($args[0]) ? $args[0] : null;

            $list = array();
            //for each row
            foreach($this->list as $i => $row) {
                //just add the column they want
                //let the model worry about the rest
                $list[] = $row->$name(isset($args[0]) ? $args[0] : null);
            }

            return $list;

        //if the method starts with set
        } else if (strpos($name, 'set') === 0) {
            //setUserName('Chris', '-') - set all user names to Chris
            $value         = isset($args[0]) ? $args[0] : null;
            $separator     = isset($args[1]) ? $args[1] : null;

            //for each row
            foreach($this->list as $i => $row) {
                //just call the method
                //let the model worry about the rest
                $row->$name($value, $separator);
            }

            return $this;
        }

        $found = false;

        //for an array of models the method might exist
        //we should loop and check for a valid method
        foreach($this->list as $i => $row) {
            //if no method exists
            if(!method_exists($row, $name)) {
                continue;
            }

            $found = true;

            //just call the method
            //let the model worry about the rest
            $row->callArray($name, $args);
        }
		
		

        //if found, it means something happened
        if($found) {
            //so it was successful
            return $this;
        }

        //nothing more, just see what the parent has to say
        return parent::__call($name, $args);
    }

	/**
     * Presets the collection
     *
     * @param array
     * @return void
     */
    public function __construct(array $data = array()) 
    {
        $this->set($data);
    }

	/**
     * Allow a property for each row to be changed in one call
     *
     * @param *string
     * @param *mixed
     * @return Eden\Collection\Collection
     */
    public function __set($name, $value) 
    {
		//argument 1 must be a string
		Argument::i()->test(1, 'string');
			
        //set all rows with this column and value
        foreach($this->list as $i => $row) {
            $row[$name] = $value;
        }

        return $this;
    }

	/**
     * If we output this to string we should see it as json
     *
     * @return string
     */
    public function __toString() 
    {
        return json_encode($this->get());
    }

    /**
     * Adds a row to the collection
     *
     * @param array|Eden\Collection\Model
     * @return Eden\Collection\Block
     */
    public function add($row = array()) 
    {
        //Argument 1 must be an array or Eden\Collection\Model
        Argument::i()->test(1, 'array', $this->model);

        //if it's an array
        if(is_array($row)) {
            //make it a model
            $model = trim(str_replace('\\', '_', $this->model), '_');
            $row = $this->$model($row);
        }

        //add it now
        $this->list[] = $row;

        return $this;
    }

    /**
     * returns size using the Countable interface
     *
     * @return string
     */
    public function count() 
    {
        return count($this->list);
    }

	/**
     * Removes a row and reindexes the collection
     *
	 * @param string|int
     * @return string
     */
    public function cut($index = self::LAST) 
    {
        //Argument 1 must be a string or integer
        Argument::i()->test(1, 'string', 'int');

        //if index is first
        if($index == self::FIRST) {
            //we really mean 0
            $index = 0;
        //if index is last
        } else if($index == self::LAST) {
            //we realy mean the last index number
            $index = count($this->list) -1;
        }

        //if this row is found
        if(isset($this->list[$index])) {
            //unset it
            unset($this->list[$index]);
        }

        //reindex the list
        $this->list = array_values($this->list);

        return $this;
    }

    /** 
     * Loops through returned result sets
     *
     * @param *callable
     * @return Eden\Collection\Block
     */
    public function each($callback) 
    {
        Argument::i()->test(1, 'callable');

        foreach($this->list as $key => $value) {
            call_user_func($callback, $key, $value);
        }

        return $this;
    }

    /**
     * Returns the current item
     * For Iterator interface
     *
     * @return void
     */
    public function current() 
    {
        return current($this->list);
    }

    /**
     * Returns the row array
     *
     * @param bool
     * @return array
     */
    public function get($modified = true) 
    {
        //Argument 1 must be a boolean
        Argument::i()->test(1, 'bool');

        $array = array();
        //for each row
        foreach($this->list as $i => $row) {
            //get the array of that (recursive)
            $array[$i] = $row->get($modified);
        }

        return $array;
    }

    /**
     * Returns th current position
     * For Iterator interface
     *
     * @return void
     */
    public function key() 
    {
        return key($this->list);
    }

    /**
     * Increases the position
     * For Iterator interface
     *
     * @return void
     */
    public function next() 
    {
        next($this->list);
    }

    /**
     * isset using the ArrayAccess interface
     *
     * @param *scalar|null|bool
     * @return bool
     */
    public function offsetExists($offset) 
    {
		//argument 1 must be scalar, null or bool
        Argument::i()->test(1, 'scalar', 'null', 'bool');
		
        return isset($this->list[$offset]);
    }

    /**
     * returns data using the ArrayAccess interface
     *
     * @param *scalar|null|bool
     * @return bool
     */
    public function offsetGet($offset) 
    {
		//argument 1 must be scalar, null or bool
        Argument::i()->test(1, 'scalar', 'null', 'bool');
		
        return isset($this->list[$offset]) ? $this->list[$offset] : null;
    }

    /**
     * Sets data using the ArrayAccess interface
     *
     * @param *scalar|null|bool
     * @param *mixed
     * @return void
     */
    public function offsetSet($offset, $value) 
    {
        Argument::i()
			//argument 1 must be scalar, null or bool
			->test(1, 'scalar', 'null', 'bool') 
			//argument 2 must be an array or Eden\Collection\Model
			->test(2, 'array', $this->model); 

        if(is_array($value)) {
            //make it a model
            $model = $this->model;
            $value = $this->$model($value);
        }

        if (is_null($offset)) {
            $this->list[] = $value;
        } else {
            $this->list[$offset] = $value;
        }
    }

    /**
     * unsets using the ArrayAccess interface
     *
     * @param *scalar|null|bool
     * @return bool
     */
    public function offsetUnset($offset) 
    {
		//argument 1 must be scalar, null or bool
        Argument::i()->test(1, 'scalar', 'null', 'bool');
		
        $this->list = Model::i($this->list)
            ->cut($offset)
            ->get();
    }

    /**
     * Rewinds the position
     * For Iterator interface
     *
     * @return void
     */
    public function rewind() 
    {
        reset($this->list);
    }

    /**
     * returns serialized data using the Serializable interface
     *
     * @return string
     */
    public function serialize() 
    {
        return $this->__toString();
    }

    /**
     * Sets data
     *
	 * @param array
     * @return Eden\Collection\Block
     */
    public function set(array $data = array()) 
    {
        foreach($data as $row) {
            $this->add($row);
        }

        return $this;
    }

    /**
     * Sets default model
     *
     * @param *string
     * @return Eden\Collection\Block
     */
    public function setModel($model) 
    {
        //argument 1 must be a string
		Argument::i()->test(1, 'string');

        if(!is_subclass_of($model, 'Eden\\Collection\\Model')) {
            Exeption::i()
				->setMessage(self::ERROR_NOT_SUB_MODEL)
                ->addVariable($model)
                ->trigger();
        }

        $this->model = $model;

        return $this;
    }

    /**
     * sets data using the Serializable interface
     *
     * @param *string
     * @return Eden\Collection\Block
     */
    public function unserialize($data) 
    {
        //argument 1 must be a string
		Argument::i()->test(1, 'string');
		
        $this->list = json_decode($data, true);
        return $this;
    }

    /**
     * Validates whether if the index is set
     * For Iterator interface
     *
     * @return bool
     */
    public function valid() 
    {
        return isset($this->list[key($this->list)]);
    }

}