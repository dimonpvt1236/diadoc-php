<?php

declare(strict_types=1);

namespace MagDv\Diadoc\Auth;

/**
 * Общие хелперы для нормализации токенов перед использованием в HTTP-заголовках.
 *
 * Токены из .env/ответов могут содержать BOM, пробелы и управляющие символы,
 * которые ломают HTTP-заголовки (libcurl возвращает ошибку 55).
 */
trait TokenSanitizerTrait
{
    /**
     * Нормализация строки токена (BOM/мусор ломают заголовки).
     */
    private function normalizeToken(string $token): string
    {
        $token = trim($token);
        if (str_starts_with($token, "\xEF\xBB\xBF")) {
            $token = substr($token, 3);
        }

        return $token;
    }

    /**
     * Убирает управляющие символы из значений в HTTP-заголовках (иначе libcurl может вернуть 55).
     */
    private function sanitizeForHttpHeader(string $value): string
    {
        $value = str_replace("\r", '', $value);

        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value) ?? '';
    }
}
