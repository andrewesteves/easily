<?php
/**
 * Class EasilyModel
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */

namespace Easily;

abstract class EasilyModel
{
	/**
	 * var EasilyDatabase connection
	 */
	protected $connection;

	/**
	 * var Table 
	 */
	protected $table;

	/**
	 * Creates a EasilyModel object
	 */
	public function __construct()
	{
		$this->connection = EasilyDatabase::connect(DB_TYPE);
		$this->table = EasilyUtils::table($this);
	}

	/**
	 * FindAll method
	 * 
	 * @param string orderBy
	 * @param string order
	 * @param int limit
	 */
	public function findAll($orderBy = 'id', $order = 'desc', $limit = null)
	{
		$order = strtolower($order);
		$sql  = "SELECT * FROM {$this->table} ORDER BY $orderBy $order ";
		$sql .= $limit ? "LIMIT " . $limit . " " : "";
		try {

			$stmt = $this->connection->prepare($sql);
			$stmt->execute();
			return $stmt->fetchAll();

		} catch (\PDOException $e) {
			throw new EasilyException($e->getMessage());			
		}
	}

	/**
	 * Paginate method
	 * 
	 * @param string orderBy
	 * @param string order
	 * @param int limit
	 * @param int offset
	 */
	public function paginate($orderBy = 'id', $order = 'desc', $limit = 1, $offset = 0)
	{
		$offset = ($offset * $limit) - $limit;

		$sql = "SELECT * FROM {$this->table} ORDER BY $orderBy " . strtoupper($order) . " LIMIT $offset, $limit";

		try {

			$stmt = $this->connection->prepare($sql);
			$stmt->execute();
			return $stmt->fetchAll();

		} catch (\PDOException $e) {
			throw new EasilyException($e->getMessage());			
		}
	}

	/**
	 * Insert method
	 *
	 * @param array data
	 */
	public function insert($data = [])
	{
		$created = $updated = date('Y-m-d H:i:s');

		$sql  = "INSERT INTO {$this->table} SET ";
		foreach($data as $argk => $argv) {
			$sql .= $argk . " = :" . $argk . ", ";
		}
		$sql .= "created = :created, updated = :updated ";

		try {
			
			$stmt = $this->connection->prepare($sql);
			foreach($data as $argk => &$argv) {
				$stmt->bindParam($argk, $argv);
			}
			$stmt->bindParam(':created', $created);
			$stmt->bindParam(':updated', $updated);
			return $stmt->execute();

		} catch (\PDOException $e) {
			throw new EasilyException($e->getMessage());
			
		}
	}

	/**
	 * Update method
	 *
	 * @param int id
	 * @param array data
	 */
	public function update($id = null, $data = [])
	{
		$updated = date('Y-m-d H:i:s');

		$sql  = "UPDATE {$this->table} SET ";
		foreach($data as $argk => $argv) {
			$sql .= $argk . " = :" . $argk . ", ";
		}
		$sql .= " updated = :updated ";
		$sql .= "WHERE id = :id";
		
		try {
			
			$stmt = $this->connection->prepare($sql);
			foreach($data as $argk => &$argv) {
				$stmt->bindParam($argk, $argv);
			}
			$stmt->bindParam(':updated', $updated);
			$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
			return $stmt->execute();

		} catch (\PDOException $e) {
			throw new EasilyException($e->getMessage());
			
		}
	}

	/**
	 * Delete
	 *
	 * @param int id
	 */
	public function delete($id = null)
	{
		$sql = "DELETE FROM {$this->table} WHERE id = :id";
		
		try {
			
			$stmt = $this->connection->prepare($sql);
			$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
			return $stmt->execute();

		} catch (\PDOException $e) {
			throw new EasilyException($e->getMessage());			
		}
	}

	/**
	 * Last inserted id
	 */
	public function lastInsertId()
	{
		return $this->connection->lastInsertId();
	}

	/**
	 * Count records
	 */
	public function countRecords()
	{
		$sql = "SELECT COUNT(*) FROM {$this->table}";
		try {
			
			$stmt = $this->connection->prepare($sql);
			$stmt->execute();
			$count = $stmt->fetch();
			return get_object_vars($count)["COUNT(*)"];

		} catch (\PDOException $e) {
			throw new EasilyException($e->getMessage());
			
		}
	}

	/**
	 * Find By
	 *
	 * @param string method name
	 * @param string parameters
	 */
	public function __call($findBy, $params = null)
	{
		$findBy = strtolower(str_replace('findBy', '', $findBy));
		
		$sql  = "SELECT * FROM {$this->table} ";
		$sql .= "WHERE {$findBy} = :find_by";

		try {
			
			$stmt = $this->connection->prepare($sql);
			$stmt->bindParam(':find_by', $params[0]);
			$stmt->execute();
			return $stmt->fetch();

		} catch (\PDOException $e) {
			throw new EasilyException($e->getMessage());
			
		}
	}

	/**
	 * Find Condition
	 *
	 * @param array $args
	 * @param int $limit
	 */
	public function findCondition($args, $limit = 0, $cond = 'and')
	{
		$sql  = "SELECT * FROM {$this->table} WHERE ";
		$cond = strtoupper($cond);
		$argsSize = count($args);
		$n = 1;
		foreach($args as $argk => $argv) {
			$sql .= $argk . " = :" . $argk;
			if($n < $argsSize)
				$sql .= " {$cond} ";
			$n++;
		}
		if($limit) $sql .= " LIMIT $limit";

		try {
			
			$stmt = $this->connection->prepare($sql);
			foreach($args as $argk => &$argv) {
				$stmt->bindParam($argk, $argv);
			}
			$stmt->execute();
			return $stmt->fetchAll();

		} catch (\PDOException $e) {
			throw new EasilyException($e->getMessage());
			
		}
	}

	/**
	 * Sluggable
	 *
	 * @param string slug
	 */
	public function sluggable($slug)
	{
		$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ"!@#$%&*()_-+={[}]/?;:.,\\\'<>';
		$b = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                              ';
		$slug = utf8_decode($slug);
		$slug = strtr($slug, utf8_decode($a), $b);
		$slug = strip_tags(trim($slug));
		$slug = str_replace(" ","-",$slug);
		return strtolower(utf8_encode($slug));
	}

}