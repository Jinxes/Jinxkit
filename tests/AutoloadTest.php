<?php
namespace Jinxes\Jinxkit\tests;

spl_autoload_register(function ($class) {
    $path = str_replace('Jinxes\\Jinxkit\\', '', $class);
    $path = strtr($path, '\\', '/');
    require $path . '.php';
});