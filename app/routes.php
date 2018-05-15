<?php

use App\Actions\IndexAction;
use App\Actions\TestAction;
use Clapi\Routing\Router;
use function \router as router;

/**
 * @var Router $router
 */
router()->get('/', IndexAction::class, 'index');
router()->get('/test', TestAction::class, 'test');
