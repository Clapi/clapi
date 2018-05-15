<?php

use Clapi\Http\Request;
use Clapi\Middlewares\DispatcherMiddleware;
use Clapi\Middlewares\MethodMiddleware;
use Clapi\Middlewares\NotFoundMiddleware;
use Clapi\Middlewares\RouterMiddleware;
use Clapi\Middlewares\TrailingSlashMiddleware;
use Middlewares\Whoops;

define('ROOT', realpath(__DIR__ . '/../'));

require ROOT . '/vendor/autoload.php';
require ROOT . '/src/helpers.php';
try {
    app()->pipe(Whoops::class)
        ->pipe(TrailingSlashMiddleware::class)
        ->pipe(MethodMiddleware::class)
        ->pipe(RouterMiddleware::class)
        ->pipe(DispatcherMiddleware::class)
        ->pipe(NotFoundMiddleware::class);
} catch (Exception $e) {
    echo $e->getMessage();
}

if (php_sapi_name() !== 'cli') {
    try {
        $response = app()->run(Request::fromGlobals());
        \Http\Response\send($response);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}