<?php
/**
 * Created by PhpStorm.
 * User: d.yuriev
 * Date: 25.06.14
 * Time: 15:39
 */

class Container
{
    protected static $inst=null;

    public static function __callStatic($method_name, $arguments)
    {
        if(is_null(static::$inst)) static::inst();

        if (method_exists(static::$inst, $method_name)) {
            return call_user_func_array(array(static::$inst, $method_name), $arguments);
        } else {
            throw new Exception('Method '.$method_name.' not found in class '.get_class(static::$inst));
        }
    }

    protected static function inst()
    {
        static::$inst=new FContainer;
    }

    private function __construct() {}
    private function __clone() {}
    private function __sleep() {}
    private function __wakeup() {}
}
