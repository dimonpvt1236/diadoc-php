<?php

declare(strict_types=1);

namespace Test\Unit;

use MagDv\Diadoc\DiadocApi;

/**
 * Подкласс DiadocApi для доступа к protected-методу logRequest() в тестах.
 */
class TestableDiadocApi extends DiadocApi
{
    public function __construct()
    {
        parent::__construct('dd-client', 'https://diadoc-api.kontur.ru/');
    }

    public function exposeLogRequest(string $uri, mixed $postData, string $method, int $httpCode, string $response): void
    {
        $this->logRequest($uri, $postData, $method, $httpCode, $response);
    }
}
