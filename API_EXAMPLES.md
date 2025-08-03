# Примеры использования API

## Аутентификация

Все запросы к API требуют заголовок `X-API-Key`:

```bash
curl -H "X-API-Key: test-api-key-123" http://localhost:8080/api/endpoint
```

## Организации

### Получить организацию по ID
```bash
curl -H "X-API-Key: test-api-key-123" \
     http://localhost:8080/api/organizations/1
```

**Ответ:**
```json
{
  "id": 1,
  "name": "ООО \"Рога и Копыта\"",
  "building_id": 1,
  "building": {
    "id": 1,
    "address": "г. Москва, ул. Ленина 1, офис 3",
    "latitude": "55.7558",
    "longitude": "37.6176"
  },
  "phones": [
    {
      "id": 1,
      "phone_number": "2-222-222",
      "organization_id": 1
    },
    {
      "id": 2,
      "phone_number": "3-333-333",
      "organization_id": 1
    }
  ],
  "activities": [
    {
      "id": 1,
      "name": "Еда",
      "parent_id": null,
      "level": 1
    },
    {
      "id": 2,
      "name": "Мясная продукция",
      "parent_id": 1,
      "level": 2
    }
  ]
}
```

### Список организаций в здании
```bash
curl -H "X-API-Key: test-api-key-123" \
     http://localhost:8080/api/organizations/building/1
```

### Поиск организаций по названию
```bash
curl -H "X-API-Key: test-api-key-123" \
     "http://localhost:8080/api/organizations/search?name=Рога"
```

### Организации по виду деятельности
```bash
curl -H "X-API-Key: test-api-key-123" \
     http://localhost:8080/api/organizations/activity/1
```

### Организации по виду деятельности с потомками
```bash
curl -H "X-API-Key: test-api-key-123" \
     http://localhost:8080/api/organizations/activity/1/descendants
```

### Организации в радиусе от точки
```bash
curl -H "X-API-Key: test-api-key-123" \
     "http://localhost:8080/api/organizations/radius?latitude=55.7558&longitude=37.6176&radius=5"
```

### Организации в прямоугольной области
```bash
curl -H "X-API-Key: test-api-key-123" \
     "http://localhost:8080/api/organizations/area?min_lat=55.7500&max_lat=55.7600&min_lng=37.6000&max_lng=37.6200"
```

## Здания

### Список всех зданий
```bash
curl -H "X-API-Key: test-api-key-123" \
     http://localhost:8080/api/buildings
```

**Ответ:**
```json
[
  {
    "id": 1,
    "address": "г. Москва, ул. Ленина 1, офис 3",
    "latitude": "55.7558",
    "longitude": "37.6176",
    "organizations": [
      {
        "id": 1,
        "name": "ООО \"Рога и Копыта\"",
        "building_id": 1
      }
    ]
  },
  {
    "id": 2,
    "address": "г. Москва, ул. Тверская 10, офис 5",
    "latitude": "55.7600",
    "longitude": "37.6100",
    "organizations": [
      {
        "id": 2,
        "name": "ООО \"Молочный Мир\"",
        "building_id": 2
      }
    ]
  }
]
```

### Информация о здании
```bash
curl -H "X-API-Key: test-api-key-123" \
     http://localhost:8080/api/buildings/1
```

## Деятельность

### Список корневых видов деятельности
```bash
curl -H "X-API-Key: test-api-key-123" \
     http://localhost:8080/api/activities
```

**Ответ:**
```json
[
  {
    "id": 1,
    "name": "Еда",
    "parent_id": null,
    "level": 1,
    "children": [
      {
        "id": 2,
        "name": "Мясная продукция",
        "parent_id": 1,
        "level": 2,
        "children": [
          {
            "id": 8,
            "name": "Свинина",
            "parent_id": 2,
            "level": 3
          },
          {
            "id": 9,
            "name": "Говядина",
            "parent_id": 2,
            "level": 3
          }
        ]
      },
      {
        "id": 3,
        "name": "Молочная продукция",
        "parent_id": 1,
        "level": 2,
        "children": [
          {
            "id": 10,
            "name": "Молоко",
            "parent_id": 3,
            "level": 3
          },
          {
            "id": 11,
            "name": "Сыр",
            "parent_id": 3,
            "level": 3
          }
        ]
      }
    ]
  },
  {
    "id": 4,
    "name": "Автомобили",
    "parent_id": null,
    "level": 1,
    "children": [
      {
        "id": 5,
        "name": "Грузовые",
        "parent_id": 4,
        "level": 2,
        "children": [
          {
            "id": 12,
            "name": "Грузовики",
            "parent_id": 5,
            "level": 3
          }
        ]
      },
      {
        "id": 6,
        "name": "Легковые",
        "parent_id": 4,
        "level": 2,
        "children": [
          {
            "id": 13,
            "name": "Седаны",
            "parent_id": 6,
            "level": 3
          }
        ]
      },
      {
        "id": 7,
        "name": "Запчасти",
        "parent_id": 4,
        "level": 2,
        "children": [
          {
            "id": 14,
            "name": "Двигатели",
            "parent_id": 7,
            "level": 3
          },
          {
            "id": 15,
            "name": "Аксессуары",
            "parent_id": 7,
            "level": 3
          }
        ]
      }
    ]
  }
]
```

### Информация о виде деятельности
```bash
curl -H "X-API-Key: test-api-key-123" \
     http://localhost:8080/api/activities/1
```

### Дерево видов деятельности
```bash
curl -H "X-API-Key: test-api-key-123" \
     http://localhost:8080/api/activities/tree
```

## Примеры с использованием JavaScript

### Получение организаций
```javascript
fetch('http://localhost:8080/api/organizations/1', {
  headers: {
    'X-API-Key': 'test-api-key-123'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

### Поиск организаций
```javascript
fetch('http://localhost:8080/api/organizations/search?name=Рога', {
  headers: {
    'X-API-Key': 'test-api-key-123'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

### Геопоиск
```javascript
fetch('http://localhost:8080/api/organizations/radius?latitude=55.7558&longitude=37.6176&radius=5', {
  headers: {
    'X-API-Key': 'test-api-key-123'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

## Коды ошибок

- `401 Unauthorized` - Неверный или отсутствующий API ключ
- `404 Not Found` - Ресурс не найден
- `422 Unprocessable Entity` - Ошибка валидации параметров

## Ограничения

1. **Уровни деятельности** - максимально 3 уровня вложенности
2. **Геопоиск** - радиус в километрах, координаты в десятичном формате
3. **API ключ** - обязателен для всех запросов
4. **Лимиты** - в текущей версии лимиты не установлены 