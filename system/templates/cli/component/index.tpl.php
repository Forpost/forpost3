&lt;?php

class <?=$component_name?> extends AComponent
{
    protected function exec($com_parameters = array())
    {
        if (Lib::chkFile(__DIR__ . '/component.php')) {
            require 'component.php';
        }
    }
}