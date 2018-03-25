<?php
// require 'vendor/autoload.php';

spl_autoload_register(function ($class) {
    $path = str_replace('Jinxes\\Jinxkit\\', '', $class);
    $path = strtr($path, '\\', '/');
    require $path . '.php';
});

use Jinxes\Jinxkit\Library\Storage;
use Jinxes\Jinxkit\Library\Field;
use Jinxes\Jinxkit\Library\FieldFactory as Router;
use Jinxes\Jinxkit\Route;
use Jinxes\Jinxkit\Library\HttpException;

class User
{
    public function get($num, $aa)
    {
        echo $aa;
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
        }
    }
}

class Midtest2
{
    public function entry()
    {
        if (array_shift($this->args) == 1) {
            throw new HttpException(405);
        }
    }
}

class Midtest3
{
    public function entry()
    {
        print_r(array_shift($this->args));
        // if (array_shift($this->args) == 1) {
        //     throw new HttpException(405);
        // }
    }
}

Route::config([
    'filterEntry' => 'entry'
]);

Route::group('api', function(Router $router) {
    $router->filter([Midtest3::class]);

    $field2 = $router->restful('user', User::class, function($router) {
        $router->get('testGet/:num/:str', User::class, 'get')
            ->setName('testGet')->filter([Midtest::class]);
    })->filter([Midtest2::class]);
});

// Route::get('user2/:num', User::class, 'test2')
// ->setName('user2');

Route::get('user3/:num', function (User $user, $num) {
    $user->get($num);
})->filter([Midtest3::class])
->setName('user3');
header('Content-Type: application/json');
Route::start();
