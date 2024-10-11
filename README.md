# libiru
Онлай библиотека литературы

# Стек

* Laravel 11;
* Laravel Sail;
* Laravel Pint;
* Laravel Reverb;
* Laravel Sanctum;
* Laravel Telescope;
* Spatie Laravel Query Builder
* Pest;
* Larastan;
* PostgreSQL;
* PgBouncer;

# API

Каждый запрос должен принимать `Header`, для получения данных в формате json:

```
Accept — application/json
```

Запрос может принимать `Accept-Language` для смены языка.

Доступные значения:
1. en (По-умолчанию)
2. ru

# Auth

## Registration

```
POST api/v1/auth/registration
```

Регистрирует пользователя.

Принимает:
* name — имя, мин. 6, макс, 48, уникальное, обязательно
* email — почта, уникальное, обязательно
* password — пароль, базовые правила для пароля, обязательно
* password_confirmation — подтверждение пароля, обязательно

Возвращает:
```json
{
    "token": "---token"
}
```

### Login

```
POST api/v1/auth/login
```

Авторизация.

Принимает:
* email — почта, обязательно
* password — пароль, обязательно

Возвращает:
```json
{
    "token": "---token"
}
```

## Logout

```
POST api/v1/auth/logout
```

Выход пользователя.

Возвращает статус 204.

## Notifications

Требует токен

Возвращает список уведомлений текущего пользователя.

...
