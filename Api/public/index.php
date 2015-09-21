<?php //-->
/*
 * This file is part a custom application package.
 * (c) 2011-2012 Openovate Labs
 */

require_once __DIR__.'/../vendor/eden/handler/loader.php';

Eden_Handler_Loader::i()
	->addRoot(true)
	->addRoot(__DIR__.'/../..')
	->register();

require_once __DIR__.'/../control.php';

/* Get Application
-------------------------------*/
echo control()

/* Set Paths
-------------------------------*/
->setPaths()

/* Set Debug
-------------------------------*/
->setDebug()

/* Set Database
-------------------------------*/
//->setDatabases()

/* Trigger Config Event
-------------------------------*/
->trigger('config')

/* Set Timezone
-------------------------------*/
->setTimezone('Asia/Manila')

/* Trigger Init Event
-------------------------------*/
->trigger('init')

/* Start Session
-------------------------------*/
->startSession()

/* Trigger Session Event
-------------------------------*/
->trigger('session')

/* Set Request
-------------------------------*/
->setRequest()

/* Trigger Request Event
-------------------------------*/
->trigger('request')

/* Set Response
-------------------------------*/
->setResponse()

/* Trigger Response Event
-------------------------------*/
->trigger('response');