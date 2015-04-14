<?php
/**
 * Class EasilyDatabase
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */
	
namespace Easily;

use \PDO;

class EasilyPdo extends PDO
{

	/**
	 * var Connection instance
	 */
	private static $instance;

	/**
	 * PDO connection
	 */
	public static function connection()
	{
		if(!isset(self::$instance)) {
			try {
				self::$instance = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			} catch (PDOException $e) {
				throw new EasilyException($e->getMessage());
			}
		}
		return self::$instance;
	}
}