<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Symfony\Component\ClassLoader\ApcClassLoader;
/*
 * @var Composer\Autoload\ClassLoader
 */
$loader = require __DIR__.'/../backend/app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';

$loader = new ApcClassLoader('campr', $loader);
$loader->register(true);

$env = getenv('SYMFONY_ENV') ? getenv('SYMFONY_ENV') : 'prod';
$debug = getenv('SYMFONY_DEBUG') ? (getenv('SYMFONY_DEBUG') === 'false' ? false : true) : false;

if ($debug) {
    Debug::enable();
}

$env = str_replace('-', '_', $env);
$envParts = explode('_', $env);
if ($env != end($envParts)) {
    $apcLoader = new ApcClassLoader($env.'.campr.biz', $loader);
    $loader->unregister();
    $apcLoader->register(true);
}

$kernel = new AppKernel($env, $debug);
$kernel->loadClassCache();
$kernel = new AppCache($kernel);

Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
