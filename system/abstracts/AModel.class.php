<?php
/**
 * Created by JetBrains PhpStorm.
 * User: CoolKid
 * Date: 24.08.13
 * Time: 3:10
 * To change this template use File | Settings | File Templates.
 */

abstract class AModel
{
    protected $data;

    public function __construct()
    {
        Container::bind(get_class($this),$this);
    }

    public function getData($key=null)
    {
        if (is_null($key)) {
            return $this->data;
        } else {
            return $this->data[$key];
        }
    }

    protected function addData($key,$value)
    {
        $this->data[$key]=$value;
    }

    protected function delData($key)
    {
        unset($this->data[$key]);
    }

    public function __get($alias)
    {
        if (!Container::isBindExists($alias)) {
            Container::bind($alias);
        }

        return Container::make($alias);
    }

}
