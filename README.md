# AmarSteps quick start
Amar Step is principally a fitness App. It will record and analyze daily activities and habits to help maintain successful diet and lead healthy lifestyle.
##

## Requirements
* PHP >= 7.2
* cURL, mcrypt, zip, unzip, libmcrypt-dev, git Extensions
* [Apache HTTP](https://httpd.apache.org/download.cgi) or [Nginx](https://www.nginx.com/) server
* [MySQL](https://dev.mysql.com/) database
* [Composer](https://getcomposer.org/) php package manager
* [npm](https://www.npmjs.com/) node package manager

## Installation
    git clone git@github.com:NazmusShakib/amar-steps.git
Switch to the repo folder

    cd amar-steps

Install all the dependencies

    composer install && npm install

Build npm dependencies

    npm run dev

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate --seed

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone git@github.com:NazmusShakib/amar-steps.git
    cd amar-steps
    composer install && npm install
    npm run dev
    cp .env.example .env
    php artisan key:generate
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate --seed
    php artisan serve

## License

Cloudly©2019 licensed under the [MIT license](https://opensource.org/licenses/MIT).
