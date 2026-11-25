
<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Max-Age: 86400');

require_once __DIR__ . '/utils/response.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = trim($uri, '/');
$segments = $uri === '' ? [] : array_map('rawurldecode', explode('/', $uri));

if ($method === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if (isset($segments[0]) && $segments[0] === 'api') {
    array_shift($segments);
}

foreach ($segments as $segment) {
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $segment)) {
        sendError('Invalid URL');
    }
}

$routesPath = __DIR__ . '/routes';
$routeFile = null;
$id = null;

if (count($segments) === 1) {
    $routeFile = $routesPath . '/' . $segments[0] . '/route.php';
}

elseif (count($segments) === 2) {
    $routeFile = $routesPath . '/' . $segments[0] . '/[id]/route.php';
    $id = $segments[1];
}

if ($routeFile && is_file($routeFile)) {
    (static function ($method, $id = null) use ($routeFile) {
        require $routeFile;
    })($method, $id);

    exit;
}

sendError('Route not found', 404);