<?php

$router->get('/tai', 'UserController@index');
$router->prefix('/tai', function ($router){
	$router->get('/admin', function (){
		return 'Hello, World from admin!';
	});
	$router->get('/users', 'UserController@index');
	$router->post('/users/id', 'UserController@show');
});