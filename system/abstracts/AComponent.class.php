<?php
/**
 * Created by JetBrains PhpStorm.
 * User: CoolKid
 * Date: 08.12.13
 * Time: 21:38
 * To change this template use File | Settings | File Templates.
 */

abstract class AComponent extends WebController
{
    protected $data = array();
    protected $content = '';
    protected $output = array();

    abstract protected function exec($com_parameters = array());

    final public function __construct($com_parameters=array())
    {
        $this->run($com_parameters);
        $key = get_class($this);
        $this->output[$key]['content'] = $this->getContent();
        $this->output[$key]['data'] = $this->getData();
    }

    final public function run($com_parameters = array())
    {
        ob_start();
        $this->exec($com_parameters);
        $this->content = ob_get_clean();

        return $this;
    }

    final public function getData()
    {
        return $this->data;
    }

    final public function getContent()
    {
        return $this->content;
    }

    final protected function addData($data)
    {
        $this->data[] = $data;
    }

    final public function getOutput()
    {
        return $this->output;
    }

    final public function __toString()
    {
        return $this->getContent();
    }
}
