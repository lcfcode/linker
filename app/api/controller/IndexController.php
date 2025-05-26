<?php

namespace app\api\controller;

use app\api\service\EmployeeService;
use app\api\service\StudentService;
use app\ComFun;
use Swap\Core\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $studentService = new StudentService($this->app);
        $student = $studentService->dao()->selectAll();
        $employeeService = new EmployeeService($this->app);
        $employee = $employeeService->dao()->selectAll();
        $data = ['student' => $student, 'employee' => $employee];
        return ComFun::succeed($data);
    }

    public function apiAction()
    {
        return ComFun::succeed(['date' => date('Y-m-d H:i:s')]);
//        return ComFun::fail('哈哈，错误了');
    }
}