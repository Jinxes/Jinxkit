<?php
namespace Jinxkit\Library;


/**
 * Di Container
 * 
 * @author   Jinxes<blldxt@yahoo.com>
 * @version  1.0
 */
class Container
{
    /**
     * Reflection singleton container
     * @static Container
     */
    private static $storage = [];

    /** @var array */
    private static $classStorage = [];

    /** @var array */
    private $singletons;

    /** @var array */
    private $reflections;

    /**
     * reverse a function and save singleton
     * 
     * @param callback $subject
     * @param array $inherentParams
     * 
     * @return mixed
     */
    public static function callReflectionFunction($subject, $inherentParams = [])
    {
        $reflection = new \ReflectionFunction($subject);
        $inherentNumber = count($inherentParams);
        $reflectionInstances = static::getReflectionInstances($reflection, $inherentNumber);
        $instanceArray = [];
        foreach($reflectionInstances as $instance) {
            $instanceArray[] = static::getAndRegistReflection($instance);
        }
        $params = array_merge($instanceArray, $inherentParams);
        return $reflection->invokeArgs($params);
    }

    /**
     * get instances of params from Reflection
     * 
     * @param \Reflector $reflection
     * @param num $inherentNumber
     * 
     * @return array
     */
    private static function getReflectionInstances($reflection, $inherentNumber)
    {
        $paramTypes = $reflection->getParameters();
        $reflectionNumber = count($paramTypes) - $inherentNumber;
        $reflectionInstances = array_slice($paramTypes, 0, $reflectionNumber);
        return $reflectionInstances;
    }

    /**
     * instantiation params
     * 
     * @param string
     * 
     * @return mixed
     * 
     * @throw \BadMethodCallException
     */
    private static function getAndRegistReflection($paramType)
    {
        $_objectClass = $paramType->getClass();

        if (is_null($_objectClass)) {
            throw new \BadMethodCallException('Method type not exists.');
        }

        $_objectNamespace = $_objectClass->getName();
        if (isset(static::$classStorage[$_objectNamespace])) {
            return static::$classStorage[$_objectNamespace];
        } else {
            // for construct
            $obj = static::initialization($_objectNamespace);
            static::$classStorage[$_objectNamespace] = $obj->singletons;
            return $obj->singletons;
        }
    }

    /**
     * @param string $class
     * 
     * @return Container
     */
    public static function initialization($class)
    {
        if (isset(static::$storage[$class])) {
            return static::$storage[$class];
        }

        $obj = new static($class);
        static::$storage[$class] = $obj;

        return $obj;
    }

    /** @param string $class */
    private function __construct($class)
    {
        $refObject = new \ReflectionClass($class);
        $params = $this->getParams($refObject, '__construct');

        $this->singletons = $refObject->newInstanceArgs($params);
        $this->reflections = $refObject;
    }

    /** @return mixed */
    public function getInstance()
    {
        return $this->singletons;
    }

    /**
     * @param string $method
     * @param array $inherentParams
     * 
     * @return mixed
     * 
     * @throw \InvalidArgumentException
     */
    public function reverse($method, $inherentParams=[])
    {
        if (! $this->reflections->hasMethod($method)) {
            throw new \InvalidArgumentException('Method not exists.');
        }

        $instanceArray = $this->getParams($this->reflections, $method, count($inherentParams));
        $params = array_merge($instanceArray, $inherentParams);
        $refmethod = $this->reflections->getMethod($method);

        return $refmethod->invokeArgs(
            $this->singletons,
            $params
        );
    }

    /**
     * instantiation param list of method and save
     * 
     * @param ReflectionClass $refClass
     * @param string $method
     * @param int inherentNumber
     * 
     * @return array
     * 
     * @internal
     */
    private function getParams($refClass, $method, $inherentNumber = 0)
    {
        $instanceArray = [];
        if (!$refClass->hasMethod($method)) {
            return $instanceArray;
        }

        $reflection = $refClass->getMethod($method);
        $reflectionInstances = static::getReflectionInstances($reflection, $inherentNumber);
        foreach($reflectionInstances as $instance) {
            $instanceArray[] = static::getAndRegistReflection($instance);
        }
        return $instanceArray;
    }
}
