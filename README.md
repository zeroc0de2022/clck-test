
# 📎 Laravel URL Shortener API

RESTful-сервис для сокращения URL-адресов на Laravel 10 (аналог https://clck.ru/). Без фронтенда — только JSON API. Позволяет создавать короткие ссылки, выполнять перенаправления, сохранять статистику переходов и получать её через защищённый токеном API.

---

## Установка и запуск

### 1. Установка зависимостей

```bash
git clone https://github.com/zeroc0de2022/clck_test.git
cd clck
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Настройка базы данных

Создайте рабочую базу данных в MySQL:

```sql
CREATE DATABASE clck;
```

Затем настройте `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clck
DB_USERNAME=ИМЯ_ПОЛЬЗОВАТЕЛЯ
DB_PASSWORD=ПАРОЛЬ_ПОЛЬЗОВАТЕЛЯ

API_STATS_TOKEN=BEARER_ТОКЕН
```

### 3. Миграции

```bash
php artisan migrate
```

---

## Защита статистики

Для доступа к статистике используйте Bearer-токен, заданный в `.env`:

```
API_STATS_TOKEN=BEARER_ТОКЕН
```

---

## Тестирование

1. Создайте тестовую базу данных в MySQL:
```sql
CREATE DATABASE clck_test;
```

2. Добавьте тестовую базу в файл `phpunit.xml`:
```xml
<env name="DB_DATABASE" value="clck_test"/>
```

3. Выполните миграции и тесты:
```bash
php artisan migrate:fresh --env=testing
php artisan test
```

---

## 📡 API Endpoints

### 🔹 1. Создание сокращённой ссылки

**POST** `/api/links`

Тело запроса:

```json
{
  "original_url": "https://test.com/long/url"
}
```

Ответ:

```json
{
  "short_url": "http://mysite.ru/AbC123",
  "code": "AbC123",
  "original_url": "https://test.com/long/url",
  "created_at": "2025-06-29T15:20:00.000000Z"
}
```

---

### 🔹 2. Переход по сокращённой ссылке

**GET** `/{code}`

Пример:  
`GET http://mysite.ru/AbC123`

Ответ: `302 Redirect` на оригинальный URL.

---

### 🔹 3. Получение статистики

**GET** `/api/links/{code}/stats`  
Требуется заголовок:

```
Authorization: Bearer BEARER_ТОКЕН
```

Пример ответа:

```json
{
  "original_url": "https://test.com/long/url",
  "short_code": "AbC123",
  "created_at": "2025-06-29T15:20:00.000000Z",
  "clicks_count": 4
}
```

---

## Дополнительно

- Все ответы в формате JSON
- Валидация входных данных
- Защита API rate limiting: 120 запросов в минуту
- При превышении лимита: `429 Too Many Requests`

---

## Структура проекта

- `app/Http/Controllers/LinkController.php` — контроллер API
- `app/Models/Link.php`, `LinkVisit.php` — модели
- `database/migrations/` — таблицы links и link_visits
- `app/Http/Middleware/EnsureApiToken.php` — защита статистики
- `tests/Feature/ClckUrlTest.php` — тесты

---
