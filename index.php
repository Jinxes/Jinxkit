<?php
// require 'vendor/autoload.php';

spl_autoload_register(function ($class) {
    $path = str_replace('Jinxkit\\', '', $class);
    $path = strtr($path, '\\', '/');
    require $path . '.php';
});

use Jinxkit\Library\Storage;
use Jinxkit\Library\Field;
use Jinxkit\Library\FieldFactory as Router;
use Jinxkit\Route;

class User
{
    public function get()
    {
        echo 222;
    }

    public function test2($num)
    {
        echo $num;
    }
}

Route::group('api', function(Router $router) {
    $field2 = $router->restful('user', User::class, function(Router $router1) {
        $field1 = $router1->get('testGet', User::class)->setName('testGet')->setMidware([
            User::class
        ]);
        $field3 = $router1->delete('testGet', function() {
            return 11;
        })->setName('testGet');
    });
});

Route::get('user2/:num', User::class, 'test2')->setName('user2');

Route::start();
