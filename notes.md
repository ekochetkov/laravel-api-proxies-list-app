# Install JWT

composer require php-open-source-saver/jwt-auth

To config/app.php:
PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider::class,

php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"

php artisan jwt:secret

# Migrate

php artisan migrate

# Integrate JWT

Add methods to User;

Modify config/auth.php

Add routes to auth;

php artisan make:controller AuthController
