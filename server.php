<?php

$uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
$uri = urldecode(parse_url($uri, PHP_URL_PATH));

$indexFilePath = __DIR__.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'index.php';

if ($uri !== '/' && file_exists(__DIR__.DIRECTORY_SEPARATOR.'public'.$uri)) {
    return false;
} elseif (file_exists($indexFilePath)) {
    require_once $indexFilePath;
} else {
    // Handle error, file not found
    header("HTTP/1.0 404 Not Found");
    echo "File not found: $indexFilePath";
    exit;
}
