<?php //-->
namespace 
{
	use Eden\Core\Loader as Loader;	
	use Handlebars\Handlebars;
	use Handlebars\Loader\FilesystemLoader as HandlebarsLoader;
	
	require_once realpath(__DIR__.'/../vendor/Eden/Core/Loader.php');
	Loader::i()->addRoot(true)->register()->load('Eden\\Core\\Controller');
	
	$path = realpath(__DIR__.'/template');
	
	$vars = array(
		'meta' 		=> array(
					   	'name1' => 'value1',
					   	'name2' => 'value2',
					   	'name3' => 'value3',
					   	'name4' => 'value4'
					   ),
		'links' 	=> array(
					   
					   ),
		'styles' 	=> array(
					   
					   ),
		'scripts' 	=> array(
					   
					   ),
		'title' 	=> 'Test Handlebars',
		'class' 	=> 'test-class',
		'body'		=> 'hi');
	
	$loader = new HandlebarsLoader($path, array('extension' => 'html'));
	
	$engine = new Handlebars(array(
		'loader' => $loader,
		'partials_loader' => $loader
	));
	
	$engine->registerHelper('_', function($string, $options) {
		return $string;
	});
	
	$engine->registerHelper('body2', function($test1, $test2, $test3, $options) {
		echo $test2;
		if($test1) {
			return $options['fn']();
		}
		
		return $options['inverse']();
	});
	
	$engine->addHelper('okay', function($template, $context, $arg) {
		return $arg;
	});
	
	header('Content-Type: text/plain');
	
	echo $engine->render('page', $vars);
}