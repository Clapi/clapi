<?php

namespace Clapi;

use App\Actions\IndexAction;
use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Middlewares\Utils\CallableHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class App
 * @package Clapi
 */
class App implements MiddlewareInterface
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var array
     */
    private $migrations = [];

    /**
     * @var array
     */
    private $seeds = [];

    /**
     * @var string $configFolder
     */
    private $configFolder;

    /**
     * @var string[] $middlewares
     */
    private $middlewares = [];

    /**
     * @var int $index
     */
    private $index = 0;

    /**
     * App constructor.
     * @param string $configFolder
     */
    public function __construct(string $configFolder)
    {
        $this->configFolder = $configFolder;
    }

    /**
     * @return array
     */
    public function getMigrations(): array
    {
        return $this->migrations;
    }

    /**
     * @return array
     */
    public function getSeeds(): array
    {
        return $this->seeds;
    }

    /**
     * @param string $middleware
     * @return self
     */
    public function pipe(string $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface|null $handler
     * @return ResponseInterface
     * @throws DependencyException
     * @throws Exception
     * @throws NotFoundException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler = null): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        if ($middleware instanceof MiddlewareInterface)
        {
            return $middleware->process($request, new CallableHandler([$this, 'process']));
        }

        throw new Exception('No middleware intercepted this request');
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        require $this->getContainer()->get('app.routes');

        return $this->process($request);
    }

    /**
     * Génère un container s'il n'existe pas et le retourne
     * ou le retourne directement si ce dernier est déjà créé
     *
     * @return Container
     */
    public function getContainer()
    {
        if ($this->container === null)
        {
            $containerBuilder = new ContainerBuilder();
            $this->initContainerBuilderWithFiles($containerBuilder);
            $this->container = $containerBuilder->build();
        }

        return $this->container;
    }

    /**
     * Ajoute la configuration des fichiers dans le DIC
     *
     * @param ContainerBuilder $containerBuilder
     */
    private function initContainerBuilderWithFiles(ContainerBuilder $containerBuilder)
    {
        $rdi = new RecursiveDirectoryIterator($this->configFolder, RecursiveDirectoryIterator::SKIP_DOTS);
        $rit = new RecursiveIteratorIterator($rdi);

        foreach ($rit as $file) {
            if ($file->getExtension() === 'php') {
                $containerBuilder->addDefinitions($file->getRealPath());
            }
        }
    }

    /**
     * @return callable|null|object
     * @throws DependencyException
     * @throws NotFoundException
     */
    private function getMiddleware()
    {
        if (array_key_exists($this->index, $this->middlewares)) {
            $middleware = $this->getContainer()
                ->get($this->middlewares[$this->index]);

            $this->index += 1;
            return $middleware;
        }

        return null;
    }
}
