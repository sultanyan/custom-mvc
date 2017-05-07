<?php
	
	spl_autoload_register(function ($class){
		$root = dirname(__DIR__); //get the parent directory
		$file = $root . '/' . str_replace('\\', '/', $class) . '.php';
		if (is_readable($file)) {
			require_once $root . '/' . str_replace('\\', '/', $class) . '.php';
		}
	});


	error_reporting(E_ALL);
	set_error_handler('Core\Error::errorHandler');
	set_exception_handler('Core\Error::exceptionHandler');



	$router = new Core\Router();

	// Add the routes
	$router->add('', ['controller' => 'Home', 'action' => 'index']);
	$router->add('{controller}/{action}');
	$router->add('{controller}/{id:\d+}/{action}');
	$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
	$router->dispatch($_SERVER['QUERY_STRING']);
?>