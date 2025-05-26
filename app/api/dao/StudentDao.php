<?php

namespace app\api\dao;

use Swap\Core\Dao;

class StudentDao extends Dao
{
    //表字段
    public function fieldArr()
    {
        return [
            'id' => 'id', 'name' => 'name', 'phone' => 'phone', 'job_number' => 'job_number', 'card_id' => 'card_id',
            'sex' => 'sex', 'age' => 'age', 'birthday' => 'birthday', 'head_img' => 'head_img', 'mail' => 'mail',
            'specialty' => 'specialty', 'area' => 'area', 'qq' => 'qq', 'is_enable' => 'is_enable', 'source' => 'source',
            'create_time' => 'create_time', 'update_time' => 'update_time',
        ];
    }
    
    //表名
    public function tabName()
    {
        return 'student';
    }
}