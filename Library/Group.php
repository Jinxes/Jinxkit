<?php
namespace Jinxes\Jinxkit\Library;


/**
 * Route group
 * 
 * @author   Jinxes<blldxt@yahoo.com>
 * @version  1.0
 */
class Group extends FieldFactory
{
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
        if (!is_null($callable)) {
            $this->restfulCallbackHandle($callable, $this->makeUri($uri), $field);
        }
        return $field;
    }
}
