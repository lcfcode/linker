<?php
/**
 * Created by PhpStorm.
 * User: AF
 * Date: 2016/11/19
 * Time: 22:01
 */
$config = include "../../config/dev.php";

$config = $config['db2'];
$char = $config['charset'];
$conn = new \mysqli($config['host'], $config['user'], $config['password'], $config['database'], $config['port']);
if ($conn->connect_errno) {
    echo 'database link failed ! please configure the global.config.php file under the config folder ...' . PHP_EOL;
    exit;
}
echo "database link success ..." . PHP_EOL;
$conn->set_charset($char);

$spec = ['电信工程及管理', '电子与计算机工程', '电子信息工程', '电子信息科学与技术', '电子商务', '电子商务及法律', '电子封装技术', '电子科学与技术', '电影学', '电气工程与智能控制', '电气工程及其自动化', '电波传播与天线', '电磁场与无线技术', '信息与计算科学', '信息安全', '信息对抗技术', '信息工程', '信息管理与信息系统', '信息资源管理', '信用管理', '智能电网信息工程', '智能科学与技术', '空间信息与数字技术', '空间科学与技术', '网络与新媒体', '网络安全与执法', '网络工程', '网络空间安全', '软件工程', '计算机科学与技术', '数字出版', '数字媒体技术', '数字媒体艺术', '数学与应用数学', '数据科学与大数据技术', '数理基础科学'];

$studentInsert = "INSERT INTO student(id,name,phone,job_number,card_id,sex,age,birthday,mail,specialty,qq,is_enable,source,create_time,update_time) VALUES ";

$jobNum = 201809020001;
for ($j = 0; $j < 30; $j++) {
    $pattern = '赵钱孙李周吴郑王冯陈褚卫蒋沈韩杨朱秦尤许何吕施张孔曹严华金魏陶姜戚谢邹喻柏水窦章云苏潘葛奚范彭郎鲁韦昌马苗凤花方俞任袁柳酆鲍史唐费廉岑薛雷贺倪汤滕殷罗毕郝邬安常乐于时傅皮卞齐康伍余元卜顾孟平黄和穆萧尹姚邵湛汪祁毛禹狄米贝明臧计伏成戴谈宋茅庞熊纪舒屈项祝董梁杜阮蓝闵席季麻强贾路娄危江童颜郭梅盛林刁锺徐邱骆高夏蔡田樊胡凌霍虞万支柯昝管卢莫经房裘缪干解应宗丁宣贲邓郁单杭洪包诸左石崔吉钮龚程嵇邢滑裴陆荣翁荀羊於惠甄麴家封芮羿储靳汲邴糜松井段富巫乌焦巴弓牧隗山谷车侯宓蓬全郗班仰秋仲伊宫宁仇栾暴甘钭历戎祖武符刘景詹束龙叶幸司韶郜黎蓟溥印宿白怀蒲邰从鄂索咸籍赖卓蔺屠蒙池乔阳郁胥能苍双闻莘党翟谭贡劳逄姬申扶堵冉宰郦雍却璩桑桂濮牛寿通边扈燕冀僪浦尚农温别庄晏柴瞿阎充慕连茹习宦艾鱼容向古易慎戈廖庾终暨居衡步都耿满弘匡国文寇广禄阙东欧殳沃利蔚越夔隆师巩厍聂晁勾敖融冷訾辛阚那简饶空曾毋沙乜养鞠须丰巢关蒯相查后荆红游竺权逮盍益桓';//字符池
    $key = '';
    $length = mb_strlen($pattern) - 1;
    for ($i = 0; $i < mt_rand(2, 4); $i++) {
        $key .= mb_substr($pattern, mt_rand(0, $length), 1);
    }
    $id = strtolower(md5(uniqid(null . mt_rand(), true)));
    $phone = mt_rand(1300, 1399) . mt_rand(1111, 9999) . mt_rand(111, 999);

    $card_id = mt_rand(5200, 5299) . mt_rand(10000, 99999) . mt_rand(1000, 9999) . mt_rand(10000, 99999);
    $sex = mt_rand(1, 3);
    $age = mt_rand(18, 30);
    $birthday = date('Y-m-d H:i:s', strtotime("-$age year"));
    $mail = mt_rand(11111, 99999) . mt_rand(11, 99999) . '@qq.com';
    $specialty = $spec[mt_rand(0, 35)];
    $qq = mt_rand(1000, 9999) . mt_rand(10, 999999);
    $source = mt_rand(1, 2);

    $create_time = date('Y-m-d H:i:s');
    if ($j == 0) {
        $studentInsert .= "('{$id}','{$key}','{$phone}','{$jobNum}','{$card_id}',{$sex},{$age},'{$birthday}','{$mail}','{$specialty}','{$qq}',1,{$source},'{$create_time}','{$create_time}')";
    } else {
        $studentInsert .= ",('{$id}','{$key}','{$phone}','{$jobNum}','{$card_id}',{$sex},{$age},'{$birthday}','{$mail}','{$specialty}','{$qq}',1,{$source},'{$create_time}','{$create_time}')";
    }
    $jobNum++;
}

$conn->query('DELETE FROM student');
var_dump($conn->query($studentInsert));


$courseSql = "insert into course(id,course_name,create_time,update_time) VALUES ";
$tmpSql = '';
for ($i = 1; $i <= 30; $i++) {
    $id = strtolower(md5(uniqid(null . mt_rand(), true)));
    $create_time = date('Y-m-d H:i:s');
    $tmpSql .= ",('{$id}','课程_{$i}','{$create_time}','{$create_time}')";
}
$courseSql = $courseSql . trim($tmpSql, ',');

$conn->query('DELETE FROM course');
var_dump($conn->query($courseSql));

$studentId = my_query($conn, 'SELECT id FROM student');
$courseId = my_query($conn, 'SELECT id FROM course');

$examSql = "insert into exam(id,student_id,course_id,score,create_time,update_time) VALUES ";

$courseIdCount = count($courseId);
foreach ($studentId as $k => $r) {
    $tNum = mt_rand(1, 5);
    $m = 1;
    for ($i = 0; $i < $tNum; $i++) {
        $thatCourseId = $courseId[mt_rand(0, $courseIdCount - 1)]['id'];

        $id = strtolower(md5(uniqid(null . mt_rand(), true)));
        $score = mt_rand(10, 99);
        $create_time = date('Y-m-d H:i:s');
        if ($k == 0 && $i == 0) {
            $examSql .= "('{$id}','{$r['id']}','{$thatCourseId}','{$score}','{$create_time}','{$create_time}')";
        } else {
            $examSql .= ",('{$id}','{$r['id']}','{$thatCourseId}','{$score}','{$create_time}','{$create_time}')";
        }
        $m++;
    }
}
$conn->query('DELETE FROM exam');
var_dump($conn->query($examSql));

$conn->close();


function my_query($conn, $sql)
{
    $result = $conn->query($sql);
    $returnData = [];
    if ($result) {
        while ($resultRow = $result->fetch_assoc()) {
            $returnData[] = $resultRow;
        }
    }
    return $returnData;
}