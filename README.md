## About Vms

# How To Install

## Step 1: Install Dependencies

Before installing the this project, we need to make sure that our system has all the required dependencies installed. We will need to install the following dependencies:

-   PHP 8.1 or higher
-   Composer
-   Node.js
-   NPM
-   git

## Step 2: Clone This app

To clone the app, following command:

```bash

```

## Step 3: Go To project directory and composer install

-   first go to the project directory

```bash
cd vms
```

-   Then copy the .env.example file to .env

```bash
cp .env.example .env
```

-   Then install composer

```bash
composer vms
composer install
```

-   then publish assets

```bash
php artisan storage:link
```

## Step 5: Config your database and assine to .env file

```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vms
DB_USERNAME=root
DB_PASSWORD=
```

-   then migrate and seed
-   перед выполнением необходимо чтобы все модули были активированы

```bash
php artisan migrate:fresh --seed
```

## Step 6: Now serve your application using php artisan serve command

```bash
 php artisan serve
```

```bash
php artisan config:cache
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```