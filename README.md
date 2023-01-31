# Генератор чисел

### Как развернуть
Необходима операционная система Ubuntu / MacOS  
И установленный Docker, docker-compose

Выполним команды:
1. Установим файлы конфигурации, для локального использования: `cp .env.local .env && cp docker-compose.yml.local docker-compose.yml`  
2. Запускаем docker-compose build (там все скрипты, в тч миграции): `docker-compose up -d`  

Проект доступен по адресу `http://127.0.0.1:82`  

### Методы API
GET `/generate`  
Пример ответа:  
```json
{
"id": "af0800e6-aaa1-4dd3-b263-34d447e58ca4",
"number": 3067
}
```

GET `/retrieve/:id`  
Пример ответа:
```json
{
"id": "af0800e6-aaa1-4dd3-b263-34d447e58ca4",
"number": 3067
}
```
Или:  
```json
{
"error": "Number with this id doesn't exist"
}
```

