<?php
/**
 * Created by JetBrains PhpStorm.
 * User: CoolKid
 * Date: 22.12.13
 * Time: 2:32
 * To change this template use File | Settings | File Templates.
 */

abstract class AFacade implements IFacade
{
    protected static $always_new=false;

    public static function __callStatic($method_name, $arguments)
    {
        if(static::$always_new) {
            $instance=static::getInstNew();
        } else {
            $instance=static::getInst();
        }

        if (method_exists($instance, $method_name)) {
            return call_user_func_array(array($instance, $method_name), $arguments);
        } else {
            throw new FException(Lang::getMessage('system.core.method_not_found_in_class',array(get_class($instance),$method_name)));
        }
    }

    public static function getInst()
    {
        return Container::make(static::getFacadeAccessor(),func_get_args());
    }

    public static function getInstNew()
    {
        return Container::makeNew(static::getFacadeAccessor(),func_get_args());
    }

    private function __construct() {}
    private function __clone() {}
    private function __sleep() {}
    private function __wakeup() {}
}
