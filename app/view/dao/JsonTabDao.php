<?php

namespace app\view\dao;

use Swap\Core\Dao;

class JsonTabDao extends Dao
{
    public function setConnect()
    {
        return 'db_2';
    }

    //表字段
    public function fieldArr()
    {
        return [
            'id' => 'id', 'data' => 'data',
        ];
    }
    
    //表名
    public function tabName()
    {
        return 'json_tab';
    }
}