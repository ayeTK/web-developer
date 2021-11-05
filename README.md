# Getting started

## Installation

In this project, Laravel v8.68.1 (PHP v7.4.24) and Maatwebsite v3.1 is used.

----------

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)

Clone the repository

    git clone https://github.com/ayeTK/web-developer.git


Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the local server at http://localhost:8000
You can also access the hosting server at https://web-developer-assignment.000webhostapp.com/

**TL;DR command list**

    git clone https://github.com/ayeTK/web-developer.git
    composer install
    cp .env.example .env
    php artisan key:generate
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate
    php artisan serve

----------

## Folders

- `app` - Contains all the Eloquent models
- `app/Http/Controllers/Api` - Contains all the api controllers
- `app/Exports` - Contains the files implementing the export feature
- `app/Models` - Contains the data model
- `config` - Contains all the application configuration files
- `database/migrations` - Contains all the database migrations
- `database/seeders` - Contains the database seeder
- `routes` - Contains all the api routes defined in api.php file
- `resources/views` - Contains all the view files
- `resources/views/exports` - Contains all the csv export view files

## Environment variables

- `.env` - Environment variables can be set in this file

***Note*** : You can quickly set the database information and other variables in this file and have the application fully working.

----------

# Testing Project

Run the laravel development server

    php artisan serve

The project can now be accessed at

    http://localhost:8000

Request headers

| **Required** 	| **Key**              	| **Value**            	|
|----------	|------------------	|------------------	|
| Yes      	| Content-Type     	| application/json 	|
| Yes      	| X-Requested-With 	| XMLHttpRequest   	|

