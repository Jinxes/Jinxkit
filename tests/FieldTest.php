<?php
declare(strict_types=1);
namespace Jinxkit\tests;

use PHPUnit\Framework\TestCase;
use Jinxkit\Library\Storage;
use Jinxkit\Library\Field;
use ReflectionClass;

class FieldTest extends TestCase
{
    public function testSetUri()
    {
        $this->setMethodTest('uri', 'test');
    }

    public function testSetType()
    {
        $this->setMethodTest('type', Field::TYPE_REST);
    }

    public function testSetName()
    {
        $this->setMethodTest('name', 'test');
    }

    public function testSetClassName()
    {
        $this->setMethodTest('className', 'test');
    }

    public function testSetFunc()
    {
        $this->setMethodTest('func', 'test');
        $this->setMethodTest('func', function () {
            return true;
        });
    }

    public function testSetMidware()
    {
        $this->setMethodTest('midware', ['test1', 'test2']);
    }

    public function testSetParams()
    {
        $this->setMethodTest('params', ['test', 1]);
    }

    public function testGetUri()
    {
        $this->getMethodTest('uri', 'test');
    }
    
    public function testGetType()
    {
        $this->getMethodTest('type', Field::TYPE_REST);
    }

    public function testGetName()
    {
        $this->getMethodTest('name', 'test');
    }

    public function testGetClassName()
    {
        $this->getMethodTest('className', 'test');
    }

    public function testGetFunc()
    {
        $this->getMethodTest('func', 'test');
        $this->getMethodTest('func', function () {
            return true;
        });
    }

    public function testGetMidware()
    {
        $this->getMethodTest('midware', ['test1', 'test2']);
    }

    public function testGetParams()
    {
        $this->getMethodTest('params', ['test', 1]);
    }

    public function testIsTypeRest()
    {
        $field = new Field();
        $field->setType(Field::TYPE_REST);
        $this->assertTrue($field->isTypeRest());
    }

    public function testIsTypeEmbed()
    {
        $field = new Field();
        $field->setType(Field::TYPE_EMBED);
        $this->assertTrue($field->isTypeEmbed());
    }

    public function testRequestMethod()
    {
        $field = new Field();
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals($field->requestMethod(), 'get');
        $this->assertEquals($field->requestMethod(true), 'GET');
    }


    private function setMethodTest($fd, $data)
    {
        list($field, $reflected) = $this->getRefArg($fd);
        $method = 'set' . ucwords($fd);
        $field->$method($data);
        $this->assertEquals(
            $reflected->getvalue($field),
            $data
        );
    }

    private function getMethodTest($fd, $data)
    {
        $getmethod = 'get' . ucwords($fd);
        $setmethod = 'set' . ucwords($fd);
        $f = new Field();
        $f->$setmethod($data);
        $this->assertEquals(
            $f->$getmethod(),
            $data
        );
    }

    private function getRefArg($param)
    {
        $reflectedField = new ReflectionClass(Field::class);
        $ref = $reflectedField->newInstanceArgs();
        $reflectedParam = $reflectedField->getProperty($param);
        $reflectedParam->setAccessible(true);
        return [$ref, $reflectedParam];
    }
}