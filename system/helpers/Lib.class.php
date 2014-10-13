<?php
/**
 * Product: Forpost3 CMS
 * Author: Dmitriy Yuriev
 * Date: 21.08.13
 * License: AGPL3
 *
 * Library of helper functions.
 **/

class Lib
{
    public static function varDump()
    {

        echo '<p><pre><strong>';

        foreach (func_get_args() as $var) {
            var_dump($var);
        }

        echo '</strong></pre></p>';
    }

    public static function getHost()
    {
        if ($host = Input::SERVER('HTTP_X_FORWARDED_HOST')) {

            $elements = explode(',', $host);
            $host = trim(end($elements));
        } else {

            if (!$host = Input::SERVER('HTTP_HOST')) {

                if (!$host = Input::SERVER('SERVER_NAME')) {
                    $server_addr=Input::SERVER('SERVER_ADDR');
                    $host = !empty($server_addr) ? $server_addr : '';
                }
            }
        }

        $host = preg_replace('/:\d+$/', '', $host);

        return trim($host);
    }

    public static function getVarDump()
    {
        ob_start();
        foreach (func_get_args() as $var) {
            Lib::VarDump($var);
        }

        return ob_get_clean();
    }

    public static function addVarDump()
    {
        foreach (func_get_args() as $var) {

            DebugBar::getCollector('Var dumps')->addMessage($var);
        }
    }

    public static function parseAjaxForm($form_name)
    {
        $params=array();
        $items = array();
        $data=explode("&",Input::RAW_POST($form_name));

        foreach ($data as $input) {
            $input=explode("=",$input);

            if(strpos($input[0],'%5B%5D') !== false) { //if exists forms elements with "[]" postfix (like multiple checkboxes)
                $item_name = str_replace('%5B%5D', '', $input[0]);
                $items[$item_name][] = Lib::html2text(urldecode($input[1]));
            } else {
                $params[$input[0]] = Lib::html2text(trim(urldecode($input[1])));
            }
        }

        if(Lib::chkArr($items)) {
            $params = array_merge($params,$items);
        }

        return $params;
    }

    public static function getPHPDebugAssets()
    {
        return DebugBar::getJavascriptRenderer()->renderHead();
    }

    public static function getPHPDebugPanel()
    {
        return DebugBar::getJavascriptRenderer()->render();
    }

    public static function renderErrorsInfo()
    {
        foreach (\DYuriev\ErrorHandler::getAppErrors() as $error) {
            $error_info = '[' . $error['LEVEL'] . '] ' . $error['MESSAGE'] . ' in file ' . $error['FILE'] . ' on line ' . $error['LINE'];
            DebugBar::getCollector('Errors')->addMessage($error_info);
        }
    }
/*
    public static function getDebugInfo($start_time)
    {
        $end_time = microtime(true);
        $render_time=round($end_time - $start_time,4,PHP_ROUND_HALF_UP);

        $queries_time=Db::getTotalTime();
        $queries_stat=Db::getQueriesInfo();
        $queries_num=count($queries_stat);
        $mem_peak_usage=round((memory_get_peak_usage(true)/1024/1024),2,PHP_ROUND_HALF_UP)." Mb";
        $mem_usage=round((memory_get_usage(true)/1024/1024),2,PHP_ROUND_HALF_UP)." Mb";

        $checkpoints=Benchmark::finishBench();

        $debug_info= "<div style='clear:both'></div>
<div style='padding:5px; text-align:left; border:3px; border-style:solid; color: #000000; border-color:#cc00aa; background: #FFFFFF; overflow: hidden;'>
<h3>TIME INFO:</h3>
<pre>
Total page render time: <strong>$render_time</strong> seconds
Total SQL queries: <strong>$queries_num</strong>
Total SQL query exec time: <strong>$queries_time</strong> seconds
</pre>
<h3>MEMORY INFO:</h3>
<pre>Memory peak usage: <b>".$mem_peak_usage."</b><br>Memory usage: <b>".$mem_usage."</b><br></pre>
<h3>QUERIES STAT:</h3>
<p>".self::getVarDump($queries_stat)."</p>
<h3>SESSION INFO:</h3>
<p>".self::getVarDump($_SESSION)."</p>
<h3>REGISTRY INFO:</h3>
<p>".self::getVarDump(Registry::show())."</p>
<h3>ERRORS INFO:</h3>
<p>".self::getVarDump(\DYuriev\ErrorHandler::getAppErrors())."</p>
<h3>BENCHMARK INFO:</h3>
<p>".self::getVarDump($checkpoints)."</p>
</div>";

        return $debug_info;
    }
*/
    public static function chkArr($array)
    {
        return (isset($array) && is_array($array) && count($array) > 0) ? true : false;
    }

