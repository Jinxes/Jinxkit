<?php
namespace Jinxkit\Library;


/**
 * Route Field Maker
 * 
 * @author   Jinxes<blldxt@yahoo.com>
 * @version  1.0
 */
class FieldFactory
{
    /** @var string */
    private $uri = '';

    /** @var array */
    private $filters = [];

    /** @var Field|null */
    public $fromField = null;

    /** @param string $uri */
    public function setUri($uri)
    {
        $this->uri = $uri;
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
     * @param array $filters
     */
    public function filter(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * @param callback|null $callable
     * @param string $fieldUri
     * @param Field $fromField parent Field
     * 
     * @internal
     */
    protected function restfulCallbackHandle($callable, $fieldUri, $fromField)
    {
        $fieldFactory = new self();
        $fieldFactory->setUri($fieldUri);
        $fieldFactory->fromField = $fromField;
        $callable($fieldFactory);
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
            $this->makeRestField($field, $className, $func);
        } else {
            $this->makeEmbedField($field, $className);
        }
        $field->parentField = $this->fromField;
        $field->filter($this->filters);
        Storage::attach($field);
        return $field;
    }

    /**
     * @param Field $field
     * @param string $className
     * @param string $func
     */
    protected function makeRestField(&$field, $className, $func)
    {
        $field->setClassName($className);
        $field->setFunc($func);
        $field->setType(Field::TYPE_REST);
    }

    /**
     * @param Field $field
     * @param callback $func
     */
    protected function makeEmbedField(&$field, $func)
    {
        $field->setFunc($func);
        $field->setType(Field::TYPE_EMBED);
    }

    /**
     * base url + field url
     * @param string $uri
     * 
     * @return string
     * 
     * @internal
     */
    protected function makeUri($uri)
    {
        return ($this->uri === '') ? $uri : $this->uri . '/' . $uri;
    }
}