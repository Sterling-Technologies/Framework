<?php 
switch(true) {
	case strpos($_SERVER['REQUEST_URI'], '/api') === 0:
		require_once dirname(__DIR__).'/Api/vendor/autoload.php';
		Eve\Framework\Index::i(dirname(__DIR__).'/api', 'Api', '/api');
		require_once __DIR__.'/api/index.php';
		break;
	case strpos($_SERVER['REQUEST_URI'], '/admin') === 0:
		echo 'TODO';
		break;
}