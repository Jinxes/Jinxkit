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
* Open the development Server for test
```
php -S localhost:8080
```
and visit: [http://localhost:8080/index.php/sayhello/world](http://localhost:8080/index.php/sayhello/world)
<br />will show:
```
hello world
```

* add some filters
define a filter class and add for the SayHello router
```php
class Filter
{
    public function __invoke()
    {
        $lang = array_shift($this->args);
        if ($lang === 'world') {
            echo 'filter pass <br />';
        } else {
            return false;
        }
    }
}

Route::get('sayhello/:str', SayHello::class, 'say')->filter([Filter::class]);
```
will show:
```
filter pass 
hello world
```
* define a RESTful router
```php
class SayHello
{
    public function get()
    {
        echo 'this is a GET request';
    }

    public function say($lang)
    {
        echo 'hello ' . $lang;
    }
}

Route::restful('api', SayHello::class, function($router) {
    $router->get('sayhello/:str', SayHello::class, 'say');
});

Route::start();
```
visit: [http://localhost:8080/index.php/api/sayhello/world](http://localhost:8080/index.php/api/sayhello/world)
```
hello world
```
visit: [http://localhost:8080/index.php/api](http://localhost:8080/index.php/api)
```
this is a GET request
```

## dependency injection for filter and controller
* Define a Service class
```php
class SayService
{
    public function hello()
    {
        echo 'hello ';
    }
}
```
service class is some singleton objects maintenance by DI system<br />
and the construct of services also can be injected

* inject services
```php
class SayHello
{
    public function say(SayService $sayService, $lang)
    {
        echo $sayService->hello() . $lang;
    }
}
```
You must put the service in front of all the parameters and declaring with service name<br />
visit: [http://localhost:8080/index.php/api/sayhello/world](http://localhost:8080/index.php/api/sayhello/world)
```
hello world

#### This library will be update by Jinxes<blldxt@yahoo.com>