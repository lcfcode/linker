<?php

namespace app\view\service;

use Swap\Core\Service;
use app\view\dao\EmployeeDao;

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
}