    public static function chkInt($integer)
    {
        return (isset($integer) && is_int($integer) && $integer > 0) ? true : false;
    }

    public static function chkStr($string)
    {
        return (isset($string) && is_string($string) && strlen(trim($string)) > 0) ? true : false;
    }

    public static function chkObj($object)
    {
        return (isset($object) && is_object($object)) ? true : false;
    }

    public static function redirect($address,$code=302)
    {
        if (Lib::chkArr($address)) {
            $redirect_url = Lib::mkLink($address);
        } elseif (empty($address)) {
            $redirect_url = Lib::mkLink(array(''));
        } else {
            $redirect_url = $address;
        }

        header('Location: '.$redirect_url, $code);
        exit();
    }

    public static function explodePath($path)
    {
        $arr = array();

        $arr2 = array_filter(explode('/',$path), function ($var) {
                if ($var == '0') $var='00'; /** dirty hack */
                return Lib::chkStr($var) ?  $var :  false;
            });

        foreach ($arr2 as $part) {
            $arr[]=$part;
        }

        return $arr;
    }

    public static function isMobileDevice()
    {
        $m_brs=array(
            'Alcatel',
            'Asus',
            'Android',
            'BlackBerry',
            'Ericsson',
            'Fly',
            'Huawei',
            'i-mate',
            'iPAQ',
            'iPhone',
            'iPod',
            'iPad',
            'LG-',
            'LGE-',
            'MDS_',
            'MOT-',
            'Nokia',
            'Palm',
            'Panasonic',
            'Pantech',
            'Philips',
            'Sagem',
            'Samsung',
            'Sharp',
            'SIE-',
            'Symbian',
            'Vodafone',
            'Voxtel',
            'WebOS',
            'ZTE-',
            'Windows CE',
            'Zune');

        foreach ($m_brs as $m_br) {
            if(FALSE !== strpos(Input::SERVER('HTTP_USER_AGENT'),$m_br)) return true;
        }

        return false;
    }

    public static function sanitize($str)
    {
        return htmlentities($str, ENT_QUOTES, 'UTF-8');
    }

    public static function unsanitize($str)
    {
        return html_entity_decode($str, ENT_QUOTES, 'UTF-8');
    }

    public static function html2text($input)
    {
        if (Lib::chkArr($input)) {
            array_walk($input, function (&$val,$key) {
                    $val = Lib::sanitize($val);
                });

            return $input;
        }

        return Lib::sanitize($input);
    }

    public static function text2html($input)
    {
        if (Lib::chkArr($input)) {
            array_walk($input, function (&$val,$key) {
                    $val = Lib::unsanitize($val);
                });

            return $input;
        }

        return Lib::unsanitize($input);
    }

    public static function genRandID($lenght = 40)
    {
        return substr(sha1(microtime().uniqid()),0,$lenght);
    }

    public static function chkFile($filename)
    {
        if (is_file($filename) && is_readable($filename)) return true;
        return false;
    }

    public static function chkFileWrite($filename)
    {
        if (is_file($filename) && is_writable($filename)) return true;
        return false;
    }

    public static function chkDir($dirname)
    {
        if (is_dir($dirname) && is_readable($dirname)) return true;
        return false;
    }

    public static function chkDirWrite($dirname)
    {
        if (is_dir($dirname) && is_writable($dirname)) return true;
        return false;
    }

