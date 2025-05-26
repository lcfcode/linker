<?php

namespace app\api\dao;

use Swap\Core\Dao;

class CourseDao extends Dao
{
    //表字段
    public function fieldArr()
    {
        return [
            'id' => 'id', 'course_name' => 'course_name', 'create_time' => 'create_time', 'update_time' => 'update_time',
        ];
    }
    
    //表名
    public function tabName()
    {
        return 'course';
    }
}