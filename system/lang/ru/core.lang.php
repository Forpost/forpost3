<?php
/*
* @author: Dmitriy Yuriev <coolkid00@gmail.com>
* @product: Forpost3
* @version: 3.1
* @release date: 04.06.2014
* @development started: 21.08.2013
* @license: GNU AGPLv3
*
* System core language file (russian).
*/

if (!defined('FORPOST_VALID')) {
    header('HTTP/1.1 404 Not Found',404);
    header('X-Powered-By: Apache',true);
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL '.htmlentities($_SERVER['REQUEST_URI'],ENT_QUOTES).' was not found on this server.</p></body></html');
}

return array(
    'cant_load_config_file'=> array(
        'Невозможно загрузить файл конфигурации. Файл {%config_file%} не найден.',
        array('{%config_file%}')
    ),
    'wrong_php_version' => array(
        'Версия используемого интерпретатора PHP ниже чем {%version%}! <br>Для корректной работы Forpost3 необходим PHP версии не ниже {%version%}',
        array('{%version%}')
    ),
    'controller_not_found' => array(
        'Контроллер "{%controller_name%}" не найден.',
        array('{%controller_name%}')
    ),

    'method_not_found_in_class' => array(
        'Метод "{%method_name%}" в контроллере "{%controller_name%}" не найден.',
        array('{%controller_name%}','{%method_name%}')
    ),

    'method_not_allowed' => array(
        'Метод доступа "{%method_name%}" не разрешен.',
        array('{%method_name%}')
    ),

    'model_not_found' => array(
        'Модель "{%model_name%}" не найдена.',
        array('{%model_name%}')
    ),

    'view_not_found' => array(
        'Представление "{%view_name%}" не найдено.',
        array('{%view_name%}')
    ),

    'class_not_found' => array(
        'Класс "{%class_name%}" не найден.',
        array('{%class_name%}')
    ),

    'bind_not_found' => array(
        'Связка "{%bind_name%}" не найдена в контейнере зависимостей.',
        array('{%bind_name%}')
    ),

    'class_not_instantiable'=> array(
        'Невозможно создать экземпляр класса "{%class_name%}".',
        array('{%class_name%}')
    ),

    'template_not_provided'=> array(
        'Не указан файл шаблона.'
    ),

    'template_not_found'=> array(
        'Файл шаблона "{%template_file%}" не найден.',
        array('{%template_file%}')
    ),

    'method_not_found_in_controller'=> array(
        'Метод "{%method_name%}" не найден в контроллере "{%controller_name%}".',
        array('{%method_name%}','{%controller_name%}')
    ),

    'component_not_found' => array(
        'Компонент "{%component_name%}" не найден.',
        array('{%component_name%}')
    )
);
