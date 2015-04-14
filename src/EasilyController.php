<?php
/**
 * Class EasilyController
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */

namespace Easily;

abstract class EasilyController
{
	/**
	 * var Model
	 */
	protected $model;

	/**
	 * Construct
	 */
	public function __construct()
	{
		$this->model = EasilyUtils::model($this);
	}

	/**
	 * Query the request parameter
	 *
	 * @param string|int|null query
	 */
	public function getParam($query = null)
	{
		return $_GET[$query];
	}

	/**
	 * Post data
	 *
	 * @param string name
	 * @param string value
	 */
	public function postData($name = null, $value = null)
	{
		if(is_null($name)){
			return $_POST;
		}else{
			$_POST[$name] = $value;
			return $_POST;
		}
	}

	/**
	 * Files data
	 */
	public function filesData()
	{
		return $_FILES;
	}

	/**
	 * Load Model
	 *
	 * @param model name
	 */
	public function loadModel($model)
	{
		return EasilyUtils::model($model);
	}

	/**
	 * Get the given request
	 */
	public function request($type)
	{
		$method = strtolower($_SERVER['REQUEST_METHOD']);

		switch ($type) {
			case $method:
				return true;
				break;
			
			default:
				return false;
				break;
		}
	}

	/**
	 * Referer
	 */
	public function referer()
	{
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit;
	}

	/**
	 * Redirect
	 *
	 * @param string location
	 */
	public function redirect($location = null)
	{
		header('Location: ' . APP_URL . $location);
		exit;
	}

	/**
	 * Render
	 */
	public function render($view, $data = [], $admin = false)
	{
		EasilyView::render($view, $data, $admin);
	}

	/**
	 * View vars
	 */
	public function viewVars($vars)
	{
		EasilyView::viewVars($vars);
	}

	/**
	 * View layout vars
	 */
	public function viewlayoutVars($vars)
	{
		EasilyView::viewlayoutVars($vars);
	}

	/**
	 * Flash message method
	 *
	 * @param string message
	 * @param string class
	 */
	public function flashMessage($message = '', $class = '')
	{
		return EasilyView::flashMessage($message, $class);
	}

	/**
	 * Query the request parameter
	 *
	 * @param string|int|null query
	 */
	public function getPage()
	{
		if(isset($_GET['page'])) {
			if($_GET['page'] == 0) {
				return 1;
			}else{
				return (int) $_GET['page'];
			}
		}else{
			return 1;
		}
	}
}