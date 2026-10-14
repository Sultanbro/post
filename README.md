# Post & Booking API

[![API checks](https://github.com/Sultanbro/post/actions/workflows/tests.yml/badge.svg)](https://github.com/Sultanbro/post/actions/workflows/tests.yml)

Backend корпоративного приложения на **PHP / Laravel**: публикации и обсуждения, бронирование помещений, пользователи и права доступа. API объединяет эти модули и взаимодействует с Keycloak для авторизации.

## Мой вклад

Самостоятельно реализовал backend проекта: API и бизнес-логику модулей, работу с данными через Eloquent и репозитории, проверки прав доступа и интеграцию с Keycloak.

## Возможности

- **Публикации:** создание и редактирование записей, вложения, комментарии, ответы на комментарии и реакции.
- **Бронирования:** помещения, интервалы бронирования и участники.
- **Доступ:** роли, разрешения, группы разрешений и проверки доступа к отдельным операциям.
- **Пользователи:** профили, аватары, подразделения и данные сотрудников.
- **Интеграции:** получение и обновление токенов Keycloak, приём данных из внешних систем, отправка email через задачи очереди.
- **Дополнительный модуль:** операции с внутренними баллами Centcoin и заявки на их начисление.

## Стек

| Часть | Технологии |
| --- | --- |
| Backend | PHP, Laravel 8; в `composer.lock` зафиксирован Laravel 8.81.0 |
| Данные | Eloquent ORM, миграции; PostgreSQL в конфигурации развёртывания |
| Авторизация | Keycloak, Bearer-токены, собственные middleware и проверки разрешений |
| Фоновые задачи | Laravel Queue, Laravel Mail |
| Инструменты | Composer, PHPUnit 9, Laravel Telescope |
| Развёртывание | Docker, Nginx, PHP-FPM, Supervisor |

Репозиторий содержит backend. Пользовательский интерфейс разворачивается отдельно; Laravel Mix здесь используется для локальных ресурсов приложения.

## Как устроен код

```text
routes/api.php
    → middleware: авторизация и проверка разрешений
    → controller / FormRequest
    → service и repository
    → Eloquent model / база данных
    → API resource / JSON-ответ
```

Контроллеры получают сервисы и репозитории через интерфейсы; реализации подключаются в service providers. Сервисы содержат операции приложения, репозитории — запросы к данным. В некоторых обработчиках контроллер обращается к репозиторию напрямую.

| Где смотреть | Что находится внутри |
| --- | --- |
| [routes/api.php](routes/api.php) | Маршруты API |
| [app/Http/Controllers/Api](app/Http/Controllers/Api) | Обработчики запросов по модулям |
| [app/Http/Requests](app/Http/Requests) | Валидация входных данных |
| [app/Http/Services](app/Http/Services) | Сервисы приложения и интеграции |
| [app/Repository](app/Repository) | Интерфейсы репозиториев и запросы к данным |
| [app/Http/Resources](app/Http/Resources) | Формирование ответов API |
| [app/Http/Middleware](app/Http/Middleware) | Авторизация и проверки доступа |
| [app/Providers](app/Providers) | Регистрация зависимостей |
| [database/migrations](database/migrations) | Схема базы данных |

Для знакомства с реализацией:

1. **Публикация с вложениями:** [PostController](app/Http/Controllers/Api/Post/PostController.php) → [PostService](app/Http/Services/Post/PostService.php) → [PostRepository](app/Repository/Post/PostRepository.php).
2. **Бронирование:** [BookingService](app/Http/Services/Booking/BookingService.php) — обработка времени и сохранение бронирования.
3. **Авторизация:** [UserAuthService](app/Http/Services/Authenticate/UserAuthService.php), [KeyCloakService](app/Http/Services/Authenticate/KeyCloakService.php) и [BearerAuthenticate](app/Http/Middleware/BearerAuthenticate.php).

## Основные маршруты

Все маршруты ниже имеют префикс `/api`. Полный список и назначенные middleware находятся в [routes/api.php](routes/api.php).

| Метод | Путь | Назначение |
| --- | --- | --- |
| `POST` | `/api/login` | Вход по `username` и `password` |
| `POST` | `/api/logout` | Выход |
| `GET`, `POST` | `/api/posts` | Список и создание публикаций |
| `GET`, `PUT/PATCH`, `DELETE` | `/api/posts/{post}` | Просмотр, изменение и удаление публикации |
| `GET`, `POST` | `/api/comments` | Комментарии |
| `GET`, `POST` | `/api/rooms` | Помещения |
| `GET`, `POST` | `/api/booking` | Бронирования |
| `GET`, `POST` | `/api/access/role` | Роли |
| `GET` | `/api/user` | Текущий пользователь |

Защищённые запросы передают `Authorization: Bearer <access_token>` и `Accept: application/json`. Вход требует настроенного Keycloak и соответствующей записи пользователя в локальной базе; доступ к операциям зависит от назначенных разрешений.

## Запуск демо

Понадобится Docker с Compose v2. PHP и Composer на компьютере устанавливать не нужно.

```sh
git clone https://github.com/Sultanbro/post.git
cd post
docker compose -f compose.local.yml up -d --build --wait --wait-timeout 240
```

Первый запуск скачивает образы, устанавливает зависимости из `composer.lock`, применяет миграции и создаёт демонстрационные данные. API доступно по `http://localhost:8088/api`; отдельного веб-интерфейса нет. Если порт занят, перед командой задайте `POST_DEMO_PORT=8089`.

Среда содержит PHP 8.1, PostgreSQL 15 и Keycloak 26.7.5. PHP 8.1 выбран для воспроизведения существующего `composer.lock`; переход на поддерживаемые версии PHP и Laravel — отдельная задача перед производственным развёртыванием. Настройки находятся в [compose.local.yml](compose.local.yml), локальный `.env` не нужен.

| Пользователь | Пароль для локального демо | Права на публикации |
| --- | --- | --- |
| `editor` | `local-demo-only` | Просмотр, создание, изменение и удаление |
| `reader` | `local-demo-only` | Только просмотр |

Вход проходит через настоящий Keycloak. В демонстрационных данных — вымышленные компания, сотрудники и публикация. Учётные записи и разрешения создаёт [DemoSeeder](database/seeders/DemoSeeder.php), повторный запуск не дублирует эти записи. Seeder разрешён только в окружениях `local` и `testing`.

Пример входа:

```sh
curl -s http://localhost:8088/api/login \
  -H 'Accept: application/json' \
  -H 'Content-Type: application/json' \
  -d '{"username":"editor","password":"local-demo-only"}'
```

Подставьте `access_token` из ответа:

```sh
curl -s http://localhost:8088/api/posts \
  -H 'Accept: application/json' \
  -H 'Authorization: Bearer <access_token>'
```

При истечении токена нужен повторный вход: защищённый запрос с недействительным токеном получает `401`, без неявного обновления сессии. После выхода локальная сессия удаляется.

Демо проверяет сценарий публикаций и разграничение прав. Для регистрации пользователей через admin API Keycloak, бронирований, почты и остальных модулей потребуется дополнительная настройка и данные. Порт API привязан к `127.0.0.1`; PostgreSQL и Keycloak доступны только внутри сети контейнеров. Открытые демонстрационные пароли и режим Keycloak `start-dev` предназначены для локального запуска.

Остановка с сохранением демонстрационной базы:

```sh
docker compose -f compose.local.yml down
```

## Проверки

После запуска среды:

```sh
# Проверки Laravel API на отдельной базе post_test
docker compose -f compose.local.yml run --rm --no-deps app php vendor/bin/phpunit

# Сквозной сценарий через работающие API и Keycloak
docker compose -f compose.local.yml exec -T app php docker/local/smoke.php
```

[PostApiTest](tests/Feature/PostApiTest.php) проверяет вход, выход, работу с публикациями, обязательные поля, роли редактора и читателя, неизвестные и истёкшие токены, ошибки и недоступность сервиса авторизации. Запросы проходят через Laravel, middleware, проверки разрешений и PostgreSQL; в этих тестах подменяются только HTTP-ответы Keycloak. Тесты пересоздают таблицы исключительно в `post_test` и отказываются работать с другим именем базы или вне окружения `testing`.

[Сквозная проверка](docker/local/smoke.php) использует реальный Keycloak: вход двух пользователей, чтение, создание, изменение, удаление, запрет записи читателю и отклонение токена после выхода. Токены не выводятся в журнал. Эти проверки запускаются в [GitHub Actions](.github/workflows/tests.yml) при push и pull request. Бронирования и остальные модули пока не покрыты этими тестами.

## Настройка собственного окружения

Для собственного окружения скопируйте `.env.example` в `.env`, установите зависимости совместимым PHP и Composer и создайте ключ через `php artisan key:generate`. Укажите PostgreSQL в `DB_CONNECTION=pgsql` и заполните `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

Для входа нужны `KEYCLOAK_TOKEN_URL`, `KEYCLOAK_USERINFO_URL`, `KEYCLOAK_CLIENT_ID` и, для конфиденциального клиента, `KEYCLOAK_CLIENT_SECRET`. Текущий вход использует password grant и scope `openid`. Пользователь должен существовать в Keycloak и локальной базе. Управление пользователями дополнительно требует `KEYCLOAK_USERS_URL`, `KEYCLOAK_ADMIN_USERNAME`, `KEYCLOAK_ADMIN_PASSWORD` с соответствующими правами.

После настройки выполните `php artisan config:clear`, `php artisan migrate` и `php artisan storage:link`. `DatabaseSeeder` не создаёт демонстрационные данные автоматически. Настройки `MAIL_*` задают почтовый сервис; `QUEUE_CONNECTION=sync` выполняет задачи сразу, а для фоновой обработки можно выбрать `database` и запустить `php artisan queue:work`.

Существующие корневой `Dockerfile` и `.deploy/` описывают другое развёртывание с частным реестром и внешней инфраструктурой. Самостоятельная локальная среда использует отдельные файлы `compose.local.yml` и `docker/local/`.

## Зависимости и лицензии

Laravel framework распространяется по [лицензии MIT](https://opensource.org/licenses/MIT). Условия остальных зависимостей указаны в их пакетах и `composer.lock`.
