<?php
/**
 * Class EasilyAuthentication
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */

namespace Easily;

class EasilyAuthentication
{
	/**
	 * var array Password_hash options
	 */
	private static $options = [
		'salt' => SALT, 
		'cost' => 11
	];

	/**
	 * var Model
	 */
	private $model;

	/**
	 * Initiate the admin model
	 *
	 * @param string admin
	 */
	public function __construct($model)
	{		
		$this->model = $model;
	}

	/**
	 * Login
	 *
	 * @param array params
	 */
	public function login(Array $params = [], $location = null)
	{

		$adminModel = EasilyUtils::model($this->model);
		$adminCheck = $adminModel->findCondition($params, 1);
		$adminCheck = $adminCheck[0];

		if($adminCheck) {
			ini_set('session.use_only_cookies', true);
			session_start();
			session_regenerate_id(true);
			$_SESSION['easily_id']    = $adminCheck->id;
			$_SESSION['easily_name']  = $adminCheck->name;
			$_SESSION['easily_email'] = $adminCheck->email;
			$_SESSION['easily_role']  = $adminCheck->role;
			$_SESSION['easily_token'] = md5(time());
			$location = $location ? $location : 'admin/users/_index';
			header('Location: ' . APP_URL . $location);
		}else{
			header('Location: ' . APP_URL . 'admin/users/login');
		}
	}

	/**
	 * Logout
	 */
	public static function logout()
	{
		session_start(); session_destroy();
		header('Location: ' . APP_URL . 'admin/users/login');
	}

	/**
	 * Password hash
	 *
	 * @param string password
	 */
	public static function passwordHash($password)
	{
		return password_hash($password, PASSWORD_DEFAULT, self::$options);
	}

	/**
	 * Check authentication
	 */
	public static function check()
	{
		
		if(!session_id()) {
			session_start();	
		}
		if(!isset($_SESSION['easily_name']) && !isset($_SESSION['easily_token'])) {
			header('Location: ' . APP_URL . 'admin/users/login');
		}else{
			return [
				'easily_id'    => $_SESSION['easily_id'],
				'easily_name'  => $_SESSION['easily_name'],
				'easily_email' => $_SESSION['easily_email'],
				'easily_role'  => $_SESSION['easily_role'],
				'easily_token' => $_SESSION['easily_token']
			];
		}
	}

	/**
	 * Is Authorized 
	 * 
	 * @param session user
	 * @param array allow
	 */
	public static function isAuthorized($user, $allow = '*')
	{

		if(!session_id()) {
			session_start();
		}

		if($allow[0] == '*') {
			return true;
		}else if(in_array($user, $allow)) {
			return true;
		}else{
			return false;
		}
	}

	/**
	 * User info
	 *
	 * @param string info
	 */
	public static function userInfo($info = 'email')
	{
		if(!session_id()) {
			session_start();
		}

		$info = 'easily_' . $info;

		if(isset($_SESSION[$info])) {
			return $_SESSION[$info];
		}else{
			return false;
		}
	}
}