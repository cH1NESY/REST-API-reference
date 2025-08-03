# Настройка окружения

## Создание .env файла

Создайте файл `.env` в корне проекта со следующим содержимым:

```env
APP_NAME="REST API Reference"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# API Key for authentication
API_KEY=test-api-key-123
```

## Последовательность запуска

1. Создайте `.env` файл (см. выше)
2. Запустите Docker контейнеры: `docker-compose up -d`
3. Установите зависимости: `docker-compose exec php-fpm composer install`
4. Сгенерируйте ключ приложения: `docker-compose exec php-fpm php artisan key:generate`
5. Запустите миграции: `docker-compose exec php-fpm php artisan migrate:fresh --seed`
6. Сгенерируйте документацию: `docker-compose exec php-fpm php artisan l5-swagger:generate`

## Проверка работы

После запуска вы можете проверить работу API:

```bash
# Проверка API ключа
curl -H "X-API-Key: test-api-key-123" http://localhost:8080/api/buildings

# Получение организации
curl -H "X-API-Key: test-api-key-123" http://localhost:8080/api/organizations/1
```

## Доступ к документации

Swagger UI будет доступен по адресу: http://localhost:8080/api/documentation

## База данных

Приложение использует PostgreSQL. Данные сохраняются в Docker volume `postgres_data`.

Для подключения к базе данных:
- Host: localhost
- Port: 54400
- Database: laravel
- Username: laravel
- Password: secret 