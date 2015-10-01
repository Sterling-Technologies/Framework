<?php //-->
return function($path) {
	$test = eve()->settings('test');
		
	$request = eve('curl')
		->setUrl($test['host'].$path)
		->setConnectTimeout(10)
		->setFollowLocation(true)
		->setTimeout(60)
		->verifyPeer(false)
		->verifyHost(false);
	
	if(isset($test['user'], $test['pass'])
		&& !empty($test['user'])
		&& !empty($test['pass'])
	) {
		$request
			->setHttpAuth(CURLAUTH_BASIC)
			->setUserPwd('admin:admin');
	}
	
	return $request;
};