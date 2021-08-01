<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About BookingApp

BookingApp is a free booking application with an easy-to-use interface. I believe in providing the best experience when using the application.

## Install your own
Download the copy to your server
```
git clone git@github.com:byronfichardt/bookingApp.git
```
 then run the 
```
composer install
cp .env.example .env
php artisan key:generate
```
make sure to set your database variables
and also set the following

```
ADMIN_USER_EMAIL=
ADMIN_PASSWORD=
```

Make sure to add the following 
Email credentials
Google calender details
Address info

```
CO_ADDRESS=
ADDRESS=
CITY=
ZIP=

GOOGLE_CLIENT_ID=
GOOGLE_PROJECT_ID=
GOOGLE_CLIENT_SECRET=

MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_NAME=
```

then you can run 
```
php artisan migrate
```

all thats left is 
```
php artisan serve
```

## License

The BookingApp is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
