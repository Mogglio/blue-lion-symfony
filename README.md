blue-lion
=========

To install project in local :

- install composer (if not install)
- composer install
- composer update
- composer dump-autoload

To generate BDD :

- update app/config/parameters.yml to set your mysql log
- php bin/console doctrine:schema:update --force

Start the project :

- php bin/console server:start
