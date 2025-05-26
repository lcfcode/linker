<?php

namespace app\view\service;

use app\view\dao\ExamDao;
use Swap\Core\Service;
use app\view\dao\StudentDao;

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


    //demo
    public function getId()
    {
        //单例测试
//        $n1=new StudentDao();
//        var_dump($n1);
//        echo '<hr>';
//        $n2=new JsonTabDao();
//        var_dump($n2);
//        echo '<hr>';
//        $n3=new StudentDao();
//        var_dump($n3);
//        echo '<hr>';
//        $n4=new JsonTabDao();
//        var_dump($n4);
//        echo '<hr>';
//        $n5=new StudentDao();
//        var_dump($n5);
//        echo '<hr>';
//        $n6=new JsonTabDao();
//        var_dump($n6);
//        echo '<hr>';
        $result = $this->dao()->selectId('907bc730dd04b0ab14ea8aa67a71ae18');
//        var_dump($this->dao());
//        print_r($this->dao()->getLastSql());//用于获取执行的sql和sql参数
        return $result;
    }

    //demo
    public function likes($content)
    {
        //test logs
//        $userConfig = Helper::config('user_config');
//        $this->logs('获取配置文件信息', $userConfig);

        return $this->dao()->like('name', $content, [], false, ['age' => 'desc'], 1, 5);
    }

    //api
    public function getAll()
    {
        return $this->dao()->selectAll([], 1, 2);
    }
    //TODO dao中所有方法中只要用到where的查询基本如下操作  等于 大于 大于等 小于 小于等于 不等于
    //TODO 只要是查询非等于情况的在key值里面添加英文双冒号(::)，其后跟上对应的符号即可
    public function sortOrder()
    {
        //查询age的值等于25的数据
//        return $this->dao()->select(['age'=>25]);
        //查询age的值大于等于25的数据
//        return $this->dao()->select(['age::>='=>25]);
        //查询age的值小于等于25的数据
//        return $this->dao()->select(['age::<='=>25]);
        //查询age的值不等于25的数据
        $result = $this->dao()->selects(['age::<>' => 25, 'age::<' => 40]);//或者是select(['age::!='=>25])
//        print_r($this->dao()->getLastSql());//用于获取执行的sql和sql参数
        return $result;
    }

    public function selectInfo()
    {
        return $this->dao()->selects(['age::>=' => 25]);
    }

    //测试多条插入数据
    public function insertMulti()
    {
        $iDataM = [];
        array_push($iDataM, []);
        for ($i = 0; $i < 10; $i++) {
            $iDataM[] = [
                'id' => $this->uuid(),
                'name' => 'name_' . mt_rand(1, 9),
                'phone' => mt_rand(1, 139999999),
                'job_number' => $i + 10,
                'card_id' => mt_rand(1, 139999999),
                'sex' => mt_rand(0, 2),
                'age' => mt_rand(10, 90),
                'birthday' => '',
                'head_img' => '',
                'mail' => '',
                'specialty' => '',
                'area' => '',
                'qq' => mt_rand(1000, 99999),
                'is_enable' => '1',
                'source' => mt_rand(10, 90),
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
            ];
        }
        array_push($iDataM, []);
        //多条分开插入
//        $ret=$this->dao()->insertMulti($iDataM);
        //多条一起插入
        $ret = $this->dao()->insertMultiple($iDataM);
        return $ret;
    }

    //事务测试
    public function transaction()
    {
        $this->dao()->beginTransaction();
        try {
            $name = '作者测试' . mt_rand(1, 9);
            $score = mt_rand(80, 99);
            $ret = $this->dao()->updateId('c83ebf313a4e89ce5b8b59f24d4fdf26', ['name' => $name]);
            $res = $this->exameDao()->update(['score' => $score], ['student_id' => 'c83ebf313a4e89ce5b8b59f24d4fdf26']);
            print_r(['name' => $name, 'score' => $score]);
//            ii //假设这里异常
            if ($ret && $res) {
                $this->dao()->commitTransaction();
                return true;
            }
            $this->dao()->rollbackTransaction();
            return false;
        } catch (\Exception $e) {
            $this->dao()->rollbackTransaction();
            return false;
        }
    }

    /**
     * @return ExamDao
     * @user LCF
     * @date
     */
    public function exameDao(): ExamDao
    {
        if ($this->examDao === null) {
            $this->examDao = new ExamDao($this->app);
        }
        return $this->examDao;
    }

    public function dbPage($pageNow, $pageSize = 5)
    {
        return $this->dao()->selectAll([], $pageNow, $pageSize);
    }

    public function dbPageCount()
    {
        return $this->dao()->count();
    }
}