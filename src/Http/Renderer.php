<?php

namespace Clapi\Http;

use Clapi\Rendering\IRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Renderer
{
    /**
     * @var ServerRequestInterface $request
     */
    protected $request;

    /**
     * @var IRenderer $renderer
     */
    protected $renderer;

    /**
     * Renderer constructor.
     * @param ServerRequestInterface $request
     * @param IRenderer $renderer
     */
    public function __construct(ServerRequestInterface $request, IRenderer $renderer)
    {
        $this->renderer = $renderer;
        $this->request = $request;
    }

    public function send($data): ResponseInterface
    {
        if ($this->request->isJson()) {
            return $this->jsonResponse($data);
        }

        if (!$this->isProduction() && $this->isDebug()) {
            return $this->debug($data);
        }

        return $this->response($data);
    }

    protected function jsonResponse($data)
    {
        return json($data);
    }

    protected function response($data)
    {
        return $this->jsonResponse($data);
    }

    private function isDebug()
    {
        return (config('app.debug') === 'true' && isset($this->request->getQueryParams()['_debug']));
    }

    private function isProduction()
    {
        return (config('app.env') === 'production');
    }

    private function debug($data)
    {
        return $this->renderer->render('debug', [
            'data' => $data,
            'app' => [
                'request' => [
                    'time' => round((microtime(true)-$_SERVER['REQUEST_TIME_FLOAT'])*1000,0)
                ]
            ]
        ]);
    }
}
