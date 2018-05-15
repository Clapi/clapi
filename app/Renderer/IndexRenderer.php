<?php

namespace App\Renderer;

use Clapi\Http\Renderer;

class IndexRenderer extends Renderer
{
    protected function response($data)
    {
        return $this->renderer->render('index', ['data' => $data]);
    }
}