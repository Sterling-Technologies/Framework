<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class BrowserSetup extends Eden\Core\Base
{
	public function runSelenium()
    {
        if($this->seleniumRunning()) {
            fwrite(STDOUT, 'Selenium server already running'.PHP_EOL);
        } else {
            fwrite(STDOUT, 'Starting Selenium'.PHP_EOL);
			$path = __DIR__ .'/../vendor/bin/selenium';
			$path = str_replace('/', DIRECTORY_SEPARATOR, $path);
			
            shell_exec('java -jar ' . $path);
        }
    }
    
	public function runPhantom()
    {
        if ($this->phantomRunning()) {
            fwrite(STDOUT, 'PhantomJS already running'.PHP_EOL);
        } else {
            fwrite(STDOUT, 'Starting PhantomJS'.PHP_EOL);
            
			$path = __DIR__ .'/../vendor/bin/phantomjs';
			$path = str_replace('/', DIRECTORY_SEPARATOR, $path);
			
			shell_exec($path . ' '
				. '--webdriver=8080 '
				. '--webdriver-selenium-grid-hub='
				. 'http://127.0.0.1:4444');
        }
    }
    
	protected function seleniumRunning()
    {
        return fsockopen('localhost', 4444);
    }
    
	protected function phantomRunning()
    {
        try {
            return fsockopen('localhost', 8080);
        } catch (Exception $e) {}
    }
}