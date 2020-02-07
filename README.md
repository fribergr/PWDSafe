PWDSafe
=======
[![Build Status](https://travis-ci.org/PWDSafe/PWDSafe.svg?branch=master)](https://travis-ci.org/PWDSafe/PWDSafe)

Prerequisite
-----------
* Webserver with support for PHP
* Access to a MySQL-database
* Composer

Installation
------------
* Run `composer install`
* Run `npm install && npm run prod`
* Copy .env.example to .env and modify it accordingly
* Run the database migrations with `php artisan migrate`
* Configure your webserver so it points to `public/`-folder. Make sure to redirect all requests where the file requested does not exist to index.php. Example configuration for nginx:
```
location / {
    try_files $uri $uri/ /index.php?$args;
}
```
* Browse to your site, register and login.
* Enjoy!

This application uses
---------------------
The following libraries/frameworks is used by the application:
- Laravel - https://laravel.com/
- Bootstrap - https://github.com/twbs/bootstrap
- JQuery - https://github.com/jquery/jquery

