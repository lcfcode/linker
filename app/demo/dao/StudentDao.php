<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */
    
namespace app\demo\dao;

use swap\core\Dao;

class StudentDao extends Dao
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
        ];
    }

    //表名
    public function tabName()
    {
        return 'student';
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
            'id' => 'id', 'name' => 'name', 'phone' => 'phone', 'job_number' => 'job_number', 'card_id' => 'card_id',
            'sex' => 'sex', 'age' => 'age', 'birthday' => 'birthday', 'head_img' => 'head_img', 'mail' => 'mail',
            'specialty' => 'specialty', 'area' => 'area', 'qq' => 'qq', 'is_enable' => 'is_enable', 'source' => 'source',
            'create_time' => 'create_time', 'update_time' => 'update_time',
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