<?php

use Clapi\Http\Request;
use Clapi\Rendering\IRenderer;
use Clapi\Rendering\TwigRendererFactory;
use Clapi\Routing\IRouter;
use Clapi\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

return [
    IRenderer::class => DI\factory(TwigRendererFactory::class),
    IRouter::class => DI\object(Router::class),
    ServerRequestInterface::class => function () {
        return Request::fromGlobals();
    }
];
