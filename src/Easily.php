<?php
/**
 * Class Easily
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */

namespace Easily;

class Easily
{
	/**
	 * Uri
	 */
	protected $_uri;

	/**
	 * Routing
	 */
	protected $_routing;

	/**
	 * Verify Action to check authentication
	 */
	protected $_verify;

	/**
	 * Add Route to stack
	 *
	 * @param string uri
	 */
	public function addRoute($uri, $routing = [])
	{
		$uri = str_replace('/', '\/', $uri);
		$uri = str_replace('[string]', '[a-z0-9_-]*', $uri);
		$uri = str_replace('[integer]', '[0-9_-]*', $uri);
		$this->_uri[] = $uri;
		$this->_routing[] = $routing;
	}

	/**
	 * Get uri stack
	 */
	public function getStack()
	{
		return $this->_uri;
	}

	/**
	 * Get routing stack
	 */
	public function getRouting()
	{
		return $this->_routing;
	}

	/**
	 * Full Uri
	 */
	public function getUri()
	{
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	}

	/**
	 * Base URI
	 */
	public function baseUri()
	{
		$route = trim($this->getUri(), '/');
		$route = explode('/', $route);

		$urlBase = str_replace('public', '', dirname($_SERVER['PHP_SELF']));
		$urlBaseRoute = str_replace(trim($urlBase, '/'), '', implode('/', $route));
		$urlBaseRoute = ltrim($urlBaseRoute, '/');

		return $urlBaseRoute;
	}

	/**
	 * Verify method 
	 * Actions to check
	 *
	 * @param array actions
	 */
	public function verify($actions = [])
	{
		$this->_verify = $actions;
	}

	/**
	 * Check action verify if user is allowed to the action
	 *
	 * @param string action
	 */
	public function checkAction($action)
	{
		if(in_array($action, $this->_verify)) {
			EasilyAuthentication::check();
		}
	}

	/**
	 * Init route
	 */
	public function init()
	{
		session_cache_limiter();
		session_start();
		$uriParam = $this->baseUri();
		$match = false;
		$index = null;
		foreach($this->_uri as $key => $value) {
			$value = $value != '' ? '/^'.$value.'$/' : '/^$/';
			if(preg_match($value, $uriParam)) {
				$match = true;
				$index = $key;
				break;
			}
		}

		if($match) {

			if(isset($this->_routing[$index])) {
				$controller = "App\\Controller\\" . ucfirst($this->_routing[$index]['controller']) . 'Controller';
				$action = $this->_routing[$index]['action'];
				
				if(!class_exists($controller)) {
					throw new EasilyException("Controller does not exists");
				}
				
				// verify if user is allowed to the action
				$this->checkAction($action);

				$appController = new $controller;

				if(isset($this->_routing[$index]['pass'])) {
					$passCount = count($this->_routing[$index]['pass']);
					$passArr = explode('/', $this->getUri());
					$passArrCount = count($passArr);
					$passArr = array_slice($passArr, ($passArrCount - $passCount));
					$passed = [];
					$n = 0;
					foreach($this->_routing[$index]['pass'] as $value) {
						$passed[$value] = $passArr[$n];
						$n++;
					}
					$appController->$action($passed);
				}else{
					$appController->$action();
				}
			}

		}else{
			$route = explode('/', $this->baseUri());
			$routeParams = count($route);
			$controllerIndex = "";
			$actionIndex = "";
			$requestParams = "";

			if($route[0] == PREFIX) {
				$controllerIndex = isset($route[1]) ? $route[1] : 'Home';
				$actionIndex = isset($route[2]) ? $route[2] : 'index';
				$requestParams = 3;
			}else{
				$controllerIndex = isset($route[0]) ? $route[0] : 'Home';
				$actionIndex = isset($route[1]) ? $route[1] : 'index';
				$requestParams = 2;
			}

			$controller = "App\\Controller\\" . ucfirst($controllerIndex) . "Controller";
			$action = isset($actionIndex) ? $actionIndex : 'index';

			// verify if user is allowed to the action
			$this->checkAction($action);

			if(!class_exists($controller)) {
				throw new EasilyException("Controller does not exists: " . $controller);
			}

			$appController = new $controller;

			if(!method_exists($appController, $action)) {
				throw new EasilyException("No action found: " . $action);
			}

			if($routeParams == $requestParams) {
				$appController->$action();
			}else{
				$params = array_slice($route, $requestParams);
				$appController->$action($params);
			}
		}
	}
}