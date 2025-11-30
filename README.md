<p align="center">
    <h1 align="center">📌 Тестовое задание (Yii2, REST API)</h1>
    <br>
</p>

#### Задача:
Разработать небольшое RESTful приложение на Yii2 для управления библиотекой книг.


#### Функционал
🔹 Сущности
- Пользователь (User)
- Книга (Book)

#### API эндпоинты

```
Пользователи:
    POST /users — регистрация нового пользователя (логин, пароль, email). 
    POST /auth/login — авторизация (получение JWT токена). 
    GET /users/{id} — просмотр профиля (только для авторизованных).
Книги: 
    GET /books — список всех книг (с пагинацией). 
    POST /books — добавить книгу (только авторизованный пользователь). 
    GET /books/{id} — получить информацию о книге. 
    PUT /books/{id} — обновить данные книги (только авторизованный). 
    DELETE /books/{id} — удалить книгу (только авторизованный).
```

 
#### Требования
 - Авторизация через JWT. 
 - Ответы должны быть в формате JSON (RESTful стиль). 
 - Добавить валидацию для всех входящих данных.

🔹 Цель
Это задание проверяет:
Знание Yii2 (модели, контроллеры, REST). 
Умение работать с аутентификацией. 
Понимание REST API (CRUD, авторизация, валидация). 
Код-стиль и структурированность проекта.


## Инструкция запуска
 - запустить миграции || импортировать sql по пути /api/sql/tw_go_solution.sql
 - примеры запросов:
#### 1. Авторизация
```
curl --location 'http://localhost:8888/api/auth/login' \
--header 'Content-Type: application/json' \
--header 'Cookie: _csrf-api=QvDnebLdqBrHLtU3Qlzr4df6uxHXtXfX' \
--data '{
    "login": "test123",
    "password": "test123"
}'
```
#### 2. Добавление книги
```
curl --location 'http://localhost:8888/api/books' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0Ojg4ODgiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0Ojg4ODgiLCJqdGkiOiI2OTI4OWYzOGQ2NjBmMS41NjU0MTY3OSIsImlhdCI6MTc2NDI2OTg4MCwiZXhwIjoxNzY0MzU2MjgwLCJ1aWQiOjIxfQ.7UEyLXPUFMbNZuQnAOmciT1Ortx2YCFh6trjFeLZ4rQ' \
--header 'Cookie: _csrf-api=esw6ZP6qaLKYjZfkENXPOvr56pagUjQ6' \
--data '{
    "title": "buety book",
    "author": "A.L Linkton",
    "published_year": 2010
}'
```
#### 3. Просмотр книг постранично
```
curl --location 'http://localhost:8888/api/books?limit=2&page=1' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0Ojg4ODgiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0Ojg4ODgiLCJqdGkiOiI2OTI4OWYzOGQ2NjBmMS41NjU0MTY3OSIsImlhdCI6MTc2NDI2OTg4MCwiZXhwIjoxNzY0MzU2MjgwLCJ1aWQiOjIxfQ.7UEyLXPUFMbNZuQnAOmciT1Ortx2YCFh6trjFeLZ4rQ' \
--header 'Cookie: _csrf-api=esw6ZP6qaLKYjZfkENXPOvr56pagUjQ6' \
--data ''
```