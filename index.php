<?php

require_once 'app/bootstrap.php';

$router = app\core\Router::create();

require_once 'router/web.php';

$router->dispatch();