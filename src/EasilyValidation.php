<?php
/**
 * Class EasilyValidation
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */

namespace Easily;

class EasilyValidation
{
	/**
	 * Data to validate
	 */
	public $data;

	/**
	 * Is valid
	 */
	public $isValid;

	/**
	 * Set data
	 */
	public function validate($data)
	{
		if(!empty($data)) {
			$this->data = $data;
			$this->isValid = true;
		}else{
			$this->isValid = false;
		}
		return $this;
	}

	/**
	 * Mimimum method verify the size of an array or string 
	 * to check if corresponds to the valid informed
	 *
	 * @param string|array data
	 * @param int size
	 */
	public function minimum($size)
	{
		if($this->isValid) {
			if(!empty($this->data)) {
				if(is_string($this->data)) {
					if(strlen($this->data) >= $size) {
						$this->isValid = true;
					}else{
						$this->isValid = false;
					}
				}else{
					if(count($this->data) >= $size) {
						$this->isValid = true;
					}else{
						$this->isValid = false;
					}
				}
			}else{
				$this->isValid = false;
			}
		}

		return $this;
	}

	/**
	 * Maximum
	 */
	public function maximum($size)
	{
		if($this->isValid) {
			if(!empty($this->data)) {
				if(is_string($this->data)) {
					if(strlen($this->data) <= $size) {
						$this->isValid = true;
					}else{
						$this->isValid = false;
					}
				}else{
					if(count($this->data) <= $size) {
						$this->isValid = true;
					}else{
						$this->isValid = false;
					}
				}
			}else{
				$this->isValid = false;
			}
		}

		return $this;
	}

	/**
	 * SizeCheck of string
	 *
	 * @param int size
	 * @param string min|max operator
	 */
	public function sizeCheck($size, $operator)
	{
		$operator = in_array($operator, ['min', 'max']) ? $operator : 'min';
		if($this->isValid) {
			if(is_string($this->data)) {
				if($operator == 'min') {
					if(strlen($this->data) >= $size) {
						$this->isValid = true;
					}else{
						$this->isValid = false;
					}				
				}else{
					if(strlen($this->data) <= $size) {
						$this->isValid = true;
					}else{
						$this->isValid = false;
					}
				}
			}else{
				$this->isValid = false;
			}
		}

		return $this;
	}

	/**
	 * Between
	 *
	 * @param int min
	 * @param int max
	 */
	public function between($min, $max)
	{
		if($this->isValid) {
			if(is_string($this->data)) {
				if(strlen($this->data) > $min && strlen($this->data) < $max) {
					$this->isValid = true;
				}else{
					$this->isValid = false;
				}
			}else{
				$this->isValid = false;
			}
		}

		return $this;
	}

	/**
	 * Files extension
	 *
	 * @param array ext
	 */
	public function extension($ext = [])
	{
		if($this->isValid) {
			$file = explode('.', $this->data);
			$fileExt = end($file);
			if(in_array($fileExt, $ext)) {
				$this->isValid = true;
			}else{
				$this->isValid = false;
			}
		}
		return $this;
	}

	/**
	 * Set PHP
	 */
	public function __call($name, $arguments)
	{
		if(!session_id()) {
			session_start();
		}

		$name = strtolower(str_replace('set', '', $name));
		$_SESSION[$name] = $arguments[0];
	}

}