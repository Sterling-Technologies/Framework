<?php //-->
/*
 * This file is part a custom application package.
 * (c) 2011-2012 Openovate Labs
 */

require_once realpath(__DIR__ . '/../vendor').'/autoload.php';

Eve\Framework\Index::i(dirname(__DIR__), 'OL')
//Add any middleware here

//HTPASSWD
//->add(Eden\Middleware\Htpasswd\Plugin::i()->import(array('admin' => 'admin')))

//Rest Route
->add(OL\App\Rest\Route::i()->import())

//Dialog Route
->add(OL\App\Dialog\Route::i()->import())

//Control Route
->add(OL\App\Back\Route::i()->import())

//WWW Route
->add(OL\App\Front\Route::i()->import())

//and this is the default
->defaultBootstrap();