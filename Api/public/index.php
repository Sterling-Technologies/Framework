<?php //-->
/*
 * This file is part a custom application package.
 * (c) 2011-2012 Openovate Labs
 */

require_once __DIR__ . '/../vendor/autoload.php';


Eve\Framework\Index::i(dirname(__DIR__), 'Api')
//Add any middleware here

//HTPASSWD
->add(Eden\Middleware\Htpasswd\Plugin::i()->import(array('admin' => 'admin')))

//ACCESS_TOKEN / SECRET Validator
->all('/rest', Api\Plugin\Validator::i()->import())

//and this is the default
->defaultBootstrap();