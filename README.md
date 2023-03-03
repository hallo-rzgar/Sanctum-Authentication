

    
:- postman collections are atached:

:- to run that application you have to follow that comands:

composer install

get a copy from .env.example rename as .env and save you MySql server config

php artisan migrate

to get fake date run

php artisan db:seed


php artisan serve

//export employe as json
php artisan export:employees

//export database
php artisan export:database
if it not work you must set mysqldump as a global variable
