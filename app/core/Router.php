<?php

namespace app\core;

use app\core\http\Request;
use app\core\http\Response;

class Router
{
	private $routes = array();
	private $prefix = '';

	public static function create()
	{
		return new static;
	}

	public function prefix($prefix, $callback)
	{
		$previousPrefix = $this->prefix;
		$this->prefix = $prefix;
		call_user_func($callback, $this);
		$this->prefix = $previousPrefix;
	}

	public function get($uri, $handler)
	{
		$uri = $this->prefix . $uri;
		$this->routes['GET'][$uri] = $handler;
	}

	public function post($uri, $handler)
	{
		$uri = $this->prefix . $uri;
		$this->routes['POST'][$uri] = $handler;
	}

	public function dispatch()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		if (isset($this->routes[$method][$url])) {
			$handler = $this->routes[$method][$url];
			if (is_callable($handler)) {
				echo call_user_func($handler);
			} else {
				[$controllerName, $methodName] = explode('@', $handler);
				$controller_file = PATH_TO_CONTROLLER . $controllerName . ".php";
				$controller = NAMESPACE_TO_CONTROLLER . $controllerName;
				if (file_exists($controller_file)) {
					$request = new Request();
					$response = new Response();
					call_user_func_array([new $controller(), $methodName], [$request, $response]);
				} else {
					die("The controller '{$controller}' does not exist");
				}
			}
			return;
		}

		http_response_code(404);
		echo 'Page not found';
	}
}

