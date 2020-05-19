<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */

namespace app\demo\controller;

use app\demo\service\EmployeeService;
use app\demo\service\ExamService;
use app\demo\service\StudentService;
use swap\core\Controller;
use swap\linker\View;

class DemoDeController extends Controller
{
    public function indexAction()
    {
        $data = [
            'times' => time(),
            'date' => date('Y-m-d H:i:s'),
            'test_a' => [
                'times' => time(),
                'date' => date('Y-m-d H:i:s'),
            ],
            'test_b' => [
                'times' => 12.3,
                'date' => date('Y-m-d H:i:s'),
                'b' => [
                    'times' => time(),
                    'date' => date('Y-m-d H:i:s'),
                    's' => null,
                    'x' => '',
                ],
            ],
        ];
        $view = new View($data);
//        $view->setLayout('Layout2')->setView('test');
//        $view->setView('index2','Index2');
        return $view;
    }

    public function TndeX()
    {
        return new View();
    }
}