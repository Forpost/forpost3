<?php
/*
* @author: Dmitriy Yuriev <coolkid00@gmail.com>
* @product: Forpost3
* @version: 3.1
* @release date: 04.06.2014
* @development started: 21.08.2013
* @license: GNU AGPLv3
*
* Raw HTTP request class.
*/

if (!defined('FORPOST_VALID')) {
    header('HTTP/1.1 403 Forbidden', 403);
    header('X-Powered-By: Apache 2.2.22');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1>
    <p>You don\'t have permission to access ' . basename(__FILE__) . ' on this server.</p></body></html>');
}

class FHttpQuery
{
    protected $connection = null;
    protected $read_buffer_size = 1024;
    protected $connection_timeout = 30;
    protected $socket_timeout = 5;
    protected $content = '';
    
    public function init($host, $port, $is_ssl = false, $connection_timeout = 30)
    {
        $this->connection_timeout = $connection_timeout;

        if ($is_ssl) {
            $host = 'ssl://' . $host;
        }
        
        $this->connect($host, $port);
        
        return $this;
    }
    
    protected function connect($host, $port)
    {
        $this->connection = fsockopen($host, $port, $error_number, $error_string, $this->connection_timeout);        

        if (!$this->connection) {
            throw new FException("An error occurred during connection: \"$errstr\", error number: $errno");
        }
        
        stream_set_timeout($this->connection, $this->socket_timeout);
    }

    public function send($data)
    {
        
        if (!$this->connection || !is_resource($this->connection)) {
            $this->connect();
        }
        
        fputs($this->connection, $data, strlen($data));       
        $this->content = '';
        
        while (!feof($this->connection)) { 
            $this->content  .= fread($this->connection, $this->read_buffer_size);
            $stream_meta_data = stream_get_meta_data($this->connection);
            
            /*if($stream_meta_data['unread_bytes'] <= 0) {
                break;
            }*/
        }
        
        fclose($this->connection);

        return $this->content;
    }
}
