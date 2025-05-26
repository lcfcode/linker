<?php

namespace app\api\service;

use Swap\Core\Service;
use app\api\dao\ExamDao;

class ExamService extends Service
{
    private $examDao = null;

    /**
     * @return ExamDao
     */
    public function dao()
    {
        if ($this->examDao === null) {
            $this->examDao = new ExamDao($this->app);
        }
        return $this->examDao;
    }
}