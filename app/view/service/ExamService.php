<?php

namespace app\view\service;

use app\view\dao\CourseDao;
use app\view\dao\StudentDao;
use Swap\Core\Service;
use app\view\dao\ExamDao;

class ExamService extends Service
{
    private $examDao;
    private $studentDao;
    private $courseDao;

    /**
     * @return ExamDao
     */
    public function dao()
    {
        if ($this->examDao == null) {
            $this->examDao = new ExamDao($this->app);
        }
        return $this->examDao;
    }


    /**
     * @return StudentDao
     */
    public function studentDao()
    {
        if ($this->studentDao === null) {
            $this->studentDao = new StudentDao($this->app);
        }
        return $this->studentDao;
    }

    /**
     * @return CourseDao
     */
    public function courseDao()
    {

        if ($this->courseDao === null) {
            $this->courseDao = new CourseDao($this->app);
        }
        return $this->courseDao;
    }

    //demo
    public function getExam()
    {
        return $this->dao()->query("select s.name,s.id,s.job_number,c.course_name,e.score from student as s LEFT JOIN exam as e on s.id=e.student_id LEFT JOIN course as c ON e.course_id=c.id WHERE e.score>=98 ORDER BY e.score desc");
    }

    //
    public function getExam2()
    {
        $student = $this->studentDao()->tabName();
        $exam = $this->dao()->tabName();
        $course = $this->courseDao()->tabName();

        $studentField = $this->studentDao()->field('s');
        $examField = $this->dao()->field('e');
        $courseField = $this->courseDao()->field('c');

        $sql = "select {$studentField},{$examField},{$courseField} from {$student} as s LEFT JOIN {$exam} as e ON s.id=e.student_id LEFT JOIN {$course} as c ON e.course_id=c.id WHERE e.score>=98 ORDER BY e.score desc";
//        echo $sql;die;
        $result = $this->dao()->query($sql);
//        print_r($this->dao()->getLastSql());
        return $result;
    }

    //
    public function test()
    {
        $sql1 = "select id,student_id,course_id,score,create_time,update_time from exam where score=? and id=?";
        $sql1 = "select id,student_id,course_id,score,create_time,update_time from exam where score=?";
        $sql1 = "select id,student_id,course_id,score,create_time,update_time from exam where score='81--' and id='05ef43fad8082f22b4c7db689906e2d2'";
//        $p=["81'--",'05ef43fad8082f22b4c7db689906e2d2'];
        $p = ["81--"];
//        $result=$this->dao()->query($sql1,$p);
        $result = $this->dao()->query($sql1);
//        $result=$this->dao()->selectAll();
//        print_r($this->dao()->getLastSql());
//        print_r($result);
        exit;
    }
}