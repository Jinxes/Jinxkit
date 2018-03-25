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
```php
require 'vendor/autoload.php';

use Jinxes\Jinxkit\Route;
use Jinxes\Jinxkit\Library\HttpException;
```
