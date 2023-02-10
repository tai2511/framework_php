<?php

namespace app\core\http;

class Request
{
	protected $cookies;
	protected $data;

	public function __construct()
	{
		$this->cookies = $_COOKIE;
		foreach ($_REQUEST as $key => $data) {
			$this->$key = $data;
		}
	}

	public function __get($property)
	{
		if (array_key_exists($property, $this->data)) {
			return $this->data[$property];
		}
	}

	public function __set($property, $value)
	{
		$this->data[$property] = $value;
	}

	public function cookie($key, $default = null)
	{
		return $this->cookies[$key] ?? $default;
	}

}