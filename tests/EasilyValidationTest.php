<?php
/**
 * Class EasilyValidationTest
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */
namespace EasilyTest;

require('../src/EasilyValidation.php');

use Easily;

class EasilyValidationTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Validation object
	 */
	protected $validation;

	/**
	 * Instatiate
	 */
	public function setUp()
	{
		$this->validation = new Easily\EasilyValidation();
	}

	/**
	 * Must return the object it self
	 */
	public function testMustReturnObject()
	{
		$this->assertInternalType('object', $this->validation->validate('Lorem ipsum'));
	}

	/**
	 * Valid Attribute
	 */
	public function testValidAttribute()
	{
		$this->assertTrue($this->validation->validate('Lorem ipsum')->isValid);
	}

	/**
	 * Chained methods
	 */
	public function testChainedMethods()
	{
		$this->assertTrue($this->validation->validate('Lorem ipsum')->minimum(4)->maximum(20)->isValid);
	}

	/**
	 * TearDown
	 */
	public function tearDown()
	{

	}
}