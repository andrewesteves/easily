<?php
/**
 * Class EasilyDatabase
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */

namespace Easily;

final class EasilyDatabase
{
	/**
	 * Create EasilyDatabase object
	 * 
	 * @param string type
	 */
	public static function connect($type)
	{
		switch ($type) {
			case 'pdo':
				return EasilyPdo::connection();
				break;
			
			default:
				throw new EasilyException("No database support!");		
				break;
		}
	}
}