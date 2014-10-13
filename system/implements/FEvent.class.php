<?php

/**
 * Created by JetBrains PhpStorm.
 * User: CoolKid
 * Date: 08.11.13
 * Time: 0:51
 * To change this template use File | Settings | File Templates.
 */
class FEvent
{
    protected $listeners = array();

    protected function addListener($event, $callback, $priority)
    {
        $this->listeners[$event][$priority][] = $callback;
    }

    public function fire($event, $arguments = array())
    {
        if (array_key_exists($event, $this->listeners) && Lib::chkArr($this->listeners[$event])) {
            ksort($this->listeners[$event]);

            foreach ($this->listeners[$event] as $callback_arr) {

                foreach ($callback_arr as $callback) {
                    $return = call_user_func_array($callback, $arguments);

                    if (false === $return) {
                        return;
                    }
                }
            }
        }
    }

    public function listen($event, $callback, $priority = 100)
    {
        if ((is_callable($callback)) || (is_string($callback) && function_exists($callback))) {
            $this->addListener(trim($event), $callback, $priority);
        }

    }

}
