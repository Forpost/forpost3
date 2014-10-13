<?php
/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 11.06.14
 * Time: 20:21
 */

class AEloquentModel extends Illuminate\Database\Eloquent\Model
{
    /*private $data;
    public $timestamps = false;
    //public $table = null;
    public $primaryKey = null;*/

    public function getData($key=null)
    {
        if (is_null($key)) {
            return $this->data;
        } else {
            return $this->data[$key];
        }
    }

    public function setTable($table_name)
    {
        $this->table=$table_name;

        return $this;
    }

    public function setPrimaryKey($primary_key)
    {
        $this->primaryKey=$primary_key;

        return $this;
    }

    protected function addData($key,$value)
    {
        $this->data[$key]=$value;
    }

    protected function delData($key)
    {
        unset($this->data[$key]);
    }

    public function __get($alias)
    {
        Container::bind($alias);

        return Container::make($alias);
    }

}
