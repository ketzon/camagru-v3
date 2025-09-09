<?php
declare(strict_types=1); //force le typage pas de declaration bizarre
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); //parse l'url sans query string
if (!$path) $path = '/';

//tableau cle=>valeur micro mvc
$routes = [
  '/' => fn() => require __DIR__ . '/../app/views/home.php',
  '/signup' => fn() => require __DIR__ . '/../app/views/signup.php',
  '/login' => fn() => require __DIR__ . '/../app/views/login.php',
];

if (isset($routes[$path])) { $routes[$path](); exit; }
http_response_code(404); echo "Not found";
