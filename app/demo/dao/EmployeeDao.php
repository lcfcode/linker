<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */
    
namespace app\demo\dao;

use swap\core\Dao;

class EmployeeDao extends Dao
{
    /**
     * @inheritDoc
     */
    public function connectInfo(): array
    {
        return [
            'table' => $this->tabName(),
            'default_id' => $this->defaultId(),
            'field' => $this->fieldArr(),
            'db' => 'db_2'
        ];
    }

    //表名
    public function tabName()
    {
        return 'employee';
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
            'num' => 'num', 'id' => 'id', 'name' => 'name', 'age' => 'age', 'sex' => 'sex',
            'homeaddr' => 'homeaddr',
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