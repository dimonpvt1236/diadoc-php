<?php

declare(strict_types=1);

namespace MagDv\Diadoc\Entities\Http;

/**
 * Данные HTTP-запроса и ответа для логирования.
 *
 * Передаётся в {@see \MagDv\Diadoc\Interfaces\HttpLoggerInterface::log()}.
 */
class HttpLogDto
{
    public function __construct(
        public string $url,
        public string $method,
        public string $response,
        public int $statusCode,
        public ?string $params = null,
    ) {
    }
}
