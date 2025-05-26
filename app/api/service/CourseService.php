<?php

namespace app\api\service;

use Swap\Core\Service;
use app\api\dao\CourseDao;

class CourseService extends Service
{
    private $courseDao = null;

    /**
     * @return CourseDao
     */
    public function dao()
    {
        if ($this->courseDao === null) {
            $this->courseDao = new CourseDao($this->app);
        }
        return $this->courseDao;
    }
}