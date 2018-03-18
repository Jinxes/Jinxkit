<?php
declare(strict_types=1);
namespace Jinxkit\tests;
require_once 'TestService.php';

use PHPUnit\Framework\TestCase;
use Jinxkit\Library\Container;


final class ContainerTest extends TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            Container::class,
            Container::initialization(TestService::class)
        );
    }

    public function testSingleton()
    {
        $this->assertSame(
            Container::initialization(TestService::class),
            Container::initialization(TestService::class)
        );
    }

    public function testReverse()
    {
        $testArg1 = 2;
        $testArg2 = 3;
        $serviceReflection = Container::initialization(Test2Service::class);
        $result = $serviceReflection->reverse('test1', [$testArg1, $testArg2]);
        $this->assertArraySubset(
            $result, [1, 2, 3]
        );
    }

    public function testCallReflectionFunction()
    {
        $testArg1 = 2;
        $testArg2 = 3;
        $result = Container::callReflectionFunction(
            function (TestService $test, $arg1, $arg2) {
                return [$test->test1(), $arg1, $arg2];
            },
            [$testArg1, $testArg2]
        );
        $this->assertArraySubset(
            $result, [1, 2, 3]
        );
    }
}
