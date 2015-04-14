<?php
/**
 * Class EasilyPaginationTest
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */
namespace EasilyTest;

require('../src/EasilyPagination.php');

use Easily;

class EasilyPaginationTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Pagination object
	 */
	protected $pagination;

	/**
	 * Instatiate
	 */
	public function setUp()
	{
		$this->pagination = new Easily\EasilyPagination();
	}

	/**
	 * Is Active
	 */
	public function testIsActive()
	{
		$_GET['page'] = 2;
		$this->assertEquals('active', $this->pagination->activePage(2));
	}

	/**
	 * TearDown
	 */
	public function tearDown()
	{

	}
}