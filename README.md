# REST-API-reference

## Описание

Проект реализует rest api справочник организаций, зданий, деятельности.

Сущность "Деятельности" имеет следующие поля:

- id
- Имя
- id родителя
- уровень

Сущность "Здания" имеет следующие поля:

- id
- Адрес
- Широта
- Долгота

Сущность "Организации" имеет следующие поля:

- id
- Имя
- id здания

Сущность "Телефоны организации" имеет следующие поля:

- id
- Номер
- id организации

Сущность "Деятельности организации" имеет следующие поля:

- id 
- id организации
- id деятельности


## Что было реализовано:

- **Набор Rest-методов, которые позволяют**:
    - Поиск организации по названию
    - Получить списка организаций, которые относятся к указанному виду деятельности
    - Получить список организаций, которые находятся в заданном радиусе/прямоугольной области
    - Получить информацию об организации по её идентификатору
    - Поиск организации по виду деятельности с дочерними деятельностями
    - Получить список всех организаций
    - Получить список всех организаций находящихся в конкретном здании
    - Получить список всех зданий
    - Получить информацию о конкретном здании
    - Получить список деятельностей
    - Получить дерево деятельностей
    - Получить информацию о конкретной деятельности
  
    
- **Docker контейнеризация**:
    - Использование Docker для развертывания приложения и базы данных.

-**Seeder'ы для заполнения бд**


### Что было реализовано:

- **GetActivityByIdAction** — Получить информацию о конкретной деятельности.
- **GetActivityTreeAction** - Получить дерево деятельностей.
- **GetRootActivitiesAction** — Получить список деятельностей.
- **GetAllBuildingsAction** — Получить список всех зданий.
- **GetBuildingByIdAction** - Получить информацию о конкретном здании.
- **GetAllOrganizationsAction** - Получить список всех организаций.
- **GetOrganizationByIdAction** — Получить информацию об организации по её идентификатору.
- **GetOrganizationsByActivityAction** — Получить списка организаций, которые относятся к указанному виду деятельности.
- **GetOrganizationsByActivityWithDescendantsAction** — Поиск организации по виду деятельности с дочерними деятельностями.
- **GetOrganizationsByAreaAction** — Получить список организаций, которые находятся в заданной прямоугольной области.
- **GetOrganizationsByBuildingAction** — Получить список всех организаций находящихся в конкретном здании.
- **GetOrganizationsByRadiusAction** - Получить список организаций, которые находятся в заданном радиусе.
- **SearchOrganizationsByNameAction** - Поиск организации по названию.
- **AreaParamsDTO** - Данные для прямоугольной области.
- **GeoParamsDTO** - Данные для радиуса.
- **ActivityController** - Контроллер для обработки запросов о деятельностях.
- **BuildingController** - Контроллер для обработки запросов о зданиях.
- **OrganizationController** - Контроллер для обработки запросов об организациях.
- **OrganizationFilterRequest** - Валидация.
- **Activity** - Модель для работы с данными деятельности.
- **Building** - Модель для работы с данными здания.
- **Organization** - Модель для работы с данными организации.
- **OrganizationActivity** - Модель для работы с данными деятельностей организации.
- **OrganizationPhone** - Модель для работы с данными телефонов организации.
- **2025_08_02_104646_create_buildings_table** - Миграция для создания таблицы зданий.
- **2025_08_02_104654_create_activities_table** - Миграция для создания таблицы деятельностей.
- **2025_08_02_104705_create_organizations_table** - Миграция для создания таблицы организаций.
- **2025_08_02_104710_create_organization_phones_table** - Миграция для создания таблицы телефонов организации.
- **2025_08_02_104716_create_organization_activities_table** - Миграция для создания таблицы деятельностей организации.
- **ActivitySeeder** - Сидер для заполнения таблицы деятельностей.
- **BuildingSeeder** - Сидер для заполнения таблицы зданий.
- **OrganizationSeeder** - Сидер для заполнения таблицы организаций.
- **ActivityApiTest** - Проверка работы api деятельности.
- **ApiAuthTest** - Проверка работы api ключа.
- **BuildingApiTest** - Проверка работы api зданий.
- **OrganizationApiTest** - Проверка работы api организаций.
- **api** - роуты.
- **Dockerfile** — Конфигурация для создания контейнера Docker.
- **docker-compose.yml** — Настройки для запуска контейнеров с помощью Docker Compose.
- **.env** — Файл с переменными окружения для настройки конфигураций.
- **README.md** — Описание проекта, инструкция по установке и запуску.



## Эндпоинты

### Получение организации по идентификатору
GET  http://localhost:95/api/organizations/1

### Получение организации и фильтрация
GET  http://localhost:95/api/organizations

GET  http://localhost:95/api/organizations?filter[name]=Рога По имени

GET  http://localhost:95/api/organizations?filter[building_id]=5 По зданию

GET  http://localhost:95/api/organizations?filter[activity_id]=3 По деятельности

GET  http://localhost:95/api/organizations?filter[activity_id]=3&include_descendants=true По деятельности c дочерними

GET  http://localhost:95/api/organizations?filter[radius]=1&latitude=55.75&longitude=37.61&radius=10по радиусу

GET  http://localhost:95/api/organizations?filter[area]=1&min_lat=55.7&max_lat=55.8&min_lng=37.5&max_lng=37.7 по прямоугольной области


## Инструкция по запуску

1. Клонирование репозитория
   git clone git@github.com:cH1NESY/REST-API-reference.git
   cd REST-API-reference

2. Запуск контейнеров
   docker-compose up -d --build

3. Установите связь с бд
   Database->"+"->Data Source->PostgreSQL.
   ввод пользователя, пароля, порта и названия бд:
   DB_PORT=54400
   DB_DATABASE=postgres
   DB_USERNAME=dbuser
   DB_PASSWORD=dbpwd
   Test connection->Apply->Ok

4. Выполнение миграции
   Вводим в терминал:
   docker exec -it php-fpm-ref bash
   в контейнере запускаем миграцию:
   php artisan migrate

5. Заполнение бд данными
   Оставаясь в контейнере:
   php artisan db:seed

6. Запуск api тестов
   Оставаясь в контейнере:
   php artisan test



# REST-API-reference
