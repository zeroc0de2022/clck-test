# Тестовое задание

## Описание задачи

Необходимо реализовать RESTful API сервис для сокращения URL-адресов (аналог https://clck.ru/) без фронтенд-части. Сервис должен предоставлять следующие возможности:

1. Создание сокращенной ссылки  
2. Перенаправление на исходный адрес при переходе на сокращенную ссылку  
3. Сохранение истории переходов по сокращенной ссылке для ведения статистики  
4. Просмотр статистики переходов

## Требования

1. Реализовать API без фронтенда (только JSON-ответы)  
2. Реализовать генерацию уникального кода для ссылки длиной 6 символов (можно использовать хеш или случайную строку)  
3. Сохранять дату создания ссылки  
4. Хранить IP-адрес пользователя при переходе по ссылке (для статистики)  
5. Реализовать rate limiting для API (например, 120 запросов в минуту)  
6. Реализовать метод получения статистики для каждой ссылки (отдавать количество переходов по сокращенной ссылке и дату ее создания). Данный метод сделать непубличным. Реализовать защиту с помощью Bearer токена.  
7. Реализовать базовую валидацию и обработку ошибок  
8. Написать тесты для основных функциональностей  
9. Загрузить результат выполнения в git репозиторий

## Что оценивается

1. Корректность реализации функционала и его полная работоспособность  
2. Качество кода (чистота, структура, соответствие PSR)  
3. Использование возможностей Laravel (Eloquent, миграции, роуты и т.д.)  
4. Наличие тестов  
5. Документация в README.md (как запустить проект, примеры запросов)

## Дополнительно

1. Наименования всех API Endpoints сделать на усмотрение исполнителя, но придерживаться при этом общепринятых правил.
