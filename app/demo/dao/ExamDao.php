<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */

namespace app\demo\dao;

use swap\core\Dao;

class ExamDao extends Dao
{
    //表字段
    public function fieldArr()
    {
        return [
            'id' => 'id', 'student_id' => 'student_id', 'course_id' => 'course_id', 'score' => 'score', 'create_time' => 'create_time',
            'update_time' => 'update_time',
        ];
    }
}