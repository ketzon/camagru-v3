<?php
declare(strict_types=1);
session_start([
  'cookie_httponly' => true, //protection contre les attaques xss, pas de cookie en js
  'cookie_secure'   => isset($_SERVER['HTTPS']), //si https, cookie envoyer en https only
  'cookie_samesite' => 'Lax', //pas de cookie auto crossite empeche CSRF (cross site request forgery)
]);

require __DIR__ . '/../app/DB.php';
require __DIR__ . '/../app/controllers/authcontroller.php';
require __DIR__ . '/../app/utils.php';

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
    '/settings' => function () {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            (new AuthController)->settings();
            return;
        }
        require __DIR__ . '/../app/views/settings.php';
    },
    '/logout' => function () {
        session_destroy();
        header("Location: /");
    },
    '/infos' => fn() => require __DIR__ . '/../app/views/infos.php',
    '/editor' => fn() => require __DIR__ . '/../app/views/editor.php',

    '/compose' => function () {
        require_auth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
            http_response_code(405);
            exit;
        }
        require __DIR__.'/../app/controllers/ImageController.php';
        (new ImageController)->compose();
    },
    //need to implement gallery
    '/gallery' => function () { require __DIR__ . '/../app/controllers/GalleryController.php';
        (new GalleryController)->list();
    },
    '/image' => function () {
        require __DIR__ . '/../app/controllers/GalleryController.php';
        (new GalleryController)->show();
    },
    // sert l’image finale depuis storage (secure)
    '/img' => function () {
        require __DIR__ . '/../app/controllers/MediaController.php';
        (new MediaController)->render();
    },
    // actions
    '/like' => function () {
        require_auth();
        if ($_SERVER['REQUEST_METHOD']!=='POST') { 
            http_response_code(405); 
            exit; 
        }
        require __DIR__ . '/../app/controllers/LikeController.php';
        (new LikeController)->like();
    },
    '/comment' => function () {
        require_auth();
        if ($_SERVER['REQUEST_METHOD']!=='POST') { 
            http_response_code(405); 
            exit; 
        }
        require __DIR__ . '/../app/controllers/CommentController.php';
        (new CommentController)->add();
    },
    '/image/delete' => function () {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
            http_response_code(405);
            exit;
        }
        require __DIR__ .  '/../app/controllers/ImageController.php';
        (new ImageController)->delete();
    }
];

if (preg_match('#^/image/(\d+)$#', $path, $m)) { $_GET['id'] = (int)$m[1]; 
    $routes['/image'](); 
    exit; 
}
if (preg_match('#^/img/(\d+)\.png$#', $path, $m)) { 
    $_GET['id'] = (int)$m[1]; 
    $routes['/img'](); 
    exit; 
}

if (isset($routes[$path])) { 
    $routes[$path](); 
    exit; 
}
http_response_code(404);
echo "[404] page not found";
