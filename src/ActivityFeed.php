<?php

namespace JasperTey\ActivityFeed;

class ActivityFeed
{
    static $strings = [];
    static $actions = [];
    static $objects = [];

    public static function addObjects($objects = [])
    {
        static::$objects = array_merge(static::$objects, $objects);
    }

    public static function strings($strings = [])
    {
        static::$strings = $strings;
        return static::$strings;
    }

    public static function actions($actions = [])
    {
        static::$actions = $actions;
        return static::$actions;
    }
}
