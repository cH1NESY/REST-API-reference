# Проверка работы приложения

## 🚀 Быстрая проверка

### 1. Проверка статуса контейнеров
```bash
docker-compose ps
```
Все контейнеры должны быть в статусе "Up".

### 2. Проверка доступности приложения
```bash
curl -I http://localhost:95
```
Должен вернуть HTTP 200.

### 3. Проверка API аутентификации
```bash
# Без API ключа (должен вернуть 401)
curl -s http://localhost:95/api/buildings

# С правильным API ключом (должен вернуть данные)
curl -H "X-API-Key: test-api-key-123" http://localhost:95/api/buildings
```

### 4. Проверка основных эндпоинтов

#### Получение организации по ID
```bash
curl -H "X-API-Key: test-api-key-123" http://localhost:95/api/organizations/1
```

#### Поиск организаций по названию
```bash
curl -H "X-API-Key: test-api-key-123" "http://localhost:95/api/organizations/search?name=Рога"
```

#### Получение дерева видов деятельности
```bash
curl -H "X-API-Key: test-api-key-123" http://localhost:95/api/activities/tree
```

#### Геопоиск организаций
```bash
curl -H "X-API-Key: test-api-key-123" "http://localhost:95/api/organizations/radius?latitude=55.7558&longitude=37.6176&radius=5"
```

### 5. Проверка Swagger документации
Откройте в браузере: http://localhost:95/api/documentation

### 6. Запуск тестов
```bash
docker-compose exec php-fpm php artisan test
```
Все тесты должны пройти успешно.

## 🔧 Детальная проверка

### Проверка базы данных
```bash
# Подключение к PostgreSQL
docker-compose exec postgres psql -U dbuser -d laravel

# Проверка таблиц
\dt

# Проверка данных
SELECT * FROM organizations LIMIT 5;
SELECT * FROM buildings LIMIT 5;
SELECT * FROM activities LIMIT 5;
```

### Проверка логов
```bash
# Логи Laravel
docker-compose exec php-fpm tail -f storage/logs/laravel.log

# Логи Nginx
docker-compose logs web

# Логи PHP-FPM
docker-compose logs php-fpm

# Логи PostgreSQL
docker-compose logs postgres
```

### Проверка конфигурации
```bash
# Проверка переменных окружения
docker-compose exec php-fpm php artisan config:show

# Проверка маршрутов
docker-compose exec php-fpm php artisan route:list
```

## 📊 Ожидаемые результаты

### API ответы должны содержать:
- **Организации**: id, name, building_id, building, phones, activities
- **Здания**: id, address, latitude, longitude, organizations
- **Деятельность**: id, name, parent_id, level, children

### Тесты должны пройти:
- ✅ API key authentication
- ✅ Get organization
- ✅ Get organizations by building
- ✅ Search organizations by name
- ✅ Get activities tree

### Swagger UI должен:
- ✅ Открываться без ошибок
- ✅ Показывать все эндпоинты
- ✅ Позволять тестировать API

## 🚨 Возможные проблемы

### 1. Контейнеры не запускаются
```bash
# Перезапуск всех контейнеров
docker-compose down
docker-compose up -d
```

### 2. База данных недоступна
```bash
# Проверка статуса PostgreSQL
docker-compose exec postgres pg_isready -U dbuser

# Пересоздание базы данных
docker-compose exec php-fpm php artisan migrate:fresh --seed
```

### 3. API возвращает 500 ошибки
```bash
# Очистка кэша
docker-compose exec php-fpm php artisan config:clear
docker-compose exec php-fpm php artisan cache:clear
docker-compose exec php-fpm php artisan route:clear
```

### 4. Тесты падают
```bash
# Пересоздание тестовой базы данных
docker-compose exec postgres dropdb -U dbuser laravel_test
docker-compose exec postgres createdb -U dbuser laravel_test
docker-compose exec php-fpm php artisan migrate --env=testing
```

## 📝 Логи проверки

После выполнения всех проверок, приложение должно:
- ✅ Отвечать на HTTP запросы
- ✅ Требовать API ключ для доступа
- ✅ Возвращать корректные JSON ответы
- ✅ Поддерживать все требуемые эндпоинты
- ✅ Иметь работающую Swagger документацию
- ✅ Проходить все тесты 