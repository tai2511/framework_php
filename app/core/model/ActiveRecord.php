<?php

namespace app\core\model;

use app\core\error_handler\ErrorHandler;
use PDO;
use PDOException;
use ReflectionClass;
use ReflectionProperty;

abstract class ActiveRecord
{
	protected $table;
	protected $primaryKey = 'id';
	protected $columns;
	public function __construct()
	{
		$this->columns = $this->getColumns();
	}

	private function getColumns()
	{
		try {
			$reflection = new ReflectionClass($this);
			$properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
			$publicProperties = array();
			foreach ($properties as $property) {
				array_push($publicProperties, $property->getName());
			}
			return $publicProperties;
		} catch (\ReflectionException $e) {
			$errorHandler = new ErrorHandler();
			$errorHandler->handle($e);
		}
	}

	protected function execute($query, $params = [])
	{
		try {
			$instance = Database::getInstance();
			$stmt = $instance->prepare($query);
			$stmt->execute($params);
			return $stmt;
		} catch (PDOException $e) {
			$errorHandler = new ErrorHandler();
			$errorHandler->handle($e);
			return;
		}
	}

	public function save()
	{
		if ($this->{$this->primaryKey}) {
			return $this->update();
		}
		return $this->insert();
	}

	public function insert()
	{
		$columns = implode(', ', $this->columns);
		$placeholders = implode(', ', array_fill(0, count($this->columns), '?'));
		$query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
		$objVar = get_object_vars($this);
		$publicVar = [];
		foreach ($this->columns as $column) {
			if (array_key_exists($column, $objVar)) {
				$publicVar[$column] = $objVar[$column];
			}
		}
		return $this->execute($query, array_values($publicVar));
	}

	public function update()
	{
		$set = [];
		$values = [];
		foreach ($this->columns as $column) {
			if ($column == $this->primaryKey) {
				continue;
			}
			$set[] = "{$column} = ?";
			$values[] = $this->{$column};
		}
		$set = implode(', ', $set);
		$query = "UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = ?";
		$values[] = $this->{$this->primaryKey};
		return $this->execute($query, $values);
	}

	public function find($id)
	{
		$query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
		$result = $this->execute($query, [$id]);
		return $result ? $result->fetchObject(get_class($this)) : null;
	}

	public function findAll()
	{
		$query = "SELECT * FROM {$this->table}";
		$results = $this->execute($query);
		return $results ? $results->fetchAll(PDO::FETCH_CLASS, get_class($this)) : [];
	}

	public function delete($id = null)
	{
		$id = $id ?: $this->{$this->primaryKey};
		$query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
		return $this->execute($query, [$id]);
	}
}

