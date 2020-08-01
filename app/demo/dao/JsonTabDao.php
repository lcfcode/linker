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
}