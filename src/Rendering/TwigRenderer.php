<?php

namespace Clapi\Rendering;

use Clapi\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

class TwigRenderer implements IRenderer
{
    /**
     * @var TwigRenderer $twig
     */
    private $twig;

    /**
     * @var Twig_Loader_Filesystem $loader
     */
    private $loader;

    /**
     * Renderer constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->loader = new Twig_Loader_Filesystem($path);
        $this->twig = new Twig_Environment($this->loader, [
            'debug' => true
        ]);
        $this->twig->addExtension(new Twig_Extension_Debug());
    }

    /**
     * @param string $namespace
     * @param null|string $path
     * @throws \Twig_Error_Loader
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        $this->loader->addPath($path, $namespace);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }

    /**
     * @param string $view
     * @param array $params
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(string $view, array $params = []): ResponseInterface
    {
        return new Response($this->twig->render($view.'.twig', $params), 200);
    }
}