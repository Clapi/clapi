<?php

namespace Clapi\Http;

use GuzzleHttp\Psr7\ServerRequest as GuzzleServerRequest;

class Request extends GuzzleServerRequest
{
    public function isJson()
    {
        return ($this->getServerParams()['CONTENT_TYPE'] ?? null) === 'application/json';
    }

    public static function fromGlobals()
    {
        $request = parent::fromGlobals();
        $serverRequest = new Request(
            $request->getMethod(),
            $request->getUri(),
            $request->getHeaders(),
            $request->getBody(),
            $request->getProtocolVersion(),
            $request->getServerParams());

        return $serverRequest
            ->withCookieParams($_COOKIE)
            ->withQueryParams($_GET)
            ->withParsedBody($_POST)
            ->withUploadedFiles(self::normalizeFiles($_FILES));
    }


}
