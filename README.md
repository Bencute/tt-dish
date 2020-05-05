#Модуль блюд
##Установка
1. Клонировать репозиторий 
    ```
    git clone https://github.com/Bencute/tt-dish.git
    ```
2. Настроить сервер на хосты для `backend` и `frontend`
3. Сконфигурировать настройки окружения в `/environments` и инициализировать окружение командой консоли 
    ```
    init
    ```
4. Применить миграции:
   - Базовые миграции 
     ```
     yii migrate
     ```
   - Миграции модуля 
     ```
     yii migrate --migrationPath=/path to project/modules/dish/migrations
     ```
5. Перейти в админ панель:
    - блюд: `http://backend/index.php?r=dish%2Fdish`
    - ингредиентов: `http://backend/index.php?r=dish%2ingredient`
6. Перейти на фронт `http://frontend`

##Структура директорий

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
