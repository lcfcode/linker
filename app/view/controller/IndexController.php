<?php

namespace app\view\controller;

use app\ComFun;
use app\view\service\EmployeeService;
use app\view\service\ExamService;
use app\view\service\StudentService;
use Swap\Core\Controller;
use Swap\Utils\VerifyCode;
use Swap\View\View;

class IndexController extends Controller
{

    public function indexAction()
    {
//        p($this->getConfigValue('user_config'));
//        echo $this->getUtils()->getIp();
//        $redis=$this->getRedis();
//        $redis->set('lian','xxxxx');
//        print_r($redis->get('lian'));
        //返回给页面的数据放到 View 的构造内
//        print_r($this->getConfigValue('user_config'));
//        $this->logs($this->getConfigValue('user_config'));
//        p($this->app->config());
//        $this->logs(Helper::config(true));
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
//        $studentService = new StudentService();
//        $result = $studentService->getAll();
        $view = new View($data);
//        $view->setLayout('layout2');
//        $view->setView('test');
//        $view->setView('index2','Index2');
//        $view->closeLayout();
        return $view;
    }

    //dbDemo
    public function dbDemoAction()
    {
        $studentService = new StudentService($this->app);

        //根据id查询数据
        $userResultId = $studentService->getId();
//        p($userResultId);die;
        $tempData = $studentService->selectInfo();
        $tempData = $studentService->sortOrder();
//        p($tempData);
        //查看成绩
        $examService = new ExamService($this->app);
        $source = $examService->getExam();
        $examTmp = $examService->getExam2();
//        $tt=$examService->test();
        //查询--第二个数据库的数据
        $employeeService = new EmployeeService($this->app);
        $employeeResult = $employeeService->dao()->selectAll();
        /** TODO::更多请阅读 @var \Swap\Utils\MysqliClass */

        //模糊查询方法
        $likeResult = $studentService->likes('祁');
        //测试redis的
//        $redis=$this->getRedis();
//        $redis->set('demo_key','lngco');
        $retArr = [
            'user_result_id' => $userResultId,
            'like_result' => $likeResult,
            'employee' => $employeeResult,
            'source' => $source,
            'title' => 'view-demo'
        ];
        return new View($retArr);
    }


    public function dbPageAction()
    {
        $page = $this->get('page', 1);
        $listNum = $this->get('list_num', 20);
        if ($listNum > 50) {
            $listNum = 20;
        }
        $stu = new StudentService($this->app);
        $count = $stu->dbPageCount();
        $result = $stu->dbPage($page, $listNum);
        return new View(['count' => $count, 'list_num' => $listNum, 'result' => $result]);
    }

    //test
    public function testAction()
    {
        $s = new StudentService($this->app);
//        $resutl = $s->insertMulti();
//        $resutl=$s->transaction();
//        var_dump($resutl);
        $view = new View();
        $view->setLayout('layout2')->setView('index', 'Index2');//设置别的页面
        return $view;
    }

    public function verifyAction()
    {
        $verifyCode = new VerifyCode();
        $code = $verifyCode->getCode();//获取验证码
        $img = $verifyCode->verifyPng();
        //输出验证码
        header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header("Content-type:image/png;");
        return $img;
    }

    public function verify1Action()
    {
        $verifyCode = new VerifyCode();
        $code = $verifyCode->getCode();//获取验证码
        $img = $verifyCode->verifyPng();
        //输出bash64验证码
        $base64 = 'data:image/png;base64,' . base64_encode($img);
        $view = new View(['data' => $base64]);
        $view->closeLayout();
        return $view;
    }

    public function testPostAction()
    {
        return new View();
    }

    public function postAction()
    {
        if (!$this->isPost()) {
            return ComFun::fail('不支持的请求');
        }
        $name = $this->post('name');
        $index = $this->post('index');
        $this->logs([$name, $index]);
        $this->logs($_POST);
        return ComFun::succeed();
    }

    public function ttAction()
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
        return ComFun::succeed($data);
    }
}