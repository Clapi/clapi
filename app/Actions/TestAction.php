<?php

namespace App\Actions;

use App\Renderer\TestRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TestAction implements MiddlewareInterface
{

    /**
     * @var TestRenderer $renderer
     */
    private $renderer;

    public function __construct(TestRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $obj = [
            'App' => env('APP_NAME'),
            'test' => [
                '1' => 'coucou',
                '2' => 'hello'
            ]
        ];
        return $this->renderer->send($obj);
    }
}
