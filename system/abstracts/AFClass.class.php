<?php
/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 20.09.14
 * Time: 22:07
 */

abstract class AFClass
{
    public function __construct()
    {
        if (func_num_args() > 0 && method_exists($this, 'construct')) {
            call_user_func_array(array($this, 'construct'), func_get_args());
        }
    }
}
