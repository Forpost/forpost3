<?php
/**
 * Created by PhpStorm.
 * User: d.yuriev
 * Date: 07.07.14
 * Time: 17:53
 */

class Cache extends AFacade
{
    public static function getFacadeAccessor()
    {
        return 'cache';
    }
}
