Address Book - Symfony 3.4
========================

This project is about developing an address book in which you can add, edit and delete entries. The project should also have an overview of all contacts.

**Project Requirements**  
The address book should contain the following data:
* Firstname
* Lastname
* Street and number
* Zip
* City
* Country
* Phonenumber
* Birthday
* Email address
* Picture (optional)

The project must use the following techniques:
* Symfony 3.4
* Doctrine with SQLite
* Twig
* PHP 7.4+

## Install and run on local machine

Clone repository local.
```shell script
git clone git@github.com:nitin33singh/addressbook-symfony3.4.git
```

Using Composer in root of the project folder install and update required packages and dependencies.
```shell script
composer install
composer update --prefer-dist
```
Create a blank file for SQLITE db
```shell script
mkdir var/data
touch var/data/data.sqlite
```
Create database schema
```shell script
php bin/console doctrine:schema:update --force
``` 
Auto generating test data on the database using data fixtures.
```shell script
php bin/console doctrine:fixtures:load
```
Start server local and serve pages
```shell script
php bin/console server:run
```
Open in browser 'http://127.0.0.1:8000'.

Enjoy the simple address book!

[1]:  https://symfony.com/doc/3.4/setup.html
[6]:  https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
[7]:  https://symfony.com/doc/3.4/doctrine.html
[8]:  https://symfony.com/doc/3.4/templating.html
[9]:  https://symfony.com/doc/3.4/security.html
[10]: https://symfony.com/doc/3.4/email.html
[11]: https://symfony.com/doc/3.4/logging.html
[13]: https://symfony.com/doc/current/bundles/SensioGeneratorBundle/index.html
[14]: https://symfony.com/doc/current/setup/built_in_web_server.html
[15]: https://symfony.com/doc/current/setup.html
