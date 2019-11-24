#!/bin/sh
cd app
composer install
cd..

docker-compose up -d
docker-compose exec php-fpm-app bash -c 'mkdir -p /var/www/.composer'
docker-compose exec php-fpm-app bash -c 'chown -R www-data:www-data /var/www/.composer'

docker-compose exec --user www-data php-fpm-app bash -c 'cd /app && composer install'

docker-compose exec php-fpm-app bash -c 'cd /app && php bin/console make:migration'
docker-compose exec php-fpm-app bash -c 'cd /app && php bin/console doctrine:migrations:migrate'
