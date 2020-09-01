<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */

namespace app\demo\dao;

use swap\core\Dao;

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
}