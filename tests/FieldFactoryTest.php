<?php
declare(strict_types=1);
namespace Jinxes\Jinxkit\tests;

use PHPUnit\Framework\TestCase;
use Jinxes\Jinxkit\Library\FieldFactory;
use Jinxes\Jinxkit\Library\Group;
use Jinxes\Jinxkit\Library\Storage;
use Jinxes\Jinxkit\Library\Field;
use ReflectionClass;


class FieldFactoryTest extends TestCase
{
    public function testRestful()
    {
        $ff = new Group();
        $ff->setUri('test/:num');
        $fieldFast = $ff->restful('user', 'User', function($router) {
            return true;
        })->setName('testName');
        $fieldSlow = Storage::getFieldByName('testName');
        $this->assertInstanceOf(Field::class, $fieldFast);
        $this->assertSame($fieldFast, $fieldSlow);
        $this->assertEquals($fieldSlow->getUri(), 'test/:num/user');
        Storage::reset();
    }

    public function testMethod()
    {
        $ff = new FieldFactory();
        $ff->setUri('test/:num');
        $fieldFast = $ff->method('testMethod', 'testUri', 'testClass', 'testFunc')->setName('testName');
        $fieldSlow = Storage::getFieldByName('testName');
        $this->assertInstanceOf(Field::class, $fieldFast);
        $this->assertSame($fieldFast, $fieldSlow);
        $this->assertEquals($fieldSlow->getHttpMethod(), 'testMethod');
        $this->assertEquals($fieldSlow->getUri(), 'test/:num/testUri');
        $this->assertEquals($fieldSlow->getClassName(), 'testClass');
        $this->assertEquals($fieldSlow->getFunc(), 'testFunc');
        $this->assertEquals($fieldSlow->getName(), 'testName');

        $fieldFast = $ff->method('testMethod', 'testUri', 'testClass');
        $this->assertNull($fieldFast->getFunc());

        $fieldFast = $ff->method('testMethod', 'testUri', function() {
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

    private function methodTest($method)
    {
        $ff = new FieldFactory();
        $field = $ff->$method('testUri', 'testClass', 'testFunc');
        $this->assertEquals($field->getHttpMethod(), $method);
        $this->assertEquals($field->getUri(), 'testUri');
        $this->assertEquals($field->getClassName(), 'testClass');
        $this->assertEquals($field->getFunc(), 'testFunc');

        $field = $ff->get('testUri1', function() {
            return true;
        });
        $this->assertTrue(is_object($field->getFunc()));
        Storage::reset();
    }
}
