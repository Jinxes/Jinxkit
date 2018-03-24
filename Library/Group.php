<?php
namespace Jinxkit\Library;


/**
 * Route group
 * 
 * @author   Jinxes<blldxt@yahoo.com>
 * @version  1.0
 */
class Group extends FieldFactory
{
    /** @var array */
    protected $midware = [];

    /**
     * @param array $midware
     * @return static
     */
    public function setMidware(array $midware)
    {
        $this->midware = array_merge($this->midware, $midware);
        return $this;
    }

    /** @return array */
    public function getMidware()
    {
        return $this->midware;
    }
}
