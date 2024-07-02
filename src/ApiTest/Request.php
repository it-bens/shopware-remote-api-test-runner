<?php

declare(strict_types=1);

namespace ITB\ShopwareRemoteApiTestRunner\ApiTest;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

final readonly class Request
{
    private const HEADER_TRANSACTIONAL_INDICATOR = 'sw-remote-api-test-runner-transactional';

    private const HEADER_TRANSACTIONAL_DEFAULT = true;

    public function __construct(
        public HttpRequest $httpRequest,
    ) {
    }

    public function isRequestTransactional(): bool
    {
        $transactionalHeader = $this->httpRequest->headers->get(self::HEADER_TRANSACTIONAL_INDICATOR, null);
        if ($transactionalHeader === null) {
            return self::HEADER_TRANSACTIONAL_DEFAULT;
        }

        return $transactionalHeader[0] === 'true';
    }
}
