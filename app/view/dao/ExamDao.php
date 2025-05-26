<?php

namespace app\view\dao;

use Swap\Core\Dao;

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
    
    //表名
    public function tabName()
    {
        return 'exam';
    }

    public function field($prefix = '', $tabAlias = '')
    {
        if (empty($prefix)) {
            return implode(',', $this->fieldArr());
        }
        if (empty($tabAlias)) {
            $tabAlias = $prefix;
        }
        $str = '';
        $fieldArr = $this->fieldArr();
        foreach ($fieldArr as $key => $row) {
            $str .= ',' . $tabAlias . '.' . $row . ' as ' . $prefix . $key;
        }
        return trim($str, ',');
    }
}