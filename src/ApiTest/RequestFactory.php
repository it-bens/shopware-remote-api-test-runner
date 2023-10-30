<?php

declare(strict_types=1);

namespace ITB\ShopwareRemoteAdminApiTestRunner\ApiTest;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

final readonly class RequestFactory
{
    public function createFromGlobals(): Request
    {
        $httpRequest = HttpRequest::createFromGlobals();

        return new Request($httpRequest);
    }
}
