<?php

namespace app\controller;

use app\core\http\Request;
use app\core\http\Response;
use app\model\User;

class UserController
{
	public function index(Request $request, Response $response)
	{
		$user = new User();
		$user->username = $request->name;
		$user->phone = $request->phone;
		$user->save();
		$response->setContent('Hello World!')
			->setStatusCode(200)
			->setHeader('Content-Type', 'text/plain')
			->send();
	}

	public function show()
	{
		echo "Show user";
	}

}