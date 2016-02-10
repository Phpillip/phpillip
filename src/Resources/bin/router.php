<?php

/*
 * See Symfony Console router:
 * https://github.com/symfony/framework-bundle/blob/master/Resources/config/router_prod.php
 */

// Workaround https://bugs.php.net/64566
if (ini_get('auto_prepend_file') && !in_array(realpath(ini_get('auto_prepend_file')), get_included_files(), true)) {
    require ini_get('auto_prepend_file');
}

if (is_file($_SERVER['DOCUMENT_ROOT'].preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']))) {
    return false;
}

$root   = $_SERVER['DOCUMENT_ROOT'].'/..';
$loader = require $root.'/vendor/autoload.php';
require_once $root.'/src/Kernel.php';

$kernel = new Kernel('dev', true);
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
