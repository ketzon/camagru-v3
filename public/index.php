<?php
declare(strict_types=1);
session_start([
  'cookie_httponly' => true, //protection contre les attaques xss, pas de cookie en js
  'cookie_secure'   => isset($_SERVER['HTTPS']), //si https, cookie envoyer en https only
  'cookie_samesite' => 'Lax', //pas de cookie auto crossite empeche CSRF (cross site request forgery)
]);

require __DIR__ . '/../app/DB.php';
require __DIR__ . '/../app/controllers/authcontroller.php';
require __DIR__ . '/../app/support.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

$routes = [
    '/' => fn() => require __DIR__ . '/../app/views/home.php',
    '/signup' => function () {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AuthController)->signup();
            return;
        }
        require __DIR__ . '/../app/views/signup.php';
    },
    '/login' => function () {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AuthController)->login();
            return;
        }
        require __DIR__ . '/../app/views/login.php';
    },
    '/logout' => function () {
        session_destroy();
        header("Location: /");
    },
    '/editor' => fn() => require __DIR__ . '/../app/views/editor.php',

    '/void' => fn() => require __DIR__ . '/../app/views/void.php',
];

if (isset($routes[$path])) { $routes[$path](); exit; }
http_response_code(404);
echo "Not found";
