<?php 
namespace Core;

	class Router{
		/*
		* Associative array of routes (the routing table)
		*/
		protected $routes = [];
		/*
		*	Parameters from the matched route, array
		*/
		protected $params = [];
		/* Add a route to the routing table
		* $route => The route URL
		* $params => Parameters (controller, action...)
		* return void stuff
		*/




		// add routes method
		public function add($route, $params = []){
			// converting the route to a regex. esc forward slashes
			$route = preg_replace('/\//', '\\/', $route);
			// convert variables {controller}
			$route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
			// convert variables with custom regexes {id:\d+}
			$route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
			// add start and end delimeters, and case sensetive flag
			$route = '/^' . $route . '$/i';
			$this->routes[$route] = $params;
		}



     	// Dispatch the route, creating the controller object and running the action method
		public function dispatch($url){
			$url = $this->removeQueryStringVariables($url);
			if ($this->match($url)) {
				$controller = $this->params['controller'];
				$controller = $this->convertToStudlyCaps($controller);
				$controller = $this->getNamespace() . $controller;
				if (class_exists($controller)) {
					$controller_object = new $controller($this->params);
					$action = $this->params['action'];
					$action = $this->convertToCamelCase($action);
					if (is_callable([$controller_object, $action])) {
						$controller_object->$action();
					}else {
						throw new \Exception("Method $action (in controller $controller) not found.");
					}
				}else {
					throw new \Exception("Controller class $controller not found.");
				}
			}else {
				throw new \Exception("No route match.", 404);
			}
		}


		/*
		* Convert the string with hyphens to StudlyCaps 
		* like {mher-margaryan} => {MherMargaryan}
		*/
		protected function convertToStudlyCaps($string){
			return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
		}



		/*
		* Convert the string with hyphens to camelCase
		* like {mher-margaryan} => {mherMargaryan}
		*/
		protected function convertToCamelCase($string){
			return lcfirst($this->convertToStudlyCaps($string));
		}




		// get all the routes from routing table, return an array
		public function getRoutes(){
			return $this->routes;
		}





		/*
		*	Match the route to the routes in the routing table, 
		*	setting the $params property if a route is found.
		*	$url => The Route's url
		*/

		// match method
	public function match($url){
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                // Get named capture group values
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }




 /**
     * Remove the query string variables from the URL (if any). As the full
     * query string is used for the route, any variables at the end will need
     * to be removed before the route is matched to the routing table. For
     * example:
     *
     *   URL                           $_SERVER['QUERY_STRING']  Route
     *   -------------------------------------------------------------------
     *   localhost                     ''                        ''
     *   localhost/?                   ''                        ''
     *   localhost/?page=1             page=1                    ''
     *   localhost/posts?page=1        posts&page=1              posts
     *   localhost/posts/index         posts/index               posts/index
     *   localhost/posts/index?page=1  posts/index&page=1        posts/index
     *
     * A URL of the format localhost/?page (one variable name, no value) won't
     * work however. (NB. The .htaccess file converts the first ? to a & when
     * it's passed through to the $_SERVER variable).
	*/
    public function removeQueryStringVariables($url){
    	if ($url != '') {
    		$parts = explode('&', $url, 2);
    		if (strpos($parts[0], '=') === false) {
    			$url = $parts[0];
    		}else {
    			$url = '';
    		}
    	}
    	return $url;
    }



	// Get the currently matched params
	public function getParams(){
		return $this->params;
	}



	// Get the namespace for controller class. The namespace defined in the route parameters 
	// is added is present
	public function getNamespace(){
		$namespace = 'App\Controllers\\';
		if (array_key_exists('namespace', $this->params)) {
			$namespace .= $this->params['namespace'] . '\\';
		}
		return $namespace;
	}
	}