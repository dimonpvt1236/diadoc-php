<?php

declare(strict_types=1);

namespace MagDv\Diadoc\Auth;

use Exception;

/**
 * Устаревший способ авторизации DiadocAuth (ddauth_api_client_id + ddauth_token).
 *
 * Используется по умолчанию для обратной совместимости: поведение полностью
 * повторяет исходный DiadocApi до внедрения OIDC.
 */
class AuthenticateV3AuthMode implements AuthModeInterface
{
    use TokenSanitizerTrait;

    public const MODE = 'authenticate_v3';

    private ?string $token = null;

    public function __construct(private readonly string $ddauthApiClientId)
    {
    }

    public function getMode(): string
    {
        return self::MODE;
    }

    public function buildRequestHeaders(?string $contentType, string $method, bool $forAuthenticateCall): array
    {
        $clientId = $this->sanitizeForHttpHeader($this->ddauthApiClientId);

        if ($forAuthenticateCall) {
            $lines = ['Authorization: DiadocAuth ddauth_api_client_id=' . $clientId];
        } else {
            $token = $this->getToken();
            if ($token === null || $token === '') {
                throw new Exception('Нет ddauth_token: выполните authenticateLoginV3() или setLegacyToken()');
            }

            $lines = [
                'Authorization: DiadocAuth ddauth_api_client_id=' . $clientId
                . ',ddauth_token=' . $this->sanitizeForHttpHeader($token),
            ];
        }

        if ($method === 'POST') {
            $lines[] = 'Content-Type: ' . ($contentType ?: 'application/x-protobuf');
        } else {
            $lines[] = 'Accept: application/x-protobuf';
            if ($contentType !== null && $contentType !== '') {
                $lines[] = 'Content-Type: ' . $contentType;
            }
        }

        return $lines;
    }

    public function ensureReady(): void
    {
        if ($this->getToken() === null || $this->getToken() === '') {
            throw new Exception('Unauthorized request: нет ddauth_token (authenticate_v3)');
        }
    }

    public function handleUnauthorized(): bool
    {
        // В legacy-режиме нет refresh-токена — повторять запрос не нужно.
        return false;
    }

    public function setToken(?string $token): void
    {
        if ($token === null || $token === '') {
            $this->token = null;

            return;
        }

        $this->token = $this->sanitizeForHttpHeader($this->normalizeToken($token));
        if ($this->token === '') {
            $this->token = null;
        }
    }

    public function getToken(): ?string
    {
        return $this->token;
    }
}
