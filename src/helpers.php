<?php

use Clapi\App;
use Clapi\Http\JsonResponse;
use Clapi\Http\Response;
use Clapi\Routing\IRouter;
use Clapi\Routing\Router;
use Symfony\Component\Dotenv\Dotenv;


if (!function_exists('app')) {
    function app(): App
    {
        static $app = null;

        if ($app === null) {
            $app = new App(ROOT . '/bin/configs');
        }

        return $app;
    }
}

if (!function_exists('config')) {
    /**
     * @param string $key
     * @return mixed
     */
    function config(string $key)
    {
        try {
            return app()->getContainer()->get($key);
        } catch (\DI\DependencyException $e) {
            return $e->getMessage();
        } catch (\DI\NotFoundException $e) {
            return $e->getMessage();
        }
    }
}

if (!function_exists('router')) {
    /**
     * @return Router
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    function router(): Router
    {
        return app()->getContainer()->get(IRouter::class);
    }
}

if (!function_exists('response')) {
    /**
     * @param $body
     * @param int $tatus
     * @param array $headers
     * @return Response
     */
    function response($body, int $tatus = 200, $headers = []): Response
    {
        return new Response($body, $tatus, $headers);
    }
}

if (!function_exists('json')) {
    /**
     * @param $body
     * @param int $tatus
     * @return JsonResponse
     */
    function json($body, int $tatus = 200): JsonResponse
    {
        return new JsonResponse($body, $tatus);
    }
}

if (!function_exists('env')) {

    function env(string $key)
    {
        $dotenv = new Dotenv();
        $dotenv->load(ROOT . '/.env');
        return getenv($key);
    }
}