<?php
/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 16.01.14
 * Time: 16:10
 */

class Component
{
    public static function run($com_name, $com_parameters = array())
    {
        if (!class_exists($com_name)) {
            throw new FException(Lang::getMessage('system.core.component_not_found', array($com_name)));
        }

        $component_instance = new $com_name($com_parameters);

        return $component_instance;
    }

}
