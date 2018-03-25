<?php
namespace Jinxkit\Library;


/**
 * container of Route Fields
 * 
 * @author   Jinxes<blldxt@yahoo.com>
 * @version  1.0
 */
final class Filters
{
    /** @var array */
    private static $storage = [];

    /**
     * @param int $id route id
     * @param array $filters
     */
    public static function attach($id, array $filter)
    {
        static::$storage[$filter][] = $id;
    }

    /**
     * @param int $id
     * @return array
     */
    public static function getFiltersById($id)
    {
        return static::$storage[$id];
    }
}