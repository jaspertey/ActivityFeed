<?php

namespace JasperTey\ActivityFeed;

class ActivityFeed
{
    public static $strings = [];
    public static $actions = [];
    public static $objects = [];
    public static $grammar = [];

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

    public static function grammar(array $grammar = null, $merge = true)
    {
        if (is_array($grammar)) {
            static::$grammar = $merge && static::$grammar
                ? $grammar + static::$grammar : $grammar;
        }

        return static::$grammar;
    }
}
