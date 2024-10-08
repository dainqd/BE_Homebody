1. cp .env.example .env
2. composer install
3. composer dump-autoload
4. composer update
5. php artisan migrate:fresh --seed
6. php artisan key:generate
7. php artisan vendor:publish --tag=laravel-pagination
8. php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
9. php artisan l5-swagger:generate
10. php artisan vendor:publish --provider "Tymon\JWTAuth\Providers\LaravelServiceProvider"
11. php artisan jwt:secret
12. php artisan storage:link
13. php artisan serve
