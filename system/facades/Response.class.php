<?php
/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 18.08.14
 * Time: 2:09
 */

class Response extends AFacade
{
    public static $always_new = true;

    public static function getFacadeAccessor()
    {
        return 'response';
    }
} 