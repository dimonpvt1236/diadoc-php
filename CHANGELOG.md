# CHANGELOG

## 0.6.0

### Added

- OpenID Connect авторизация (Authorization Code + Bearer) через `DiadocApi::create()` со стратегией `OidcAuthMode`.
- Стратегии авторизации в `src/Auth/`:
  - `AuthModeInterface` — контракт стратегии;
  - `AuthenticateV3AuthMode` — устаревший DiadocAuth (обратная совместимость);
  - `OidcAuthMode` — OIDC (Bearer + refresh + retry).
- OIDC-методы в `DiadocApi`:
  - `buildAuthorizationUrl()`
  - `exchangeAuthorizationCode()`
  - `refreshAccessToken()`
  - `setOAuthSession()`
  - `getOAuthSessionState()`
  - `setOAuthSessionPersistenceCallback()`
  - `getOidcScope()`
  - `getAuthMode()`
  - `setLegacyToken()`
- Автоматическое управление жизненным циклом токена:
  - проактивный refresh по `expires_at`;
  - один ретрай на `401` с refresh-токеном.
- Определение scope по окружению:
  - `Diadoc.PublicAPI` для production;
  - `Diadoc.PublicAPI.Staging` для staging/test (`diadoc-api-staging`, `diadoc-api-test`).
- Локальный OAuth-тест-сервер:
  - `docker/oauth-test/router.php`;
  - `docker compose --profile oauth up oauth-test`.

### Backward compatibility

- Конструктор `DiadocApi` не изменился: `new DiadocApi($ddauthApiClientId, $serviceUrl, $debug, $signerProvider)` по-прежнему использует устаревший DiadocAuth.
- Для произвольного режима авторизации (в т.ч. OIDC) используйте статический фабричный метод `DiadocApi::create(AuthModeInterface $authMode, $serviceUrl, $debug, $signerProvider)`. Для OIDC передайте `new OidcAuthMode($clientId, $clientSecret, $identityBaseUrl, $serviceUrl)`; фабрика проверяет совпадение `serviceUrl` стратегии и клиента (`\RuntimeException` при рассинхронизации).

### Updated

- `.env.example` — добавлены OIDC-переменные.
- `docker-compose.yml` — добавлен сервис `oauth-test`.
- Тесты: `ApiClient`, `AuthTest`, `ConfigNames`, `SimpleFileCache`.

## 0.0.3

- Generate signed content not only from file
- Fix default timezone in `DateHelper`

## 0.0.2

- Methods for Events
- Wrappers `BoxApi`, `OrganizationApi`
- `GetDocument` method
- Bugfix

## 0.0.1

Init