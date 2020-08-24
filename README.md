# Instructions

1. Clone this repository on your local machine with `git clone https://github.com/felix1234567890/php_zadatak.git`
2. Rename .env.example to .env and fill in db options. Make sure if using Docker that DB_HOST is equal to container name for database
3. Run `docker-compose build app`
4. Run `docker-compose up -d`
5. Run `docker-compose exec app composer install`
6. Run `docker-compose exec app php artisan key:generate`
7. Run `docker-compose exec app php artisan migrate`
8. Run `docker-compose exec app npm install && npm run dev`
9. Open application on `http://localhost:8000`
