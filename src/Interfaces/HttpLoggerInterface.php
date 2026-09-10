<?php

declare(strict_types=1);

namespace MagDv\Diadoc\Interfaces;

use MagDv\Diadoc\Entities\Http\HttpLogDto;

/**
 * Контракт логгера HTTP-запросов к API Диадока.
 *
 * Реализации получают готовый {@see HttpLogDto} с данными запроса и ответа
 * и отвечают за их вывод/сохранение (STDOUT, файл, БД и т.п.).
 *
 * Примеры реализаций:
 *  - {@see \MagDv\Diadoc\Logger\StdoutHttpLogger} — вывод в STDOUT.
 */
interface HttpLoggerInterface
{
    /**
     * Записать лог HTTP-запроса.
     *
     * @param HttpLogDto $httpLogDto данные запроса и ответа
     */
    public function log(HttpLogDto $httpLogDto): void;
}
