<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */
    
namespace app\demo\dao;

use swap\core\Dao;

class CourseDao extends Dao
{
    /**
     * @inheritDoc
     */
    public function connectInfo(): array
    {
        return [
            'table' => $this->tabName(),
            'default_id' => $this->defaultId(),
            'field' => $this->fieldArr()
        ];
    }

    //表名
    public function tabName()
    {
        return 'course';
    }

    //默认主键字段
    public function defaultId()
    {
        return 'id';
    }

    //表字段
    public function fieldArr()
    {
        return [
            'id' => 'id', 'course_name' => 'course_name', 'create_time' => 'create_time', 'update_time' => 'update_time',
        ];
    }

    //表字段,直接返回字符串或者对应字段生成的别名
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