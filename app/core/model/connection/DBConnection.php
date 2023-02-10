<?php

namespace app\core\model\connection;

class DBConnection
{
	private $database;

	public function __construct(IDBConnectionStrategy $database)
	{
		$this->database = $database;
	}

	public function connect() {
		return $this->database->connect();
	}
}
