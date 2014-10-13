<?php
/**
 * Created by PhpStorm.
 * User: d.yuriev
 * Date: 06.08.14
 * Time: 14:38
 */

class View extends AFacade
{
    protected static $always_new=true;

    public static function getFacadeAccessor()
    {
        return 'view';
    }

} 