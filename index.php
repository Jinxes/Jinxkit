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
use Jinxkit\Library\HttpException;

class User
{
    public function get()
    {
        echo 2122;
    }

    public function test2($num)
    {
        echo $num;
    }
}

class Midtest
{
    public function entry()
    {
        if (array_shift($this->args) == 1) {
            throw new HttpException(405);
        } else {
            echo 'hahaha';
        }
    }
}

class Midtest2
{
    public function entry()
    {
        if (array_shift($this->args) == 1) {
            throw new HttpException(405);
        } else {
            echo 'hahaha';
        }
    }
}
Route::config([
    'midwareEntry' => 'entry'
]);

Route::group('api')->match(function(Router $router) {
    $field2 = $router->restful('user', User::class)->match(function(Router $router1) {
        $router1->get('testGet', User::class, 'get')
            ->setName('testGet')
            ->setMidware([Midtest2::class]);
    });
})->setMidware([Midtest::class]);

// Route::get('user2/:num', User::class, 'test2')
// ->setMidware([Midtest::class])
// ->setName('user2');

// Route::get('user3/:num', function (User $user, $num) {
//     $user->get();
// })
// ->setName('user3');
header('Content-Type: application/json');
Route::start();
