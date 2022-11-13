# Install JWT

composer require php-open-source-saver/jwt-auth

To config/app.php:
PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider::class,

php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"

php artisan jwt:secret

