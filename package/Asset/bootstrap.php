<?php //-->
control()

->listen('init', function($control) {
	control('block')
	->setAssetRoot('/assets/vendor/eden/block/Eden/Block/assets');
})

->listen('request', function($control) {
	//if page is already set
	if($control->registry()->get('page')) {
		//do nothing
		return;
	}
	
	//get path
	$path = $control->registry()->get('request', 'string');
	//get application
	$application = $control->registry()->get('application');
	//register routes
	if(strpos($path, '/assets') !== 0) {
		return;
	}
	
	//load up assets
	$path 	= '/'.substr($path, 7);
	
	$root 	= $control->registry()->get('path', 'root');
	$file 	= $control('system')->file($root.$path);
	$ext 	= $file->getExtension();
	
	//do not accept php, phtml
	if(in_array($ext, array('php', 'phtml')) || !$file->isFile()) {
		header("HTTP/1.0 404 Not Found");
		return 'We cannot find your file.';
	}
	
	switch($ext) {
		case 'css':
			$mime = 'text/css';
			break;
		case 'js':
			$mime = 'text/javascript';
			break;
		case 'png':
			$mime = 'image/png';
			break;
		case 'gif':
			$mime = 'image/gif';
			break;
		case 'jpg':
		case 'jpeg':
			$mime = 'image/jpeg';
			break;
		case 'woff':
			$mime = 'application/x-font-woff';
			break;
		case 'eot':
			$mime = 'application/vnd.ms-fontobject';
			break;
		case 'ttf':
		case 'otf':
			$mime = 'application/octet-stream';
			break;
		case 'svg':
			$mime = 'image/svg+xml';
			break;
		default: 
			$mime = $file->getMime();
			break;
	}
	
	header('Content-Type: '.$mime);
	echo $file->getContent();
	exit;
});