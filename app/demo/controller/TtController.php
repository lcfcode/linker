<?php
/**
 * @link https://gitee.com/lcfcode/linker
 * @link https://github.com/lcfcode/linker
 */
    
namespace app\demo\controller;

use swap\core\Controller;
use swap\utils\MongoClass;
use swap\utils\MssqlClass;

class TtController extends Controller
{
    public function indexAction()
    {
        $mss = new MssqlClass($this->getConfigValue('db_mssql'));
        $data = [
            'config_1' => '配置1',
            'config_2' => '配置1',
            'status' => '1',
        ];
        p($mss->serverInfo());
        /*
         * sql
         *
         IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[test1]') AND type IN ('U'))
        DROP TABLE [dbo].[test1]
        GO

        CREATE TABLE [dbo].[test1] (
        [id] char(32) COLLATE SQL_Latin1_General_CP1_CI_AS  NOT NULL,
        [name] nvarchar(max) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL
        )
        GO

        ALTER TABLE [dbo].[test1] SET (LOCK_ESCALATION = TABLE)
        GO


        -- ----------------------------
        -- Table structure for test2
        -- ----------------------------
        IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[test2]') AND type IN ('U'))
        DROP TABLE [dbo].[test2]
        GO

        CREATE TABLE [dbo].[test2] (
        [id] int  IDENTITY(1,1) NOT NULL,
        [config_1] nvarchar(max) COLLATE Chinese_PRC_CI_AS  NULL,
        [config_2] nvarchar(max) COLLATE Chinese_PRC_CI_AS  NULL,
        [status] smallint  NULL
        )
        GO

        ALTER TABLE [dbo].[test2] SET (LOCK_ESCALATION = TABLE)
        GO
         */
//        p($mss->insert('test2',$data));die;
//        p($mss->server_info());
        $data = [
            'id' => strtolower(md5(uniqid(mt_rand() . microtime(), true))),
            'name' => '哈哈哈_' . date('Y-m-d H:i:s'),
        ];
//        p($mss->insert('test1', $data));
//        p($mss->getLastSql());
        $data = [
            'name' => '更新的_' . time() . mt_rand(),
        ];
//        p($mss->updateId('test1','id','f95c22d216de7b69711ebeedc6650459', $data));
//        p($mss->deleteId('test1','id','33e30539ef6e4c4bb32078c07f71278e'));
        $where = [
            'name' => '哈哈哈_2019-09-13 21:33:20',
        ];
//        p($mss->update('test1',$data,$where));

        $where = [
            'name' => '更新的_15684419201247699230',
        ];
//        p($mss->delete('test1',$where));
//        p($mss->selectId('test1','id','69fda42c0cf1113bb8b2eab96ef34f4e'));
//        p($mss->select('test1', ['name' => '哈哈哈_2019-09-21 20:20:11']));
//        p($mss->select('test1', ['name' => '哈哈哈_2019-09-21 20:20:11'], ['id' => 'asc'],0,2));
//        p($mss->selectOne('test1', ['name' => '哈哈哈_2019-09-21 20:20:11'], ['id' => 'asc']));
//        p($mss->getLastSql());
//        p($mss->selectAll('test1', ['id' => 'asc']));
//        p($mss->selectAll('test1', ['id' => 'asc'],0,2));
//        p($mss->selects('test1',[], ['id' => 'desc'],0,2));
//        p($mss->selects('test1',[], ['id' => 'desc'],0,2));

        $parameter = ['name' => '哈哈哈_2019-09-21 20:20:11'];
        $sql = "select * from( select *, row_number() over(order by id asc ) as row_num from test1 where  name=?  ) as linker ";

        $parameter = ['id' => '67c86291956dc2059ade3c25d7ad', 'name' => '哈哈哈_2019-09-21 21:22:21'];
        $sql = "insert into test1 (id,name) values (?,?) ";

//        p($mss->query($sql, $parameter));
//        p($mss->getLastSql());
        p($mss->like('test2', 'config_1', '配置'));
//        p($mss->like('test2', 'config_1', '配置',[],true));
        die;
//        p($mss->rlike('test1', 'name', '哈哈哈', ['id' => '67c86291956dc2059ade3c25d7ad']));
//        p($mss->llike('test1', 'name', '哈哈哈', ['id' => '67c86291956dc2059ade3c25d7ad'],['name'=>'desc'],0,1));
//        p($mss->llike('test1', 'name', '哈哈哈', ['id' => '67c86291956dc2059ade3c25d7ad'],['name'=>'desc'],0,1));
        $muData = [];
        for ($i = 0; $i < 8; $i++) {
            $muData[] = [
                'id' => strtolower(md5(uniqid(mt_rand() . microtime(), true))),
                'name' => '哈哈哈_' . date('Y-m-d H:i:s'),
            ];
        }
//        p($muData);die;
//        p($mss->insertMultiple('test1', $muData));
        p($mss->count('test1',['id'=>'81a23124e8cb37c2a043276d5cab02c7']));
        p($mss->getLastSql());
        return 'test';
    }

    public function testAction(){
        $mss = new MongoClass($this->getConfigValue('db'));
        $data = [
            'config_1' => '配置1',
            'config_2' => '配置1',
            'status' => '1',
        ];
    }
}