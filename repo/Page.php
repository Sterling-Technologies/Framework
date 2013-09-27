<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Page extends Eden\Block\Base 
{
	const TEMPLATE_EXTENSION = 'php';
	
	protected $meta	= array();
	protected $head = array();
	protected $body = array();
	protected $foot = array();
	
	protected $id = NULL;
	protected $title = NULL;
	
	protected $messages = array();
	
	/**
	 * returns variables used for templating
	 *
	 * @return array
	 */
	public function getVariables() 
	{
		return $this->body;
	}
	
	/**
	 * returns location of template file
	 *
	 * @return string
	 */
	public function getTemplate() 
	{
		if(!$this->template) {
			$start = strrpos(get_class($this), '\\');
			
			$this->template = control('type', get_class($this))
				->str_replace('\\', DIRECTORY_SEPARATOR)
				->substr($start)
				->strtolower().'.'
				.static::TEMPLATE_EXTENSION;
		}
		
		return $this->template;
	}
	
	/**
	 * Transform block to string
	 *
	 * @param array
	 * @return string
	 */
	public function render() 
	{
        $messages = array();
        if(isset($_SESSION['messages'])) {
            $messages = $_SESSION['messages'];
            $_SESSION['messages'] = array();
        }
		
		$path = control()->path('template');
		$template = $this->getTemplate();
		
		$helpers = $this->getHelpers();
		
		$head = array_merge($helpers, $this->head);
		$body = array_merge($helpers, $this->getVariables());
		$foot = array_merge($helpers, $this->foot);
		
		$file = $path.'/head.'.static::TEMPLATE_EXTENSION;
		$head = control()->trigger('head')->template($file, $head);
		
		$file = $path.$template;
		$body = control()->trigger('body')->template($file, $body);
		
		$file = $path.'/foot.'.static::TEMPLATE_EXTENSION;
		$foot = control()->trigger('foot')->template($file, $foot);
		
		$page = array_merge($helpers, array(
			'meta' 			=> $this->meta,
			'title'			=> $this->title,
			'class'			=> $this->id,
			'head'			=> $head,
			'messages'		=> $messages,
			'body'			=> $body,
			'foot'			=> $foot));
		
		//page
		$file = $path.'/page.'.static::TEMPLATE_EXTENSION;
		return control()->template($file, $page);
	}
	
	/**
	 * Adds flash messaging
	 *
	 * @param string
	 * @param string
	 * @return Front\Page
	 */
	protected function addMessage($message, $type = 'info') 
	{
		$_SESSION['messages'][] = array(
		'type' 		=> $type,
		'message' 	=> $message);
		
		return $this;
	}
	
	protected function getHelpers() 
	{
		$urlRoot 	= control()->path('url');
		$cdnRoot	= control()->path('cdn');
		$language 	= control()->language();
		
		return array(
			'url' => function() use ($urlRoot) {
				echo $urlRoot;
			},
			
			'cdn' => function() use ($cdnRoot) {
				echo $cdnRoot;
			},
			
			'_' => function($key) use ($language) {
				echo $language[$key];
			});
	}
}