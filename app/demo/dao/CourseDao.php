<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */

namespace app\demo\dao;

use swap\core\Dao;

class CourseDao extends Dao
{
    //表名
    public function tabName()
    {
        return 'course';
    }

    //表字段
    public function fieldArr()
    {
        return [
            'id' => 'id', 'course_name' => 'course_name', 'create_time' => 'create_time', 'update_time' => 'update_time',
        ];
    }

}