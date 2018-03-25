<?php
declare(strict_types=1);
namespace Jinxes\Jinxkit\tests;

use PHPUnit\Framework\TestCase;
use Jinxes\Jinxkit\Library\Storage;
use Jinxes\Jinxkit\Library\Field;


class StorageTest extends TestCase
{
    public function testAttach()
    {
        $field = new Field();
        Storage::attach($field);
        $storage = Storage::getStorage();

        $storage->rewind();
        $currentField = $storage->current();
        $this->assertSame($field, $currentField);
        Storage::reset();
    }

    public function testGetStorage()
    {
        $this->assertNull(Storage::getStorage());
        $field = new Field();
        Storage::attach($field, 'test');
        $this->assertInstanceOf(
            \SplObjectStorage::class,
            Storage::getStorage()
        );
        Storage::reset();
    }

    public function testReset()
    {
        $field = new Field();
        Storage::attach($field, 'test');
        Storage::reset();
        $this->assertNull(Storage::getStorage());
    }

    public function testGetParamsFromUrl()
    {
        $sub = 'test/:num/:str';
        $pub = '/test/12/abc';
        $params = Storage::getParamsFromUrl($sub, $pub);
        $this->assertEquals($params, [12, 'abc']);

        $sub = 'test';
        $pub = 'test';
        $params = Storage::getParamsFromUrl($sub, $pub);
        $this->assertEquals($params, []);

        $pub = 'raise';
        $params = Storage::getParamsFromUrl($sub, $pub);
        $this->assertEquals($params, []);
    }

    public function testMatchUrl()
    {
        $url = 'test/:num/:str';
        $realUrl = '/test/123/test';
        $matched = Storage::matchUrl($url, $realUrl);
        $this->assertEquals(
            $matched,
            [123, 'test']
        );
        $this->assertTrue(is_numeric($matched[0]));

        $realUrl = '/test/123/test/';
        $matched = Storage::matchUrl($url, $realUrl);
        $this->assertEquals($matched, [123, 'test']);

        $url = 'test/:str/:num';
        $matched = Storage::matchUrl($url, $realUrl);
        $this->assertFalse($matched);
    }

    public function testGetFieldByUrl()
    {
        $field1 = new Field();
        $field1->setUri('test/:num');
        Storage::attach($field1, 'test');

        $field2 = new Field();
        $field2->setUri('test2');
        Storage::attach($field2, 'test2');

        $rest = Storage::getFieldByUrl('/test/12');
        $this->assertEquals($rest->getParams(), [12]);

        $rest = Storage::getFieldByUrl('/test/abc');
        $this->assertFalse($rest);
        Storage::reset();
    }

    public function testGetFieldByName()
    {
        $field1 = new Field();
        $field1->setUri('testone');
        $field1->setName('test1');
        Storage::attach($field1, 'test1');

        $field2 = new Field();
        $field2->setUri('testtwo');
        $field2->setName('test2');
        Storage::attach($field2);

        $rest = Storage::getFieldByName('test2');
        $this->assertEquals($rest->getUri(), 'testtwo');

        $rest = Storage::getFieldByName('test3');
        $this->assertFalse($rest);
        Storage::reset();
    }
}