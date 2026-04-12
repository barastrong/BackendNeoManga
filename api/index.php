<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$publicPath = __DIR__ . '/../public';

if ($uri !== '/' && file_exists($publicPath . $uri) && !is_dir($publicPath . $uri)) {
    return false;
}

$_SERVER['DOCUMENT_ROOT'] = $publicPath;

require_once $publicPath . '/index.php';
