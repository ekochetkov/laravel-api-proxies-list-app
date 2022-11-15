FROM php:8.1.12-apache-bullseye

ADD . /var/www
ADD ./public /var/www/html

# Add the user UID:1000, GID:1000, home at /app
RUN groupadd -r app -g 1000 && useradd -u 1000 -r -g app -m -d /app -c "App user" app

RUN chown -R 1000:1000 /var/www/storage /var/www/database

WORKDIR /var/www

RUN a2enmod rewrite

RUN php artisan config:clear && php artisan config:cache

USER 1000
