## About BookingApp

BookingApp is a free booking application with an easy-to-use interface. I believe in providing the best experience when using the application.

##Install your own
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
