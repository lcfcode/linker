<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */

namespace app\demo\service;

use app\demo\dao\EmployeeDao;
use swap\core\Service;

class EmployeeService extends Service
{
    private $employeeDao = null;

    /**
     * @return EmployeeDao
     */
    public function dao()
    {
        if ($this->employeeDao === null) {
            $this->employeeDao = new EmployeeDao($this->app);
        }
        return $this->employeeDao;
    }

    //demo
    public function getAll()
    {
        return $this->dao()->selectAll();
    }
}