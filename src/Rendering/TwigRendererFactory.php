<?php

namespace Clapi\Rendering;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class TwigRendererFactory
{
    /**
     * @param ContainerInterface $container
     * @return null|TwigRenderer
     */
    public function __invoke(ContainerInterface $container): ?TwigRenderer
    {
        try {
            return new TwigRenderer($container->get('view.path'));
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }

        return null;
    }
}