<?php
/**
 * Created by JetBrains PhpStorm.
 * User: CoolKid
 * Date: 22.12.13
 * Time: 2:35
 * To change this template use File | Settings | File Templates.
 */

interface IFacade
{
    public static function getFacadeAccessor();
    public static function getInst();
    public static function getInstNew();
}
