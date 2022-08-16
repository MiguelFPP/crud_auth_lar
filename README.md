# API CRUD AUTH

this api uses authentication from laravel passport. [Repository](https://github.com/laravel/passport), [Documentation](https://laravel.com/docs/9.x/passport)

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/9.x)

Install all the dependencies using composer

    composer install   

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations & seeder (**Set the database connection in .env before migrating**)

    php artisan migrate:fresh --seed
    
 Run this command will create the encryption keys needed to generate secure access tokens.
 
    php artisan passport:install
    
 Run this command for create the simbolyc link
 
    php artisan storage:link

Start the local development server

    php artisan serve


**TL;DR command list**
    
    composer install    
    cp .env.example .env
    php artisan key:generate
    php artisan migrate:fresh --seed
    php artisan passport:install
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan serve

----------

## Folders

- `app` - Contains all the Eloquent models
- `app/Http/Controllers` - Contains all the controllers
- `app/Http/Requests` - Contains all the form requests
- `config` - Contains all the application configuration files
- `database/factories` - Contains the model factory for all the models
- `database/migrations` - Contains all the database migrations
- `database/seeds` - Contains the database seeder
- `routes` - Contains all the api routes defined in api.php file
- `tests` - Contains all the api tests

## Environment variables

- `.env` - Environment variables can be set in this file

***Note*** : You can quickly set the database information and other variables in this file and have the application fully working.

----------

# Testing

Run the laravel test 

    php artisan test

Run the laravel development server

    php artisan serve

