<?php //-->
/*
 * This file is part of the Core package of the Eden PHP Library.
 * (c) 2012-2013 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/BrowserSetup.php';
require_once __DIR__ . '/BrowserTest.php';

Eve\Framework\Index::i(dirname(__DIR__), 'OL');

//create db helper
$create = include('helper/create-database.php');

//just call it
$create();