<?php

namespace Clapi\Middlewares;

use Clapi\Routing\Route;
use Clapi\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouterMiddleware implements MiddlewareInterface
{

    private function addParamsInRequest(ServerRequestInterface $request, $params): ServerRequestInterface
    {
        return array_reduce(array_keys($params), function (ServerRequestInterface $request, string $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = \router()->match($request);
        if ($route === null) {
            return $handler->handle($request);
        }

        $request = $this->addParamsInRequest($request, $route->getParams());
        $request = $request->withAttribute(Route::class, $route);
        return $handler->handle($request);
    }
}
