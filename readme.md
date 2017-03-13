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
* Run `composer install` in the root-directory
* Copy config.inc.default.php to config.inc.php and modify it
* Add a phinx configuration (`vendor/bin/phinx init .`) and modify it
* Run the database migrations with `vendor/bin/phinx migrate -e development`
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
The following libraries is used by the application:
* Bootstrap - https://github.com/twbs/bootstrap
* JQuery - https://github.com/jquery/jquery
* ToroPHP - https://github.com/anandkunal/ToroPHP
* Twig - https://github.com/twigphp/Twig
* Phinx - https://github.com/robmorgan/phinx
