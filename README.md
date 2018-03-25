# Jinxes/Jinxkit
A minimal restful router

This is a mini php route library. At the core of Jinxkit is a RESTful and resource-oriented microkernel. It also integrates a simple filter-controller system and a DI (dependency injection) container.

It is suitable as the basis for some customized web frameworks.

## Supported PHP versions
I was tested on 5.5 and 7.1 (linux/windows), it work properly

## Installation
```
composer require jinxes/jinxkit dev-master
```
## Get start
* require the Route
```php
require 'vendor/autoload.php';

use Jinxes\Jinxkit\Route;
```

* define a controller and connect to the router
```php
class User
{
    public function say($lang)
    {
        echo 'hello ' . $lang;
    }
}

Route::get('sayhello/:str', SayHello::class, 'say');

Route::start();
```
## Open the development Server for test
```
php -S localhost:8080
```
and visit: [http://localhost:8080/index.php/sayhello/world](http://localhost:8080/index.php/sayhello/world)
<br />will show `hello world`