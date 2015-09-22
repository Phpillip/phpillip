<?php

$root = $_SERVER['DOCUMENT_ROOT'].'/../../..';

require_once $root . '/vendor/autoload.php';
require_once $root . '/src/Application.php';

use Symfony\Component\Debug\Debug;

Debug::enable();

$filename = $_SERVER['DOCUMENT_ROOT'] . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);

if (is_file($filename)) {
    return false;
}

$application = new Application(['debug' => true]);
$application->run();
