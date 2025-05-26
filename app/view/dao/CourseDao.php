<?php

namespace app\view\dao;

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