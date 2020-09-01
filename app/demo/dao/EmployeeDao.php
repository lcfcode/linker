<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */

namespace app\demo\dao;

use swap\core\Dao;

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
}