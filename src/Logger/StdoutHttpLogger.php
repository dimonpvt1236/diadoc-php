<?php

declare(strict_types=1);

namespace MagDv\Diadoc\Logger;

use MagDv\Diadoc\Entities\Http\HttpLogDto;
use MagDv\Diadoc\Interfaces\HttpLoggerInterface;

/**
 * Логгер HTTP-запросов, пишущий данные запроса/ответа в STDOUT.
 */
class StdoutHttpLogger implements HttpLoggerInterface
{
    public function log(HttpLogDto $httpLogDto): void
    {
        $message = sprintf(
            "[%s] %s %s => %d\nparams: %s\nresponse: %s\n",
            date('c'),
            $httpLogDto->method,
            $httpLogDto->url,
            $httpLogDto->statusCode,
            $httpLogDto->params ?? '',
            $httpLogDto->response
        );

        fwrite(STDOUT, $message);
    }
}
