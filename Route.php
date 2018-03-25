<?php
namespace Jinxes\Jinxkit;

use Jinxes\Jinxkit\Library\Storage;
use Jinxes\Jinxkit\Library\Field;
use Jinxes\Jinxkit\Library\Group;
use Jinxes\Jinxkit\Library\Container;
use Jinxes\Jinxkit\Library\HttpException;


/**
 * foundation entry
 * 
 * @author   Jinxes<blldxt@yahoo.com>
 * @version  1.0
 */
class Route
{
    /** @var array */
    private static $configure = [
        'filterEntry' => '__invoke',
        'filterArgs' => 'args'
    ];

    /** @param array $config */
    public static function config(array $config)
    {
        static::$configure = $config + static::$configure;
    }

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

    /** router match and send response */
    public static function start()
    {
        try {
            static::scan();
        } catch(HttpException $httpException) {
            $code = $httpException->getStatusCode();
            http_response_code($code);
        }
    }

    /**
     * match all path for execute api
     * 
     * @return mixed
     * 
     * @throw HttpException
     * 
     * @internal
     */
    public static function scan()
    {
        $field = Storage::getFieldByUrl(static::getPathInfo());
        if ($field === false) {
            throw new HttpException(404);
        }
        
        if ($field->methodIsValid()) {
            $params = Storage::getParamsFromUrl($field->getUri(), static::getPathInfo());
            if (! static::callFilters($field, $params)) {
                return false;
            }
            static::callController($field, $params);
        } else {
            throw new HttpException(405);
        }
    }

    /**
     * @param Field $field
     * @param array $params
     * @return bool
     */
    private static function callFilters($field, $params)
    {
        $filters = static::getFiltersByField($field);
        foreach ($filters as $filter) {
            $container = Container::initialization($filter);
            $instance = $container->getInstance();
            $argsField = static::$configure['filterArgs'];
            $instance->$argsField = $params;
            $result = $container->reverse(static::$configure['filterEntry']);
            if ($result === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param Field $field
     * @param array $curentFilters
     * @return array
     */
    private static function getFiltersByField($field, $curentFilters = [])
    {
        if (is_null($field->parentField)) {
            return array_merge($field->getFilters(), $curentFilters);
        }
        $swap = array_merge($field->getFilters(), $curentFilters);
        return static::getFiltersByField($field->parentField, $swap);
    }

    /**
     * @param Field $field
     * @param array $params
     */
    public static function callController($field, $params)
    {
        if ($field->isTypeRest()) {
            static::callRestfulFunction(
                $field->getClassName(), $field->getMethod(), $params
            );
        } else {
            Container::callReflectionFunction($field->getFunc(), $params);
        }
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
     * 
     * @internal
     */
    public static function callRestfulFunction($subject, $method, $params = [])
    {
        $container = Container::initialization($subject);
        return $container->reverse($method, $params);
    }
}