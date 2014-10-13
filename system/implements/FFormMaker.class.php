<?php

/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 19.01.14
 * Time: 15:43
 */
abstract class FFormElement
{
    protected $html = '';

    public function getHTML()
    {
        return $this->html;
    }
}

class FFormTextarea extends FFormElement
{
    public function __construct($name, $text = '', $rows = 0, $cols = 0, $class_name = null, $style = null)
    {
        if (Lib::chkInt($rows)) {
            $rows = " rows='$rows'";
        } else {
            $rows = '';
        }
        if (Lib::chkInt($cols)) {
            $cols = " cols='$cols'";
        } else {
            $cols = '';
        }
        if (Lib::chkStr($class_name)) {
            $class_name = " class='$class_name'";
        } else {
            $class_name = '';
        }
        if (Lib::chkStr($style)) {
            $style = " style='$style'";
        } else {
            $style = '';
        }

        $this->html = "<textarea name='$name' id='id' $rows$cols$class_name$style>$text</textarea>";

    }
}

class FFormSubmit extends FFormElement
{
    public function __construct($value = '', $class_name = null, $style = null)
    {
        if (Lib::chkStr($class_name)) {
            $class_name = " class='$class_name'";
        } else {
            $class_name = '';
        }
        if (Lib::chkStr($style)) {
            $style = " style='$style'";
        } else {
            $style = '';
        }

        $this->html = "<input type='submit' value='$value'$class_name$style />\n";
    }
}

class FFormInput extends FFormElement
{
    public function __construct($name, $type, $value = '', $size = 0, $maxsize = 0, $class_name = null, $style = null)
    {
        if (Lib::chkInt($size)) {
            $size = " size='$size'";
        } else {
            $size = '';
        }
        if (Lib::chkInt($maxsize)) {
            $maxsize = " maxsize='$maxsize'";
        } else {
            $maxsize = '';
        }
        if (Lib::chkStr($class_name)) {
            $class_name = " class='$class_name'";
        } else {
            $class_name = '';
        }
        if (Lib::chkStr($style)) {
            $style = " style='$style'";
        } else {
            $style = '';
        }

        $this->html = "<input type='$type' name='$name' id='$name' value='$value'$size$maxsize$class_name$style />\n";
    }
}

class FFormTextInput extends FFormInput
{
    public function __construct($name, $value = '', $size = 0, $maxsize = 0, $class_name = null, $style = null)
    {
        $elem = new FFormInput($name, 'text', $value, $size, $maxsize, $class_name, $style);
        $this->html = $elem->getHTML();
    }
}

class FFormPasswordInput extends FFormInput
{
    public function __construct($name, $value = '', $size = 0, $maxsize = 0, $class_name = null, $style = null)
    {
        $elem = new FFormInput($name, 'password', $value, $size, $maxsize, $class_name, $style);
        $this->html = $elem->getHTML();
    }
}

class FFormHiddenInput extends FFormInput
{
    public function __construct($name, $value = '')
    {
        $elem = new FFormInput($name, 'hidden', $value);
        $this->html = $elem->getHTML();
    }
}

class FFormMaker
{
    protected $html = '';

    public function __construct($name, $method = 'post', $action = '')
    {
        $this->html = "<form name='$name' id='$name' method='$method' action='$action'>\n";
    }

    public function addElement($form_element, $wrap_begin = '', $wrap_end = '')
    {
        $this->html .= $wrap_begin . $form_element->getHTML() . $wrap_end;
    }

    public function addHTML($html = '')
    {
        $this->html .= $html;
    }

    public function getHTML()
    {
        return $this->html . '</form>';
    }

}
