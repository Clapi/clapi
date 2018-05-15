<?php

namespace Clapi\Http;

use GuzzleHttp\Psr7\Response;

class JsonResponse extends Response
{
    public function __construct($body, int $status = 200)
    {
        $version = '1.1';
        $reason = null;

        $body = json_encode($body);

        parent::__construct($status, [
            'Content-Type' => 'application/json'
        ], $body, $version, $reason);
    }
}