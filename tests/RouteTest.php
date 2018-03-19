<?php
declare(strict_types=1);
namespace Jinxkit\tests;

use PHPUnit\Framework\TestCase;
use Jinxkit\Library\FieldFactory;
use Jinxkit\Library\HttpException;
use Jinxkit\Library\Storage;
use Jinxkit\Library\Field;
use Jinxkit\Route;
use ReflectionClass;
require_once 'TestService.php';


class RouteTest extends TestCase
{
    public function testRestful()
    {
        $fieldFast = Route::restful('user', 'User', function($router) {
            return true;
        })->setName('testName');
        $fieldSlow = Storage::getFieldByName('testName');
        $this->assertInstanceOf(Field::class, $fieldFast);
        $this->assertSame($fieldFast, $fieldSlow);
        $this->assertEquals($fieldSlow->getUri(), 'user');
        Storage::reset();
    }

    public function testMethod()
    {
        $fieldFast = Route::method('testMethod', 'testUri', 'testClass', 'testFunc')->setName('testName');
        $fieldSlow = Storage::getFieldByName('testName');
        $this->assertInstanceOf(Field::class, $fieldFast);
        $this->assertSame($fieldFast, $fieldSlow);
        $this->assertEquals($fieldSlow->getHttpMethod(), 'testMethod');
        $this->assertEquals($fieldSlow->getUri(), 'testUri');
        $this->assertEquals($fieldSlow->getClassName(), 'testClass');
        $this->assertEquals($fieldSlow->getFunc(), 'testFunc');
        $this->assertEquals($fieldSlow->getName(), 'testName');

        $fieldFast = Route::method('testMethod', 'testUri', 'testClass');
        $this->assertNull($fieldFast->getFunc());

        $fieldFast = Route::method('testMethod', 'testUri', function() {
            return true;
        });
        $this->assertTrue(is_object($fieldFast->getFunc()));
        Storage::reset();
    }

    public function testGet()
    {
        $this->methodTest('get');
    }

    public function testPost()
    {
        $this->methodTest('post');
    }
    
    public function testPatch()
    {
        $this->methodTest('patch');
    }

    public function testPut()
    {
        $this->methodTest('put');
    }

    public function testDelete()
    {
        $this->methodTest('delete');
    }

    public function testHead()
    {
        $this->methodTest('head');
    }

    public function testCallRestfulFunction()
    {
        $list = Route::callRestfulFunction(Test2Service::class, 'test1', [2, 3]);
        $this->assertEquals($list, [1, 2, 3]);
    }

    public function testGetPathInfo()
    {
        $_SERVER['PATH_INFO'] = 'test';
        $pathinfo = Route::getPathInfo();
        $this->assertEquals($pathinfo, 'test');
    }

    public function testScan()
    {
        $fieldFactory = new FieldFactory();
        $fieldFactory->post('test', Field::class, 'requestMethod');
        $_SERVER['PATH_INFO'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->runScan();
        Storage::reset();
    }

    private function runScan()
    {
        try {
            $result = Route::scan();
            $this->assertEquals($result, 1);
        } catch (\Throwable $t) {
            $this->assertInstanceOf(HttpException::class, $t);
        } catch (\Exception $e) {
            $this->assertInstanceOf(HttpException::class, $e);
        }
    }

    private function methodTest($method)
    {
        $field = Route::$method('testUri', 'testClass', 'testFunc');
        $this->assertEquals($field->getHttpMethod(), $method);
        $this->assertEquals($field->getUri(), 'testUri');
        $this->assertEquals($field->getClassName(), 'testClass');
        $this->assertEquals($field->getFunc(), 'testFunc');

        $field = Route::get('testUri1', function() {
            return true;
        });
        $this->assertTrue(is_object($field->getFunc()));
        Storage::reset();
    }
}