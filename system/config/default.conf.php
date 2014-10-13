<?php
/*
* @author: Dmitriy Yuriev <coolkid00@gmail.com>
* @product: Forpost3
* @version: 3.1
* @release date: 04.06.2014
* @development started: 21.08.2013
* @license: GNU AGPLv3
*
* System default configuration.
*/

if (!defined('FORPOST_VALID')) {
    header('HTTP/1.1 403 Forbidden',403);
    header('X-Powered-By: Apache 2.2.22');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1>
    <p>You don\'t have permission to access '.basename(__FILE__).' on this server.</p></body></html>');
}

return array(
    /** System settings */
    'sys.date_timezone' => 'Europe/Moscow',
    'sys.session_name' => 'FORPOST3',
    'sys.dir_chmod' => 755,
    'sys.file_chmod' => 644,
    'sys.default_charset' => 'utf-8',
    'sys.session_max_lifetime' => 3600,
    'sys.session_cookie_lifetime' => 0,
    'sys.global_buffer_enable' => false,
    /** Cookie sesstings */
    'cookie.default_expire' => time()+60*60*24*31, // One month
    'cookie.default_path' => '/',
    'cookie.default_domain' => '',
    'cookie.only_http' => true,
    /** Application sesstings */
    'app.allowed_methods' => array('GET','POST','CLI'),
    'app.filter_vars' => true,
    'app.unset_global_vars' => true,
    'app.project_name' => 'Forpost3 web application',
    'app.debug_mode'=> true,
    'app.show_debug_panel' => true,
    'app.debug_panel_allowed_ips' => array('127.0.0.1/32'),
    'app.static_pages_enable' => true,
    'app.static_controller' => 'static',
    'app.static_action' => 'static',
    'app.on_controller_not_found' => 'exception', // Maybe 'exception',  'http_404', 'http_403'
    'app.on_method_not_found' => 'exception', // Maybe 'exception',  'http_404', 'http_403'
    'app.default_language' => 'en',
    'app.enable_static_routes' => false,
    'app.skip_url_parts' => 0,
    /*'app.default_logger_level' => 'DEBUG',*/
    /** Cache settings */
    'cache.default_ttl' => 600,
    'cache.datacache_enabled' => true,
    'cache.datacache_type' => 'file',
);
