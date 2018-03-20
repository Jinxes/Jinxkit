<?php
namespace Jinxkit\Library;


/**
 * container of Route Fields
 * 
 * @author   Jinxes<blldxt@yahoo.com>
 * @version  1.0
 */
final class Storage
{
    /** @var \SplObjectStorage */
    private static $storage = null;

    /**
     * attach the Route Fields Storage
     * 
     * @param \Jinxkit\Library\Field $field
     * @param string $name
     */
    public static function attach(Field $field)
    {
        if (is_null(static::$storage)) {
            static::$storage = new \SplObjectStorage();
        }
        static::$storage->attach($field);
    }

    /**
     * @param string $name
     * 
     * @return Field|false
     */
    public static function getFieldByName($name)
    {
        if (is_null(static::$storage)) {
            return false;
        }
        static::$storage->rewind();
        while (static::$storage->valid()) {
            $field = static::$storage->current();
            if ($field->getName() === $name) {
                return $field;
            }
            static::$storage->next();
        }
        return false;
    }

    /**
     * @param string $url
     * 
     * @return Field|false
     */
    public static function getFieldByUrl($url)
    {
        if (is_null(static::$storage)) {
            return false;
        }
        static::$storage->rewind();
        while (static::$storage->valid()) {
            $field = static::$storage->current();
            $matched = static::matchUrl($field->getUri(), $url);
            if ($matched !== false) {
                $field->setParams($matched);
                return $field;
            }
            static::$storage->next();
        }
        return false;
    }

    /**
     * match url and return matched params
     * 
     * @param string $url
     * @param string $realUrl
     * 
     * @return array|false
     */
    public static function matchUrl($url, $realUrl)
    {
        $url = '/' . $url;
        $urlinfo = static::makeRegUrl($url);
        $regexp = '/^'. $urlinfo .'\/?$/';
        if (\preg_match($regexp, $realUrl, $matched)) {
            array_shift($matched);
            return $matched;
        }
        return false;
    }

    /**
     * get function params from $pubUrl
     * 
     * @param string $subUrl
     * @param string $pubUrl
     * 
     * @return array
     */
    public static function getParamsFromUrl($subUrl, $pubUrl)
    {
        $urlinfo = static::makeRegUrl('/' . $subUrl);
        $regexp = '/^'. $urlinfo .'\/?$/';
        if (\preg_match($regexp, $pubUrl, $match)) {
            array_shift($match);
            return $match;
        }
        return [];
    }

    /** @return \SplObjectStorage */
    public static function getStorage()
    {
        return static::$storage;
    }

    /**
     * @param string $url
     * 
     * @return string
     * 
     * @internal
     */
    private static function makeRegUrl($url)
    {
        $url = str_replace([
            '.', '*', '$', '[', ']', '(', ')'
        ], [
            '\\.', '\\*', '\\$', '\\[', '\\]', '\\(', '\\)',
        ], $url);
        return str_replace([
            '/', ':str', ':num', ':any'
        ], [
            '\\/', '(\w+)', '(\d+)', '(.*+)'
        ], $url);
    }

    /** reset storage for test */
    public static function reset()
    {
        static::$storage = null;
    }
}