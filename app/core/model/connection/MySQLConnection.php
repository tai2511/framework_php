<?php

namespace app\core\model\connection;

use PDO;
use PDOException;

class MySQLConnection implements IDBConnectionStrategy
{
	private $host;
	private $username;
	private $password;
	private $dbName;

	public function __construct($host, $username, $password, $dbName)
	{
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->dbName = $dbName;
	}

	public function connect()
	{
		try {
			$conn = new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->username, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
		} catch (PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}
	}

}