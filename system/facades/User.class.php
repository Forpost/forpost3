<?php
/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 21.01.14
 * Time: 2:38
 */

final class User extends AFacade
{
    public static function getFacadeAccessor()
    {
        return 'user';
    }
}
