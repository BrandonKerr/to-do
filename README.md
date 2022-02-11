## To-Do App

This is a simple demo app where users can manage to-do lists. This was built with Laravel 8.x (PHP 7.4), the [Laravel Breeze](https://laravel.com/docs/8.x/starter-kits#laravel-breeze) package, and styled with [Tailwind CSS](https://tailwindcss.com/).

This app was built to address the following functionality needs:
-  User authentication and authorization
    - Two roles: user and administrator
    - Administrator has full permissions
- User can only create and delete their own to-do items
- Users can create a to-do list
- Users can mark to-do items as done
- Users can delete to-do items
- Administrators can see all users to-do items, including deleted to-do items

## Getting Started

All you should need to do is clone the repo, configure your `.env`, and initialize the project (eg. `composer install`, `php artisan migrate`, etc).
The app was developed with a MySQL database, but should work on any standard Laravel-supported database type.

## Users

**Please note** that I set up the auth system to make use of email verification, as would normally be my process for a public-facing registration system. This means that emails will be sent out during registration. I used [MailHog](https://github.com/mailhog/MailHog) to catch generated emails, but you are welcome to configure your mail settings in any way you please (or just ignore the fact the emails would be generate and `tinker` users to set the `email_verified_at` field).

In order to get things going quickly, you can use the provided database seeders by running `php artisan db:seed` after migration (or `php artisan migrate:fresh --seed` to complete it all in one step). This will generate two users:
- admin@todo.com
    - password: admin
- user@todo.com
    - password: user

The seeder also creates a few sample to-do tasks and their associated checklists for the standard user.