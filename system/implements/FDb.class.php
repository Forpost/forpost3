<?php

class FDb implements IDb
{
    protected $total_time = 0;
    protected $args = array();
    protected $sql = null;
    protected $pdo = null;
    protected $pdo_stmt = null;
    protected $queries = array();
    protected $num_rows = null;
    protected $cache_enable = false;
    protected $cache_key = null;
    protected $cache_data = null;

    public function __construct()
    {
        $this->pdo = Container::make('pdo');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        if ($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
            $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
            $this->pdo->query("SET NAMES 'utf8'");
        }
    }

    /** Prepares sql query for PDO::execute */
    public function prepare($sql, $cache_enable = false)
    {
        $this->cache_enable = $cache_enable;
        $this->sql = $sql;
        $this->pdo_stmt = $this->pdo->prepare($sql);

        return $this;
    }

    /** Executes prepared sql query to DB server. */
    public function execute($params = array())
    {
        $this->cache_key = $this->sql . implode('', $params);

        if ($this->cache_enable && SQLCache::hasKey($this->cache_key)) {
            return $this;
        }

        $start_time = microtime(true);
        $this->pdo_stmt->closeCursor();
        $this->pdo_stmt->execute($params);
        $this->queries[] = $this->sql;
        $end_time = microtime(true);
        $this->total_time += $end_time - $start_time;

        return $this;
    }

    /** Executes sql query to DB server without preparing. */
    public function query($sql, $cache_enable = false)
    {
        $this->cache_key = $sql;
        $this->cache_enable = $cache_enable;

        if ($this->cache_enable && SQLCache::hasKey($this->cache_key)) {
            return $this;
        }

        $start_time = microtime(true);

        if (!is_null($this->pdo_stmt)) {
            $this->pdo_stmt->closeCursor();
        }

        $this->pdo_stmt = $this->pdo->query($sql);
        $this->queries[] = $sql;
        $end_time = microtime(true);
        $this->total_time += $end_time - $start_time;

        return $this;
    }

    /** Resets cache state to default */
    protected function resetCacheState()
    {
        $this->cache_enable = false;
        unset($this->cache_key, $this->cache_data);
    }

    /** Fetches result from DB server into associative array */
    public function fetchAssoc()
    {
        if ($this->cache_enable && SQLCache::hasKey($this->cache_key)) {
            $data = SQLCache::get($this->cache_key);
            $this->num_rows = count($data);
            $this->resetCacheState();

            return $data;
        } else {
            $data = $this->pdo_stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->num_rows = count($data);

            if ($this->cache_enable) {
                SQLCache::set($this->cache_key, $data);
                $this->resetCacheState();
            }

            if (Lib::chkArr($data)) {
                return $data;
            }

            return false;
        }
    }

    /** Fetches result from DB server into numeric array */
    public function fetchRow()
    {
        if ($this->cache_enable && SQLCache::hasKey($this->cache_key)) {
            $data = SQLCache::get($this->cache_key);
            $this->num_rows = count($data);
            $this->resetCacheState();

            return $data;
        } else {
            $data = $this->pdo_stmt->fetchAll(PDO::FETCH_NUM);
            $this->num_rows = count($data);

            if ($this->cache_enable) {
                SQLCache::set($this->cache_key, $data);
                $this->resetCacheState();
            }

            if (Lib::chkArr($data)) {
                return $data;
            }

            return false;
        }
    }

    public function sqlResult()
    {
        return $this->pdo_stmt->fetchColumn();
    }

    public function safeString($str)
    {
        return $this->pdo->quote($str);
    }

    public function safeInt($int)
    {
        return (int) $int;
    }

    public function lastID()
    {
        return $this->pdo->lastInsertId();
    }

    public function numRows()
    {
        $num_rows = $this->num_rows;
        unset($this->num_rows);

        return $num_rows;
    }

    public function affectedRows()
    {
        return $this->pdo_stmt->rowCount();
    }

    public function dbError()
    {
        $err_info = $this->pdo_stmt->errorInfo();

        return $err_info[2];
    }

    public function getTotalTime()
    {
        return round($this->total_time, 6, PHP_ROUND_HALF_UP);
    }

    public function getQueriesInfo()
    {
        return $this->queries;
    }
}
