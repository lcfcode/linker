<?php

namespace app\api\dao;

use Swap\Core\Dao;

class EmployeeDao extends Dao
{
    public function setConnect()
    {
        return 'db_2';
    }

    //表字段
    public function fieldArr()
    {
        return [
            'num' => 'num', 'id' => 'id', 'name' => 'name', 'age' => 'age', 'sex' => 'sex',
            'homeaddr' => 'homeaddr',
        ];
    }
    
    //表名
    public function tabName()
    {
        return 'employee';
    }
}