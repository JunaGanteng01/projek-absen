<?php
// Local static preview with the same extensionless URLs as Vercel.
$root = realpath(__DIR__ . '/../demo');
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$candidate = realpath($root . ($path === '/' ? '/index.html' : $path));
if (!$candidate) $candidate = realpath($root . $path . '.html');
if (!$candidate || !str_starts_with($candidate, $root . DIRECTORY_SEPARATOR) || !is_file($candidate)) {
    http_response_code(404);
    readfile($root . '/404.html');
    return;
}
$types = ['html'=>'text/html; charset=utf-8','css'=>'text/css','js'=>'text/javascript','png'=>'image/png','ico'=>'image/x-icon'];
header('Content-Type: ' . ($types[pathinfo($candidate, PATHINFO_EXTENSION)] ?? 'application/octet-stream'));
readfile($candidate);
