## About The Project

We will build a mini, yet complete, laravel API. It will consist of the following:
- User authentication using Passport
- Confirmation email after registration
- Processing multiple data with queue and jobs
- Cron jobs to perform recurrent operations
- Clean Code
- More and more will be added

## Setting Up

Please make sure you have composer installed on your system and a DBMS like phpmyadmin etc
- Clone the repo and on your local (machine) cd into the repo folder
- Copy .env.example file into .env
- Set the database credentials in .env file
- Run `composer install` to install the dependencies and packages
- Run `php artisan migrate` to create the required database tables
- Run `composer install` to install the dependencies and packages
- Run `php artisan passport:install` to install passport package

### Packages

This is a list of all packages installed in the project (will be updated later):
- Laravel/Passport
- Doctrine/Dbal


* Please note this file will be updated as time goes on