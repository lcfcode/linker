<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */
    
namespace app\demo\service;

use app\demo\dao\CourseDao;
use swap\core\Service;

class CourseService extends Service
{
    private $courseDao = null;

    /**
     * @return CourseDao
     */
    public function dao()
    {
        if ($this->courseDao === null) {
            $this->courseDao = new CourseDao();
        }
        return $this->courseDao;
    }
}