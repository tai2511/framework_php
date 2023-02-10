<?php

namespace app\core\model;

use app\core\model\connection\DBConnection;
use app\core\model\connection\MySQLConnection;

class Database
{
	private static $instance;
	private function __construct() {}
	private function __clone() {}
	public static function getInstance()
	{
		if (self::$instance === null) {
			$con = new DBConnection(new MySQLConnection(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_DBNAME));
			self::$instance = $con->connect();
		}
		return self::$instance;
	}
}
