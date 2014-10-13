<?php
/**
 * Created by JetBrains PhpStorm.
 * User: CoolKid
 * Date: 22.12.13
 * Time: 14:36
 * To change this template use File | Settings | File Templates.
 */

final class Session extends AFacade
{
    public static function getFacadeAccessor()
    {
        return 'session';
    }
}
