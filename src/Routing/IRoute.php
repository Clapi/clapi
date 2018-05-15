<?php

namespace Clapi\Routing;

/**
 * Interface IRoute
 * @package Clapi\Routing
 */
interface IRoute
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return callable|string
     */
    public function getCallback();

    /**
     * @return string[]
     */
    public function getParams(): array;
}
