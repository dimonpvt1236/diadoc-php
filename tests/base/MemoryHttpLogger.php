<?php

declare(strict_types=1);

namespace Test\base;

use MagDv\Diadoc\Entities\Http\HttpLogDto;
use MagDv\Diadoc\Interfaces\HttpLoggerInterface;

/**
 * Тестовая реализация логгера, накапливающая записи в памяти.
 */
class MemoryHttpLogger implements HttpLoggerInterface
{
    /** @var list<HttpLogDto> */
    public array $logs = [];

    public function log(HttpLogDto $httpLogDto): void
    {
        $this->logs[] = $httpLogDto;
    }
}
