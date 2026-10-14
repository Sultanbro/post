# Post & Booking API

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

## Подготовка локального окружения

Понадобятся Composer, PHP с расширениями для Laravel и выбранной базы, PostgreSQL и отдельный экземпляр Keycloak. `composer.json` задаёт диапазон PHP `^7.3|^8.0`; совместимость конкретного окружения следует проверить по зафиксированным зависимостям. PHP 8.0 используется в существующем Dockerfile.

```sh
git clone https://github.com/Sultanbro/post.git
cd post
cp .env.example .env
composer install
composer check-platform-reqs
php artisan key:generate
```

Создайте отдельную локальную базу и заполните `.env`:

| Параметры | Что указать |
| --- | --- |
| `APP_URL` | `http://127.0.0.1:8000` |
| `DB_CONNECTION` | `pgsql` для PostgreSQL; в `.env.example` исходно указан `mysql` |
| `DB_HOST`, `DB_PORT` | Адрес локальной базы и её порт, например `127.0.0.1` и `5432` |
| `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Созданная база и локальная учётная запись |
| `KEYCLOAK_TOKEN_URL`, `KEYCLOAK_USERINFO_URL`, `KEYCLOAK_USERS_URL` | Полные URL token, userinfo и admin users API вашего realm |
| `KEYCLOAK_CLIENT_ID`, `KEYCLOAK_CLIENT_SECRET` | Клиент Keycloak; текущая реализация входа использует password grant |
| `KEYCLOAK_ADMIN_USERNAME`, `KEYCLOAK_ADMIN_PASSWORD` | Учётная запись для операций управления пользователями |
| `MAIL_*` | Настройки тестового почтового сервиса, если проверяете отправку писем |

После настройки базы:

```sh
php artisan config:clear
php artisan migrate
php artisan storage:link
php artisan route:list
php artisan serve --host=127.0.0.1 --port=8000
```

`DatabaseSeeder` не создаёт демонстрационные данные. Для прикладных сценариев нужно отдельно подготовить пользователей, структуру компаний и разрешения. Сквозной запуск с новой базой и Keycloak пока не проверен; приведены шаги подготовки по текущей структуре проекта.

В `.env.example` стоит `QUEUE_CONNECTION=sync`: задачи выполняются сразу. Для фоновой обработки можно настроить `QUEUE_CONNECTION=database` и запустить `php artisan queue:work` в отдельном терминале.

Существующие `Dockerfile` и файлы в `.deploy/` зависят от частного реестра образов и внешней инфраструктуры. Они описывают развёртывание проекта, но не являются самостоятельной локальной демосредой.

## Проверки

После установки зависимостей:

```sh
php artisan route:list
php artisan test
```

В `tests/` пока находятся базовые примеры PHPUnit. Feature-пример ожидает ответ `200` на `/`, хотя в `routes/web.php` маршрут `/` не определён; этот пример требует обновления. Тесты бизнес-сценариев публикаций, бронирований и прав доступа ещё нужно добавить.

## Зависимости и лицензии

Laravel framework распространяется по [лицензии MIT](https://opensource.org/licenses/MIT). Условия остальных зависимостей указаны в их пакетах и `composer.lock`.
