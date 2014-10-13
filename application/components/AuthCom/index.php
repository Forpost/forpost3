<?php

class AuthCom extends AComponent
{
    protected function exec($com_parameters = array())
    {
        if (Lib::chkFile(__DIR__ . '/component.php')) {
            require 'component.php';
        }
    }
}