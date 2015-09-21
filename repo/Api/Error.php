<?php //-->
/*
 * This file is part of the Eve package.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @package Api
 */
class Error extends Base 
{
    /**
     * Output the error details
     *
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string|int
     * @param string
     * @param array
     * @param int
     * @return void
     */
    public function outputDetails(
        $error,     
        $event, 
        $type,         
        $level, 
        $class,     
        $file, 
        $line,         
        $message, 
        $trace,     
        $offset) 
    {
        $history = array();
        for(; isset($trace[$offset]); $offset++) {
            $row = $trace[$offset];
             
            //lets formulate the method
            $method = $row['function'].'()';
            if(isset($row['class'])) {
                $method = $row['class'].'->'.$method;
            }
             
            $rowLine = isset($row['line']) ? $row['line'] : 'N/A';
            $rowFile = isset($row['file']) ? $row['file'] : 'Virtual Call';
             
            //add to history
            $history[] = sprintf('%s File: %s Line: %s', $method, $rowFile, $rowLine);
        }
        
        $message = sprintf(
            '%s %s: "%s" from %s in %s on line %s', 
            $type,         $level,     $message, 
            $class,     $file,         $line);
        
        header('Content-Type: text/json');
        
        echo json_encode(array(
            'error'     => true,
            'message'    => $message,
            'trace'        => $history), 
            JSON_PRETTY_PRINT);
        
        exit;
    }
    
    /**
     * Output the generic error
     *
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string|int
     * @param string
     * @param array
     * @param int
     * @return void
     */
    public function outputGeneric(
        $error,     
        $event, 
        $type,         
        $level, 
        $class,     
        $file, 
        $line,         
        $message, 
        $trace,     
        $offset) 
    {
        header('Content-Type: text/json');
        
        echo json_encode(array(
            'error'     => true,
            'message'    => 'A server Error occurred'),
            JSON_PRETTY_PRINT);
        
        exit;
    }
}