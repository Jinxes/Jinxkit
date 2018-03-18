<?php
namespace Jinxkit;

use Jinxkit\Library\Storage;
use Jinxkit\Library\Field;
use Jinxkit\Library\Group;
use Jinxkit\Library\Container;


/**
 * foundation entry
 */
class Route
{
    /**
     * @param string $groupUri
     * @param callback $callable
     */
    public static function group($groupUri, $callable)
    {
        $group = new Group();
        $group->setUri($groupUri);
        $callable($group);
    }

    /**
     * @param string $uri
     * @param string $className
     * @param callback|null $callable
     * 
     * @return Field
     */
    public static function restful($uri, $className, $callable = null)
    {
        $group = new Group();
        return $group->restful($uri, $className, $callable);
    }

    /**
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public static function get($uri, $className, $func = null)
    {
        $group = new Group();
        return $group->get($uri, $className, $func);
    }

    /**
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public static function post($uri, $className, $func = null)
    {
        $group = new Group();
        return $group->post($uri, $className, $func);
    }

    /**
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public static function patch($uri, $className, $func = null)
    {
        $group = new Group();
        return $group->patch($uri, $className, $func);
    }

    /**
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public static function put($uri, $className, $func = null)
    {
        $group = new Group();
        return $group->put($uri, $className, $func);
    }

    /**
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public static function delete($uri, $className, $func = null)
    {
        $group = new Group();
        return $group->delete($uri, $className, $func);
    }

    /**
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public static function head($uri, $className, $func = null)
    {
        $group = new Group();
        return $group->head($uri, $className, $func);
    }

    /**
     * @param string|null $methodType
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public static function method($methodType, $uri, $className, $func = null)
    {
        $group = new Group();
        return $group->method($methodType, $uri, $className, $func);
    }

    /**
     * match path info to execute api
     * 
     * @return mixed
     */
    public static function scan()
    {
        $field = Storage::getFieldByUrl(static::getPathInfo());
        if ($field === false) {
            echo 404;
            return;
        }
        // TODO run midware

        $params = Storage::getParamsFromUrl(
            $field->getUri(), static::getPathInfo()
        );
        if ($field->isTypeRest()) {
            return static::callRestfulFunction(
                $field->getClassName(), $field->getMethod(), $params
            );
        }
        return Container::callReflectionFunction(
            $field->getClassName(), $params
        );
    }

    /**
     * get request path info
     * 
     * @return string
     */
    public static function getPathInfo()
    {
        return empty($_SERVER['PATH_INFO']) ? '' : $_SERVER['PATH_INFO'];
    }

    /**
     * @param string $subject
     * @param string $method
     * @param array $params
     * 
     * @return mixed
     */
    public static function callRestfulFunction($subject, $method, $params = [])
    {
        $container = Container::initialization($subject);
        return $container->reverse($method, $params);
    }
}