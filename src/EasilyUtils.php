<?php
/**
 * Class EasilyUtils
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */

namespace Easily;

class EasilyUtils
{
	/**
	 * Get Model
	 *
	 * @param Object $model
	 */
	public static function model($model)
	{
		if(is_object($model)) {
			$model = get_class($model);
			$lastSlash = strrpos($model, '\\');
			$model = substr($model, $lastSlash + 1);
			$model = str_replace('Controller', '', $model);
			$model = str_replace('Model', '', $model);
		}
		$model = "App\\Model\\" . ucfirst($model) . "Model";
		return new $model;
	}

	/**
	 * Get Model Table Name
	 *
	 * @param Object $model
	 */
	public static function table($model)
	{
		$model = get_class($model);
		$lastSlash = strrpos($model, '\\');
		$model = substr($model, $lastSlash + 1);
		$model = str_replace('Controller', '', $model);
		$model = str_replace('Model', '', $model);
		return strtolower($model);
	}
}

