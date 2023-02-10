<?php

namespace app\core\error_handler;

use Exception;
use PDOException;
use ReflectionException;

class ErrorHandler
{
	public function handle(Exception $exception)
	{
		if ($exception instanceof PDOException) {
			echo "SQL error:";
			throw new PDOException($exception->getMessage());
		} else if ($exception instanceof ReflectionException) {
			throw new ReflectionException($exception->getMessage());
		} else {
			echo sprintf('Uncaught exception %s in file %s on line %s', get_class($exception), $exception->getFile(), $exception->getLine());
			die();
		}
	}
}
