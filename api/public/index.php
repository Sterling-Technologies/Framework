<?php //-->
/*
 * This file is part a custom application package.
 * (c) 2011-2012 Openovate Labs
 */

require_once realpath(__DIR__ . '/../vendor').'/autoload.php';

Eve\Framework\Index::i(dirname(__DIR__), 'Api')
//Add any middleware here

//HTPASSWD
//->add(Eden\Middleware\Htpasswd\Plugin::i()->import(array('admin' => 'admin')))

//Rest Validator
->all('/rest', Api\Plugin\Rest::i()->import())

//Dialog Validator
->all('/dialog', Api\Plugin\Dialog::i()->import())

//and this is the default
->defaultBootstrap();