Library API

Описание

Этот проект — API для управления библиотекой. Включает регистрацию пользователей, аутентификацию, управление книгами и выдачу книг пользователям.

Запуск проекта

1️⃣ Установка зависимостей

Убедитесь, что у вас установлен PHP, Composer и Laravel.

composer install

2️⃣ Создание файла конфигурации

cp .env.example .env

Отредактируйте .env файл, установив настройки подключения к базе данных.

3️⃣ Генерация ключа приложения

php artisan key:generate

4️⃣ Настройка базы данных

php artisan migrate --seed

5️⃣ Генерация JWT-секрета (если используется JWT)

php artisan jwt:secret

6️⃣ Запуск локального сервера

php artisan serve

Теперь API доступно по адресу: http://127.0.0.1:8000

Тестирование API

🔹 Регистрация пользователя

Перейдите в норень проекта и введите
curl -X POST http://127.0.0.1:8000/register -H "Content-Type: application/json" -d '{"name": "User", "email": "user@example.com", "password": "password"}'

🔹 Регистрация библиотекаря
    php artisan librarian:register
    
🔹 Запуск тестов

php artisan test

Проверка Созданного Библиотекаря:

php artisan tinker

В консоли Tinker выполните следующий код:

App\Models\Librarian::all();

Запустите команду для авторизации библиотекаря:

curl -X POST http://localhost:8000/librarian/login \
-H "Content-Type: application/json" \
-d '{"email": "librarian@example.com", "password": "password"}'

Сохраните токен доступа из ответа.
Протестируйте маршрут, требующий аутентификации библиотекаря:

curl -X POST http://localhost:8000/books \
-H "Authorization: Bearer YOUR_LIBRARIAN_JWT_TOKEN" \
-H "Content-Type: application/json" \
-d '{"title": "New Book", "description": "This is a new book", "total_copies": 3}'

📚 Основные маршруты API

Метод

URL

Описание

POST

/register

Регистрация пользователя

POST

/login

Авторизация пользователя

POST

/logout

Выход (нужен токен)

GET

/books

Список доступных книг

POST

/books/{id}/borrow

Взять книгу в аренду

POST

/books/{id}/return

Вернуть книгу

💡 Примечание: Некоторые маршруты требуют аутентификации через Bearer-токен.

Теперь ваш проект готов к работе! 🚀

