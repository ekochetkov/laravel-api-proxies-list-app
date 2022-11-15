FROM php:8.1.12-apache-bullseye

ADD . /var/www
ADD ./public /var/www/html

# Add the user UID:1000, GID:1000, home at /app
RUN groupadd -r app -g 1000 && useradd -u 1000 -r -g app -m -d /app -c "App user" app

WORKDIR /var/www

RUN touch database/database.sqlite

RUN chown -R 1000:1000 /var/www/storage /var/www/database

RUN a2enmod rewrite

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

RUN apt update && apt install -y git unzip

RUN composer update

RUN echo "DB_CONNECTION=sqlite" >> .env

RUN echo "APP_KEY=" >> .env

RUN php artisan jwt:secret

RUN php artisan key:generate

RUN php artisan config:clear && php artisan config:cache

RUN php artisan migrate --force

USER 1000

