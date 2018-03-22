<?php
namespace Jinxkit\Library;


/**
 * Route field struct
 * 
 * @author   Jinxes<blldxt@yahoo.com>
 * @version  1.0
 */
class Field
{
    /** @static int */
    const TYPE_REST = 1;

    /** @static int */
    const TYPE_EMBED = 2;

    /** @var string */
    private $uri;

    /** @var int */
    private $type;

    /** @var string */
    private $httpMethod;

    /** @var string */
    private $className;

    /** @var string|callback */
    private $func;

    /** $var array */
    private $midware = [];

    /** @var array */
    private $params = [];

    /** @var array */
    private $name;

    /** @param string $uri */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /** @param int $type */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /** @param string|null $method */
    public function setHttpMethod($method)
    {
        $this->httpMethod = $method;
    }

    /** @param string $className */
    public function setClassName($className)
    {
        $this->className = $className;
        return $this;
    }

    /** @param string|callback $func */
    public function setFunc($func)
    {
        $this->func = $func;
        return $this;
    }

    /** @param array $midware */
    public function setMidware(array $midware)
    {
        $this->midware = $midware;
        return $this;
    }

    /** @param array $params */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /** @var string $name */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /** @return string */
    public function getUri()
    {
        return $this->uri;
    }

    /** @return int */
    public function getType()
    {
        return $this->type;
    }

    /** @return string|null */
    public function getHttpMethod()
    {
        return empty($this->httpMethod) ? null : $this->httpMethod;
    }

    /** @return string */
    public function getClassName()
    {
        return $this->className;
    }

    /** @return string|callback */
    public function getFunc()
    {
        return $this->func;
    }

    /** @return array */
    public function getMidware()
    {
        return $this->midware;
    }

    /** @return array */
    public function getParams()
    {
        return $this->params;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    /**
     * rest has methods for http request, and don't check http method
     * @return bool
     */
    public function isTypeRest()
    {
        return (bool)($this->getType() === static::TYPE_REST);
    }

    /** @return bool */
    public function isTypeEmbed()
    {
        return (bool)($this->getType() === static::TYPE_EMBED);
    }

    /** @return bool */
    public function isStandardRestfulMethod()
    {
        return (bool)(empty($this->getHttpMethod()) && $this->isTypeRest());
    }

    /** @return bool */
    public function isUnstandardRestfulMethod()
    {
        $httpMethod = $this->getHttpMethod();
        $method = $this->requestMethod();
        if (!is_null($httpMethod)) {
            if ($httpMethod === $method) {
                return true;
            }
        }
        return false;
    }

    /** @return bool */
    public function isRestfulMethod()
    {
        $isRM = $this->isStandardRestfulMethod();
        $isURM = $this->isUnstandardRestfulMethod();
        return (bool) ($isRM or $isURM);
    }

    /**
     * only restful type has method
     * @return bool
     */
    public function methodIsValid()
    {
        return $this->isRestfulMethod();
    }

    /**
     * @return string
     * 
     * @throw HttpException
     */
    public function getMethod()
    {
        $method = $this->requestMethod();
        $func = $this->getFunc();
        return empty($func) ? $method : $func;
    }

    /**
     * @return string
     * 
     * @internal
     */
    public function requestMethod($isUp = false)
    {
        return $isUp ? $_SERVER['REQUEST_METHOD'] : strtolower($_SERVER['REQUEST_METHOD']);
    }
}
