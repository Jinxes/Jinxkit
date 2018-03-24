<?php
namespace Jinxkit\Library;


/**
 * Route Field Maker
 * 
 * @author   Jinxes<blldxt@yahoo.com>
 * @version  1.0
 */
abstract class FieldFactory
{
    /** @var string */
    private $uri = '';

    /** @param array $midware */
    abstract public function setMidware(array $midware);

    /** @return array */
    abstract public function getMidware();

    /** @param string $uri */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @param string $uri
     * @param string $className
     * @param callback|null $callable
     * 
     * @return Field
     */
    public function restful($uri, $className, $callable = null)
    {
        $field = $this->method(null, $uri, $className);
        $this->callbackHandle($callable, $this->makeUri($uri));
        return $field;
    }

    /**
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public function get($uri, $className, $func = null)
    {
        return $this->method('get', $uri, $className, $func);
    }

    /**
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public function post($uri, $className, $func = null)
    {
        return $this->method('post', $uri, $className, $func);
    }

    /**
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public function patch($uri, $className, $func = null)
    {
        return $this->method('patch', $uri, $className, $func);
    }

    /**
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public function put($uri, $className, $func = null)
    {
        return $this->method('put', $uri, $className, $func);
    }

    /**
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public function delete($uri, $className, $func = null)
    {
        return $this->method('delete', $uri, $className, $func);
    }

    /**
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public function head($uri, $className, $func = null)
    {
        return $this->method('head', $uri, $className, $func);
    }

    /**
     * @param string|null $methodType
     * @param string $uri
     * @param string $className
     * @param string|null $func
     * 
     * @return Field
     */
    public function method($methodType, $uri, $className, $func = null)
    {
        $field = new Field();
        $fieldUri = $this->makeUri($uri);
        $field->setUri($fieldUri);
        $field->setHttpMethod($methodType);
        if (is_string($className)) {
            $field->setClassName($className);
            $field->setFunc($func);
            $field->setType(Field::TYPE_REST);
        } else {
            $field->setFunc($className);
            $field->setType(Field::TYPE_EMBED);
        }
        $field->setGroupMidware($this->getMidware());
        Storage::attach($field);
        return $field;
    }

    /**
     * @param callback|null $callable
     * @param string $fieldUri
     * 
     * @internal
     */
    private function callbackHandle($callable, $fieldUri)
    {
        if (!is_null($callable)) {
            $groupMidware = $this->getMidware();
            $fieldFactory = new Group();
            // inherit midware from parent
            $fieldFactory->setMidware($groupMidware);
            $fieldFactory->setUri($fieldUri);
            $callable($fieldFactory);
        }
    }

    /**
     * make uri string for Field object
     * @param string $uri
     * 
     * @return string
     * 
     * @internal
     */
    private function makeUri($uri)
    {
        if ($this->uri === '') {
            return $uri;
        }
        return $this->uri . '/' . $uri;
    }
}