<?php
/*
* @author: Dmitriy Yuriev <coolkid00@gmail.com>
* @product: Forpost3
* @version: 3.1
* @release date: 10.06.2014
* @development started: 21.08.2013
* @license: GNU AGPLv3
*
* Router class is loads and processes static and dynamic routes of system.
*/

if (!defined('FORPOST_VALID')) {
    header('HTTP/1.1 404 Not Found', 404);
    header('X-Powered-By: Apache', true);
    die("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL " . htmlentities(
            $_SERVER['REQUEST_URI'],
            ENT_QUOTES
        ) . " was not found on this server.</p>\n</body></html>");
}

class FRouter
{

    protected $args = array();
    protected $dynamic_routes = array();
    protected $static_routes = array();

    public function __construct()
    {
        $args = func_get_args();

        if (Lib::chkArr($args)) {
            $this->args = $args[0];
        }

        $this->loadDynamicRoutes();

        if (Config::get('app.enable_static_routes')) {
            $this->loadStaticRoutes();
        }
    }

    /** Adds dynamic route in routes array */
    public function addDynamic($route, $cap)
    {
        $this->dynamic_routes[$route] = $cap;
    }

    /** Adds static route in routes array */
    public function addStatic($path, $page_id)
    {
        $this->dynamic_routes[$path] = $page_id;
    }

    /* Loads dynamic (Controller/Action/Params) routes from routes file **/
    protected function loadDynamicRoutes()
    {
        if (Lib::chkFile(APP_ROUTES_FILE)) {
            $this->dynamic_routes = Lib::loadArrayFile(APP_ROUTES_FILE);
        }
    }

    /* Loads static (pages) routes from DB **/
    protected function loadStaticRoutes()
    {
        $sql = "SELECT struct.id, struct.path FROM fpst_structure struct
              WHERE struct.status <> 'DISABLED' AND struct.type = 'PAGE'";

        foreach (DB::query($sql)->fetchAssoc() as $db_route) {
            $this->static_routes[$db_route['path']] = $db_route['id'];
        }
    }

    /** Returns static route if it exists or false if not found */
    public function checkStaticRoute($request)
    {
        if (Lib::chkArrKey($request, $this->static_routes)) {
            return $this->static_routes[$request];
        }

        return false;
    }

    /** Returns dynamic route if it exists or false if not found */
    public function checkDynamicRoute($request)
    {
        foreach ($this->dynamic_routes as $src => $dst) {

            if (preg_match($src, $request, $matches)) {

                return preg_replace($src, '/' . implode('/', $dst), $request);
            }
        }

        return false;
    }
}
