<?php

use App\Config\ErrorLog;
use App\Config\ResponseHttp;

require './vendor/autoload.php';

//$_SERVER['HTTP_ORIGIN'] existe cuando realizamos la petición desde un dominio válido
if (isset($_SERVER['HTTP_REFERER'])){
  ResponseHttp::headerHttpPro($_SERVER['REQUEST_METHOD'], $_SERVER['HTTP_ORIGIN']);//CORS Producción
} else {
  ResponseHttp::headerHttpDev($_SERVER['REQUEST_METHOD']);//CORS Desarrollo
}

//Activa el registro de errores
ErrorLog::activateErrorLog();

//Verifico si se seteo una ruta y que esta no sea index
if (isset($_GET['route']) && $_GET['route'] != "index.php") {
    
  $params = explode('/',$_GET['route']);
  $list = ['auth','user','product'];
  $file = './src/Routes/' .$params[0]. '.php';

  if (in_array($params[0], $list)) {
    if (is_readable($file)) {
      require $file;
    } else {
      echo json_encode(ResponseHttp::status400());
    }                
  } else {
    $msg = "La ruta '".$params[0]."' no existe.";
    echo json_encode(ResponseHttp::status404($msg));
    error_log($msg);//Escribe el archivo php-error.log
  }

} else {
  echo json_encode(ResponseHttp::status404("Debe indicar la ruta. Consulte la documentación."), JSON_UNESCAPED_UNICODE);
}

// JSON_UNESCAPED_UNICODE: Codifica caracteres Unicode multibyte literalmente (por defecto es escapado como \uXXXX).