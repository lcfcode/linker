<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */
    
namespace app\demo\service;

use app\demo\dao\CourseDao;

class CourseService
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