<?php
namespace Jinxkit\tests;

class TestService
{
    public function test1()
    {
        return 1;
    }
}

class Test2Service
{
    public function test1(TestService $test, $arg1, $arg2)
    {
        return [$test->test1(), $arg1, $arg2];
    }
}
