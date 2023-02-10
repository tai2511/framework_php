<?php

class CLI
{
	public function start()
	{
		exec('php -S localhost:8000');
	}

	public function create_model()
	{
		echo "This is command to create model.";
	}
}

$cli = new CLI();

if (isset($argv[1]) && method_exists($cli, $argv[1])) {
	$cli->{$argv[1]}();
} else {
	echo "Invalid command.";
}