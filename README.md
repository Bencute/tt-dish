# Модуль блюд
## Установка
1. Клонировать репозиторий 
    ```
    git clone https://github.com/Bencute/tt-dish.git
    ```
2. Установить пакеты composer 
    ```
    composer install
    ```
3. Настроить сервер на хосты для `backend` и `frontend`
4. Сконфигурировать настройки окружения в `/environments` и инициализировать окружение командой консоли 
    ```
    init
    ```
5. Применить миграции:
   - Базовые миграции 
     ```
     yii migrate
     ```
   - Миграции модуля 
     ```
     yii migrate --migrationPath=/path to project/modules/dish/migrations
     ```
6. Перейти в админ панель:
    - блюд: `http://backend/index.php?r=dish%2Fdish`
    - ингредиентов: `http://backend/index.php?r=dish%2ingredient`
7. Перейти на фронт `http://frontend`

## Структура директорий

```
modules/                    содержит модули
    dish/                   модуль блюд
        controllers/        контролеры модуля
            backend/        контролеры админ панели
            frontend/       контролеры для фронта
        migrations/         миграции модуля
        model/              модели модуля
            ar/             модели ActiveRecord
            forms/          модели форм
        validators/         валидаторы модуля
        views/              представления модуля
            backend/        представления админ панели
            frontend/       представления для фронта
```
