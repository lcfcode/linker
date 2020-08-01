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
use swap\core\View;
use swap\utils\ApiTool;
use swap\utils\Helper;
use swap\utils\VerifyCode;

class IndexController extends Controller
{
    public function indexAction()
    {
//        Helper::that();
//        p(Helper::config(true));
//        p(Helper::getUrl());
//        p($this->getConfigValue('user_config'));
//        echo $this->getUtils()->getIp();
//        $redis=$this->getRedis();
//        $redis->set('lian','xxxxx');
//        print_r($redis->get('lian'));
        //返回给页面的数据放到 View 的构造内
//        print_r($this->getConfigValue('user_config'));
        $this->logs($this->getConfigValue('user_config'));
        $this->logs(Helper::config(true));
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
//        $view->setLayout('layout2')->setView('test');
//        $view->setView('index2','Index2');
        return $view;
    }

    //dbDemo
    public function dbDemoAction()
    {
        $studentService = new StudentService();

        //根据id查询数据
        $userResultId = $studentService->getId();
//        p($userResultId);die;
        $tempData = $studentService->selectInfo();
        $tempData = $studentService->sortOrder();
//        print_r($tempData);
        //查看成绩
        $examService = new ExamService();
        $source = $examService->getExam();
        $examTmp = $examService->getExam2();
//        $tt=$examService->test();
        //查询--第二个数据库的数据
        $employeeService = new EmployeeService();
        $employeeResult = $employeeService->getAll();
        /** TODO::更多请阅读 @var \swap\utils\MysqliClass */

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
            'title' => 'woh'
        ];
        return new View($retArr);
    }

    //api test
    public function apiAction()
    {
        $studentService = new StudentService();
        $result = $studentService->getAll();
//        return ApiTool::success($result);
        $view = new View([
            'code' => 200,
            'msg' => '成功',
            'data' => $result
        ]);
//        return $view->api();
        $view->api();
        return $view;
    }

    public function dbPageAction()
    {
        $page = $this->get('page', 1);
        $listNum = $this->get('list_num', 20);
        if ($listNum > 50) {
            $listNum = 20;
        }
        $stu = new StudentService();
        $count = $stu->dbPageCount();
        $result = $stu->dbPage($page, $listNum);
        return new View(['count' => $count, 'list_num' => $listNum, 'result' => $result]);
    }

    //test
    public function testAction()
    {
        $s = new StudentService();
//        $resutl = $s->insertMulti();
//        $resutl=$s->transaction();
//        var_dump($resutl);
        $view = new View();
        $view->setLayout('layout2')->setView('index', 'Index2');//设置跳转到别的页面
        return $view;
    }

    public function verifyAction()
    {
        $verifyCode = new VerifyCode();
        $code = $verifyCode->getCode();//获取验证码
        $verifyCode->verifyPng();//输出验证码
    }

    public function testPostAction()
    {
        return new View();
    }

    public function postAction()
    {
        if (!$this->isPost()) {
            return $this->msg(100, '不支持的请求');
        }
        $name = $this->post('name');
        $index = $this->post('index');
        $this->logs([$name, $index]);
        $this->logs($_POST);
        return $this->msg('200', '搞定');
    }
}