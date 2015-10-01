<?php 
	require_once __DIR__ . '/vendor/autoload.php';
	
	use Eve\Framework\Qdispatch;

	Qdispatch::i('localhost', 5672, 'guest', 'guest')->run();