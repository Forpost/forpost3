<?php
/**
 * Product: Forpost3 CMS
 * Author: Dmitriy Yuriev
 * Date: 21.08.13
 * License: AGPL3
 *
 * Interface describes access to database.
 **/
interface IDb
{
    public function safeString($str);
    public function safeInt($int);
    public function query($sql);
    public function prepare($sql);
    public function execute($params=array());
    public function fetchAssoc();
    public function fetchRow();
    public function numRows();
    public function dbError();
    public function lastID();
}
