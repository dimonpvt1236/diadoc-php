<?php

declare(strict_types=1);

namespace Test\Unit;

use MagDv\Diadoc\Interfaces\HttpLoggerInterface;
use MagDv\Diadoc\DiadocApi;
use MagDv\Diadoc\Entities\Http\HttpLogDto;
use MagDv\Diadoc\Logger\StdoutHttpLogger;
use Test\base\BaseTest;
use Test\base\MemoryHttpLogger;

class HttpLoggerTest extends BaseTest
{
    public function testLogRequestSendsDtoToLogger(): void
    {
        $memoryHttpLogger = new MemoryHttpLogger();
        $testableDiadocApi = new TestableDiadocApi();
        $testableDiadocApi->setLogger($memoryHttpLogger);

        $testableDiadocApi->exposeLogRequest(
            'https://diadoc-api.kontur.ru/GetMyOrganizations?',
            ['key' => 'value'],
            DiadocApi::METHOD_POST,
            200,
            'response-body'
        );

        self::assertCount(1, $memoryHttpLogger->logs);

        $log = $memoryHttpLogger->logs[0];
        self::assertSame('https://diadoc-api.kontur.ru/GetMyOrganizations?', $log->url);
        self::assertSame(DiadocApi::METHOD_POST, $log->method);
        self::assertSame(200, $log->statusCode);
        self::assertSame('response-body', $log->response);
        self::assertSame('key=value', $log->params);
    }

    public function testLogRequestWithStringParams(): void
    {
        $memoryHttpLogger = new MemoryHttpLogger();
        $testableDiadocApi = new TestableDiadocApi();
        $testableDiadocApi->setLogger($memoryHttpLogger);

        $testableDiadocApi->exposeLogRequest(
            'https://diadoc-api.kontur.ru/V3/Authenticate?',
            'serialized-protobuf',
            DiadocApi::METHOD_POST,
            200,
            'token'
        );

        self::assertCount(1, $memoryHttpLogger->logs);
        self::assertSame('serialized-protobuf', $memoryHttpLogger->logs[0]->params);
    }

    public function testLogRequestWithEmptyParamsIsNull(): void
    {
        $memoryHttpLogger = new MemoryHttpLogger();
        $testableDiadocApi = new TestableDiadocApi();
        $testableDiadocApi->setLogger($memoryHttpLogger);

        $testableDiadocApi->exposeLogRequest(
            'https://diadoc-api.kontur.ru/GetMyOrganizations?',
            [],
            DiadocApi::METHOD_GET,
            200,
            'response-body'
        );

        self::assertCount(1, $memoryHttpLogger->logs);
        self::assertNull($memoryHttpLogger->logs[0]->params);
    }

    public function testLogRequestWithoutLoggerDoesNothing(): void
    {
        $memoryHttpLogger = new MemoryHttpLogger();
        $testableDiadocApi = new TestableDiadocApi();

        $testableDiadocApi->exposeLogRequest(
            'https://diadoc-api.kontur.ru/GetMyOrganizations?',
            [],
            DiadocApi::METHOD_GET,
            200,
            'response-body'
        );

        self::assertCount(0, $memoryHttpLogger->logs);
    }

    public function testSetLoggerWithNullDisablesLogging(): void
    {
        $memoryHttpLogger = new MemoryHttpLogger();
        $testableDiadocApi = new TestableDiadocApi();
        $testableDiadocApi->setLogger($memoryHttpLogger);
        $testableDiadocApi->setLogger(null);

        $testableDiadocApi->exposeLogRequest(
            'https://diadoc-api.kontur.ru/GetMyOrganizations?',
            [],
            DiadocApi::METHOD_GET,
            200,
            'response-body'
        );

        self::assertCount(0, $memoryHttpLogger->logs);
    }

    public function testStdoutHttpLoggerImplementsInterfaceAndDoesNotThrow(): void
    {
        $stdoutHttpLogger = new StdoutHttpLogger();
        self::assertInstanceOf(HttpLoggerInterface::class, $stdoutHttpLogger);

        $httpLogDto = new HttpLogDto(
            url: 'https://diadoc-api.kontur.ru/GetMyOrganizations?',
            method: DiadocApi::METHOD_GET,
            response: 'response-body',
            statusCode: 200,
            params: 'key=value'
        );

        // fwrite(STDOUT, ...) не перехватывается ob_start(), поэтому проверяем только отсутствие исключений
        $stdoutHttpLogger->log($httpLogDto);
        self::assertTrue(true);
    }
}
