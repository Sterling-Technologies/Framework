<?php //-->
/*
 * This file is part a custom application package.
 * (c) 2011-2012 Openovate Labs
 */
require __DIR__.'/../../Control.php';

/* Get Application
-------------------------------*/
control()

/* Set Paths
-------------------------------*/
->setApplication('front')

/* Set Paths
-------------------------------*/
->setPaths()

/* Set Debug
-------------------------------*/
->setDebug()

/* Trigger Config Event
-------------------------------*/
->trigger('config')

/* Start Packages
-------------------------------*/
->startPackages()

/* Trigger Init Event
-------------------------------*/
->trigger('init')

/* Set Database
-------------------------------*/
->setDatabases()

/* Set Timezone
-------------------------------*/
->setTimezone('Asia/Manila')

/* Trigger Init Event
-------------------------------*/
->trigger('config')

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
->setResponse('index')

/* Trigger Response Event
-------------------------------*/
->trigger('response')

/* Render Output
-------------------------------*/
->render()

/* Trigger Render Event
-------------------------------*/
->trigger('render')

/* Save Translation
-------------------------------*/
//->saveTranslation()

/* Trigger Shutdown Event
-------------------------------*/
->trigger('shutdown');