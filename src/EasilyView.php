<?php
/**
 * Class EasilyView
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */

namespace Easily;

class EasilyView
{	
	/**
	 * Partial
	 */
	public static $partial;

	/**
	 * Data
	 */
	public static $data;

	/**
	 * Vars
	 */
	public static $vars;

	/**
	 * Vars to view into layouts
	 */
	public static $layoutVars;

	/**
	 * Render view parts
	 */
	public static function render($view, $data = [], $layout = false)
	{
		$f = !$layout ? 'index.php' : $layout . '.php';
		$file = APP_TEMPLATE . $f;

		$layoutVars = self::$layoutVars ? self::$layoutVars : '';
		
		$ev = '\Easily\EasilyView';

		if(file_exists($file)) {
			self::$partial = APP_DIR . 'src' . DS . 'App' . DS . 'View' . DS . $view . '.php';
			self::$data = $data;
			include($file);
		}
	}

	/**
	 * View vars
	 *
	 * @param array vars
	 */
	public static function viewVars($vars)
	{
		self::$vars = $vars;
	}

	/**
	 * View layout vars from layout
	 *
	 * @param array layoutVars
	 */
	public static function viewlayoutVars($layoutVars)
	{
		self::$layoutVars = $layoutVars;
	}

	/**
	 * Show view
	 */
	public static function show()
	{
		$ev = '\Easily\EasilyView';
		$ep = '\Easily\EasilyPagination';
		if(file_exists(self::$partial)) {
			$vars = self::$vars ? self::$vars : '';
			$data = self::$data;
			include(self::$partial);
		}		
	}

	/**
	 * Get JS files
	 */
	public static function js($javascript = [])
	{
		$scripts = "";
		foreach($javascript as $js) {
			$scripts .= "<script src='". APP_URL ."public/js/". $js .".js'></script>\n";
		}
		return $scripts;
	}

	/**
	 * Get CSS files
	 */
	public static function css($stylesheet = [])
	{
		$style = "";
		foreach($stylesheet as $css) {
			$style .= "<link rel='stylesheet' href='". APP_URL ."public/css/". $css .".css'>\n";
		}
		return $style;
	}

	/**
	 * Elements
	 */
	public static function elements($element = null)
	{
		$ev = '\Easily\EasilyView';
		if(!is_null($element)) {
			$element = str_replace('/', DS, $element);
			$file = APP_TEMPLATE . 'elements' . DS . $element . '.php';
			if(file_exists($file)) {
				include($file);
			}
		}
	}

	/**
	 * URL Build
	 */
	public static function buildUrl($url = null)
	{
		return APP_URL . $url;
	}

	/**
	 * Link href
	 */
	public static function link($link, $url, $attrs = null, $confirm = false)
	{
		$url = APP_URL . $url;
		
		$confirm = $confirm ? "onclick='return confirm(\"$confirm\")'" : '';

		return "<a href='" . htmlspecialchars($url) . "' $attrs $confirm>" . $link . "</a>";
	}

	/**
	 * Flash method
	 *
	 * @param string message
	 * @param string class
	 */
	public static function flashMessage($message = '', $class = '')
	{
		if(!session_id()) {
			session_start();
		}

		if($message != '') {
			$_SESSION['easily_flash_message'] = $message;
			$_SESSION['easily_flash_class'] = $class;
		}
	}

	/**
	 * Flash
	 */
	public static function flash()
	{
		if(!session_id()) {
			session_start();
		}

		$output = '';

		if(isset($_SESSION['easily_flash_message'])) {
			
			$output = '<div id="easily-flash" class="' . $_SESSION['easily_flash_class'] . '">' . $_SESSION['easily_flash_message'] . '</div>';
			
			unset($_SESSION['easily_flash_message']);
			unset($_SESSION['easily_flash_class']);
		}

		return $output;
	}

	/**
	 * PostForm
	 *
	 * @param string input
	 * @param string data
	 */
	public static function inputForm($input)
	{
		if(isset($_POST[$input])) {
			return $_POST[$input];
		}elseif(isset($_SESSION[$input])) {
			$session = $_SESSION[$input];
			unset($_SESSION[$input]);
			return $session;
		}
	}
}