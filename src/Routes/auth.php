<?php

use App\Config\ResponseHttp;
use App\Controllers\UserController;

// Obtenemos la ruta
$routes = $_GET['route'];
// Si la ruta no contiene "/" al final, se lo agregamos.
if (substr($routes, -1) != "/"){ $routes = $_GET['route'].'/'; }
// Parametros
$params = explode('/', $routes);

// Instancia del controlador de usuario
$app = new UserController();

// Rutas
if (!empty($params[1]) && !empty($params[2])){
  echo json_encode($app->getLogin("auth/{$params[1]}/{$params[2]}/"), JSON_UNESCAPED_UNICODE);
}else{
  echo json_encode(ResponseHttp::status400('Todos los campos son necesarios'));
}


// Error 404
//echo json_encode(ResponseHttp::status404());