    public static function chkArrKey($key,$arr)
    {
        if (Lib::chkArr($arr) && array_key_exists($key,$arr)) {
            return true;
        }

        return false;
    }

    public static function loadArrayFile($file)
    {
        if (Lib::chkFile($file)) {
            return include_once($file);
        }

        return false;
    }

    public static function mkPath($path_arr,$add_trail=false)
    {
        if (Lib::chkArr($path_arr)) {
            $path=ROOT_DIR.'/'.implode($path_arr,'/');
        } else {
            return false;
        }

        if ($add_trail) {
            $path.='/';
        }

        return $path;
    }

    public static function mkLink($link_arr,$add_trail=false)
    {
        if (Lib::chkArr($link_arr)) {
            $proto=Input::isHTTPS() ? 'https://' : 'http://';
            $link=$proto.Config::get('app.main_domain').'/'.implode($link_arr,'/');
        } else {
            return false;
        }

        if ($add_trail) {
            $link.='/';
        }

        return $link;
    }

    public static function getFilesFromDir($dir)
    {
        $files = array();
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_dir($dir.DS.$file)) {
                        $dir2 = $dir.DS.$file;

                        $files[] = Lib::getFilesFromDir($dir2);
                    } else {
                        $files[] = $dir.DS.$file;

                    }
                }
            }
            closedir($handle);
        }

        return $files;
    }

    public static function array_flat($array)
    {
        foreach ($array as $a) {
            if (is_array($a)) {
                $tmp = array_merge($a, Lib::array_flat($a));
            } else {
                $tmp[] = $a;
            }
        }

        return $tmp;
    }

    public static function netCompare($networks, $ip)
    {
        $flag = false;

        foreach ($networks as $network) {

            if (false === strpos($network, '/')) {
                $network = $network . '/32';
            }

            $ip_arr = explode('/', $network);
            $network_long = ip2long($ip_arr[0]);
            $x = ip2long($ip_arr[1]);
            $mask = long2ip($x) == $ip_arr[1] ? $x : 0xffffffff << (32 - $ip_arr[1]);
            $ip_long = ip2long($ip);

            if(($ip_long & $mask) == ($network_long & $mask)) {
                $flag = true;
            }
        }

        return $flag;
    }

    public static function httpAuthBasic($callback)
    {
        $user=trim(Input::SERVER('PHP_AUTH_USER'));
        $password=trim(Input::SERVER('PHP_AUTH_PW'));
        if (empty($user) || empty($password)) return false;
        return call_user_func($callback,$user,$password);
    }

    //mode can be: 0 - digits, 1 - letters, 2 - letters and digits, 3 - letters, digits and signs
    public static function getRandStr($str_len,$mode=1)
    {
        $res=array(); $str='';
        $letters='AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
        $digits='0123456789';
        $signs='!@#$%^&*()_-=+[]{};:|,.?';

        switch ($mode) {
            case 0:
                $str=$digits;
                break;

            case 1:
                $str=$letters;
                break;

            case 2:
                $str=$letters.$digits;
                break;

            case 3:
                $str=$letters.$digits.$signs;
                break;

            default:
                trigger_error('getRandStr: Invalid mode argument',E_ERROR);
                break;
        }

        $sym_arr=str_split($str);
        shuffle($sym_arr);
        $len=count($sym_arr);

        for ($i=0;$i<$str_len;$i++) {
            $rand=mt_rand(0,$len-1);
            $res[]=$sym_arr[$rand];
        }

        return implode('',$res);
    }

    public static function getRandInt($len)
    {
        return Lib::getRandStr($len,0);
    }

    public static function setCookie($name,$value=null,$expire=null,$path=null,$domain=null,$secure=null,$http_only=null)
    {
        $expire=is_null($expire) ? Config::get('cookie.default_expire') : $expire;
        $path=is_null($path) ? Config::get('cookie.default_path') : $path;
        $domain=is_null($domain) ? Config::get('cookie.default_domain') : $domain;
        $http_only=is_null($http_only) ? Config::get('cookie.only_http') : $http_only;
        setcookie($name,$value,$expire,$path,$domain,$secure,$http_only);
    }

}
