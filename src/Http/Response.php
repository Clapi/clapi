<?php

namespace Clapi\Http;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response extends GuzzleResponse
{
    public function __construct($body, int $status = 200, $headers = [])
    {
        $version = '1.1';
        $reason = null;
        parent::__construct($status, $headers, $body, $version, $reason);
    }
}