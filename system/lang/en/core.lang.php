<?php
/*
* @author: Dmitriy Yuriev <coolkid00@gmail.com>
* @product: Forpost3
* @version: 3.1
* @release date: 04.06.2014
* @development started: 21.08.2013
* @license: GNU AGPLv3
*
* System core language file (english).
*/

if (!defined('FORPOST_VALID')) {
    header('HTTP/1.1 403 Forbidden',403);
    header('X-Powered-By: Apache 2.2.22');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1>
    <p>You don\'t have permission to access '.basename(__FILE__).' on this server.</p></body></html>');
}

return array(
    'cant_load_config_file'=> array(
        'Unable to load configuration file. File {%config_file%} not found.',
        array('{%config_file%}')
    ),

    'wrong_php_version' => array(
        'You version of PHP is lower than {%version%}! <br>Forpost3 need PHP {%version%} to work properly',
        array('{%version%}')
    ),

    'controller_not_found' => array(
        'Controller "{%controller_name%}" not found.',
        array('{%controller_name%}')
    ),

    'method_not_found_in_class' => array(
        'Method "{%method_name%}" not found in controller "{%controller_name%}".',
        array('{%controller_name%}','{%method_name%}')
    ),

    'method_not_allowed' => array(
        'Access method "{%method_name%}" is not allowed.',
        array('{%method_name%}')
    ),

    'model_not_found' => array(
        'Model "{%model_name%}" not found.',
        array('{%model_name%}')
    ),

    'view_not_found' => array(
        'View "{%view_name%}" not found.',
        array('{%view_name%}')
    ),

    'class_not_found' => array(
        'Class "{%class_name%}" not found.',
        array('{%class_name%}')
    ),

    'bind_not_found' => array(
        'Bind "{%bind_name%}" not found in dependency container.',
        array('{%bind_name%}')
    ),

    'class_not_instantiable'=> array(
        'Unable to create instance of class "{%class_name%}".',
        array('{%class_name%}')
    ),

    'template_not_provided'=> array(
        'Template file not provided.'
    ),

    'template_not_found'=> array(
        'Template file "{%template_file%}" not found.',
        array('{%template_file%}')
    ),

    'method_not_found_in_controller'=> array(
        'Method "{%method_name%}" not found in controller "{%controller_name%}".',
        array('{%method_name%}','{%controller_name%}')
    ),

    'component_not_found' => array(
        'Component "{%component_name%}" not found.',
        array('{%component_name%}')
    )

);
