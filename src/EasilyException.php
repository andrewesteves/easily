<?php
/**
 * Class EasilyException
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */

namespace Easily;

class EasilyException extends \Exception
{
	public function __construct($message, $code = 0, \Exception $previous = null)
	{
		$data = $message;
		EasilyView::render('error/index', $data, 'errors');
		parent::__construct($message, $code, $previous);
		exit;
	}
}