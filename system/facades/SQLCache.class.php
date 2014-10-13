<?php
/**
 * Created by PhpStorm.
 * User: d.yuriev
 * Date: 23.12.13
 * Time: 19:08
 */

final class SQLCache extends AFacade
{
    public static function getFacadeAccessor()
    {
        return 'sqlcache';
    }
}
