<?php

namespace app\api\service;

use Swap\Core\Service;
use app\api\dao\StudentDao;

class StudentService extends Service
{
    private $studentDao = null;

    /**
     * @return StudentDao
     */
    public function dao()
    {
        if ($this->studentDao === null) {
            $this->studentDao = new StudentDao($this->app);
        }
        return $this->studentDao;
    }
}