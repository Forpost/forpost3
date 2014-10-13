<?php
/**
 * Created by JetBrains PhpStorm.
 * User: CoolKid
 * Date: 21.08.13
 * Time: 21:43
 * To change this template use File | Settings | File Templates.
 */

interface IStorage
{
    public function add($key,$data,$ttl=null);
    public function hasKey($key);
    public function delete($key);
    public function flush();
    public function get($key);
    public function replace($key,$data,$ttl=null);
    public function set($key,$data,$ttl=null);
}
