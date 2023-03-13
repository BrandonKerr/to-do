## To-Do App
<p>
<img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
<img src="https://img.shields.io/codecov/c/github/BrandonKerr/to-do" alt="Codecov Status">
</p>
This is a simple demo app where users can manage to-do lists. This was originally built with Laravel 8.x (PHP 7.4), the [Laravel Breeze](https://laravel.com/docs/8.x/starter-kits#laravel-breeze) package, and styled with [Tailwind CSS](https://tailwindcss.com/). It has sicne been updated to Laravel 10.x and PHP 8.1.

This app was built to address the following functionality needs:
-  User authentication and authorization
    - Two roles: user and administrator
    - Administrator has full permissions
- User can only create and delete their own to-do items
- Users can create a to-do list
- Users can mark to-do items as done
- Users can delete to-do items
- Administrators can see all users' to-do items, including deleted to-do items

## Getting Started

All you should need to do is clone the repo, configure your `.env`, and initialize the project (eg. `composer install`, `php artisan migrate`, etc).
The app was developed with a MariaDB (MySQL) database, but should work on any standard Laravel-supported database type.

### Docker

If you would like to use Docker containers to run this app, you can make use of the Docker configuration provided in this project. Using the provided [docker-compose.yml](https://github.com/BrandonKerr/to-do/blob/master/docker-compose.yml), you will have three containers:
- todo-app
    - runs PHP FPM, providing the ability to run artisan commands and more
    - uses a custom Docker image built from the [Dockerfile](https://github.com/BrandonKerr/to-do/blob/master/Dockerfile) contained within this project
        - if you make any adjustments to the Dockerfile, don't forget to rebuild the image with `docker-compose build app`
- todo-db
    - the MariaDB database
    - uses the `DB_` values in your `.env` file to create the database and user
    - port 3306 is forwarded to your localhost so you can connect with your favourite database management tool
    - **note:** set your `DB_HOST=db` value in your .env to use the db service, and set a value for the `DB_PASSWORD=`
- todo-nginx
    - an nginx service running on [Alpine](https://wiki.alpinelinux.org/wiki/Main_Page), to serve up the PHP application

Docker notes:
- `docker-compose up -d` to get docker running in the background
- to run any commands within the app container: `docker-compose exec app ____`.
    - e.g. `docker-compose exec app php artisan migrate --seed`
- as a convenient way to stay in the app container, you can type `docker exec -it todo-app /bin/bash` where your terminal will stay running as the `todo` user created by `Dockerfile`
    - to end the session, simply type `exit`
- `docker-compose down` to stop and remove the containers

## Users

**Please note** that I set up the auth system to make use of email verification, as would normally be my process for a public-facing registration system. This means that emails will be sent out during registration. I used [MailHog](https://github.com/mailhog/MailHog) to catch generated emails, but you are welcome to configure your mail settings in any way you please (or just ignore the fact the emails would be generate and `tinker` users to set the `email_verified_at` field).

In order to get things going quickly, you can use the provided database seeders by running `php artisan db:seed` after migration (or `php artisan migrate:fresh --seed` to complete it all in one step). This will generate two users:
- admin@todo.com
    - password: admin
- user@todo.com
    - password: user

The seeder also creates a few sample to-do tasks and their associated checklists for the standard user.