<?php
/**
 * Class EasilyPagination
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */

namespace Easily;

class EasilyPagination
{
	/**
	 * Simple pagination
	 *
	 * @param int totalRecords
	 * @param int perPage
	 */
	public static function simple($totalRecords = null, $perPage = null)
	{
		if(!is_null($totalRecords) && !is_null($perPage)) {
			$total = ceil($totalRecords / $perPage);

			$pagination  = "<ul class='pagination'>";
			$pagination .= self::firstPage();
			for($i = 1; $i <= $total; $i++) {
				$pagination .= "<li class='" . self::activePage($i) . "'>";
					$pagination .= "<a href='?page={$i}'>{$i}</a>";
				$pagination .= "</li>";
			}
			$pagination .= self::lastPage($total);
			$pagination .= "</ul>";
			return $pagination;
		}
	}

	/**
	 * Active page
	 *
	 * @param int i
	 */
	public static function activePage($i)
	{
		if(isset($_GET['page'])) {
			if($_GET['page'] == $i) {
				return 'active';
			}
		}else{
			return '';
		}
	}

	/**
	 * First page
	 */
	public static function firstPage()
	{
		if(isset($_GET['page'])) {
			if($_GET['page'] > 1) {
				return "<li><a href='?page=1'><span>&laquo;</span></a></li>";
			}
		}
	}

	/**
	 * Last page
	 * 
	 * @param int total
	 */
	public static function lastPage($total = null)
	{
		if(!is_null($total)) {
			$page = isset($_GET['page']) ? $_GET['page'] : 1;
			if($page != $total && $page >= 1) {
				return "<li><a href='?page={$total}'><span>&raquo;</span></a></li>";
			}				
		}
	}
}