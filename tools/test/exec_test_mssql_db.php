<?php
/**
 * Created by LCF.
 * User: AF
 * Date: 2016/11/19
 * Time: 22:01
 */
$config = include "../../config/dev.php";

$config = $config['db_mssql'];

$connectionOptions = array(/*"Database" => $config['database'],*/
    "Uid" => $config['user'], "PWD" => $config['password'], 'CharacterSet' => $config['charset']);
$conn = sqlsrv_connect($config['host'] . ',' . $config['port'], $connectionOptions);

$dbname = $config['database'];
$dbname = 'linker';

$sql = <<<EOF
if not exists (select * from sys.databases where name = '{$dbname}')
	create database [{$dbname}]
EOF;

$result = my_query($conn, $sql);


echo "create database success [linker] ..." . PHP_EOL;

var_dump(my_query($conn, "use {$dbname}"));

$sql = <<<SQL
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[course]') AND type IN ('U'))
	DROP TABLE [dbo].[course]
SQL;
var_dump(my_query($conn, $sql));

$sql = <<<EOF
CREATE TABLE [dbo].[course] (
  [id] nchar(32) COLLATE SQL_Latin1_General_CP1_CI_AS  NOT NULL,
  [course_name] nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [create_time] char(20)  NULL,
  [update_time] char(20)  NULL
)
EOF;

var_dump(my_query($conn, $sql));
echo "create table success [linker][course] ..." . PHP_EOL;

$sql = <<<SQL
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[student]') AND type IN ('U'))
	DROP TABLE [dbo].[student]
SQL;
var_dump(my_query($conn, $sql));

$sql = <<<EOF
CREATE TABLE [dbo].[student] (
  [id] nchar(32) COLLATE SQL_Latin1_General_CP1_CI_AS  NOT NULL,
  [name] nvarchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [phone] nchar(11) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [job_number] nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [card_id] nchar(18) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [sex] int  NULL,
  [age] int  NULL,
  [birthday] nvarchar(20) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [head_img] nvarchar(1024) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [mail] nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [specialty] nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [area] nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [qq] nvarchar(11) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [is_enable] int  NULL,
  [source] int  NULL,
  [create_time] char(20)  NULL,
  [update_time] char(20)  NULL
)
EOF;
var_dump(my_query($conn, $sql));

echo "create table success [linker][student] ..." . PHP_EOL;

$sql = <<<SQL
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[exam]') AND type IN ('U'))
	DROP TABLE [dbo].[exam]
SQL;
var_dump(my_query($conn, $sql));
$sql = <<<EOF
CREATE TABLE [dbo].[exam] (
  [id] nchar(32) COLLATE SQL_Latin1_General_CP1_CI_AS  NOT NULL,
  [student_id] nchar(32) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [course_id] nchar(32) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [score] int  NULL,
  [create_time] char(20)  NULL,
  [update_time] char(20)  NULL
)
EOF;
var_dump(my_query($conn, $sql));
echo "create table success [linker][exam] ..." . PHP_EOL;

$sql = "INSERT INTO [course] VALUES (N'10f96d28ae069e167965dfc07f3ddd63', N'课程_25', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'11b03859f17d9897c60b4f0caedcecfd', N'课程_14', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'14a2045d6c704d5064679303f5edf7da', N'课程_19', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'14b21c90a608dfc5e2f8b3e6735c367b', N'课程_17', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'15729afc1254fa3ab1b53532b741b8f6', N'课程_27', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'213651b77df828ae8bad422f6085ea4f', N'课程_12', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'2255fc25e0b70556631f0d737d7f92fb', N'课程_11', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'23faf48b10d58a0bc9418e149731588a', N'课程_3', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'308b38049a5a5eee5b83066939d446cd', N'课程_9', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'3178fc40fd9d5b0d539dddee288ed0a3', N'课程_6', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'3db3622814ab1e880f4fdeade51db0f3', N'课程_13', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'40ccf10a996b82ab58d01e7cfc34d96a', N'课程_30', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'5093870ccabc8ad410378ebb006c3ad2', N'课程_10', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'53051079373757f4661b8f7069ae7c7a', N'课程_23', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'56bd15dac13e64aa477ad7eb1d2b3062', N'课程_1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'6a875bfd8b769ade02be67abe98de261', N'课程_15', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'73ea3d34802f4192fcdd760054714df5', N'课程_21', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'7dee5547ae0b08e4b8f006a8fbc7d4c9', N'课程_20', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'8394a622db0a517fe902fcccbaf5896d', N'课程_2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'898b0a531ace92712c60c7a0487a7abd', N'课程_5', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'9b1f5416ddcb2e706d10e07e635177c8', N'课程_26', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'a2e222bc33f97fe34de8195c3267bc1a', N'课程_16', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'a6c3f13892fa3b48083ed6efc8c1f6bb', N'课程_8', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'c0f3b05271bd4156abd361621b8f3130', N'课程_22', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'c3088661e452cbe6835122c41f1e03bc', N'课程_24', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'c355f516bfb2bb2010c2586bfd313044', N'课程_18', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'c40920baa879846afef8400c5eeb943f', N'课程_28', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'cc3d4ce455285428924f33f36922aee7', N'课程_7', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'd0ef95c27823ff64d5cfd19602e46361', N'课程_29', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'e25f2b4e2f2e54a99a0cc29363c486dc', N'课程_4', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32')";
var_dump(my_query($conn, $sql));
echo "insert data success [linker][course] ..." . PHP_EOL;

$sql = "INSERT INTO [exam] VALUES (N'04887d84a26eab93ec9f8316a7b4d573', N'612ea4708f8e8bb4f867c1875864d6ab', N'c40920baa879846afef8400c5eeb943f', N'25', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'0539173682fc483bca0fa30703b174d0', N'705cad0105982e85cb2679ab8aa2e946', N'3178fc40fd9d5b0d539dddee288ed0a3', N'17', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'0541ffe8cac439313bfb6a9ea9ddca05', N'87fd2b7df3612e10a8e96021f32fc43f', N'c0f3b05271bd4156abd361621b8f3130', N'84', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'0618bcf8e54b24872ece3000584b5703', N'29111520be609063bd1ddf1786720185', N'308b38049a5a5eee5b83066939d446cd', N'90', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'0a4d579c53cffa4f0ed0876b98cd677d', N'3dee6458f90e9e0c89bee8c5e13db1c3', N'23faf48b10d58a0bc9418e149731588a', N'86', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'0ba476be986ed2114050c7e16625bbe6', N'e6b9a5d5609446d1bacbc6f6d3464530', N'40ccf10a996b82ab58d01e7cfc34d96a', N'78', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'12d37cb7e5aa0f123d3c6ee92c1273cf', N'ec1ec049710f81d0916098ab915bed34', N'a2e222bc33f97fe34de8195c3267bc1a', N'70', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'134fa8d416b3f2b149250622c76ff568', N'dc0ab2923292a0b56f520d218d65aebb', N'c40920baa879846afef8400c5eeb943f', N'16', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'18a77f6ac40393cd6871d87fa3266073', N'3569a83b48ec387e6a8cfcd52f7945fa', N'8394a622db0a517fe902fcccbaf5896d', N'84', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'1932a85b8123110a5fc22de1f3a8d779', N'5de0678d2f88545c55834ae8b582081f', N'14b21c90a608dfc5e2f8b3e6735c367b', N'23', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'19a780b85dd616560aef45ccffbdb7ba', N'b70b4da7a96466c337b73e15cee36bb9', N'40ccf10a996b82ab58d01e7cfc34d96a', N'83', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'1c04bbaf57e1bee3dd9c89ac1914f4e5', N'4c9981a25eb84ac3ddbc2861290e02bb', N'2255fc25e0b70556631f0d737d7f92fb', N'24', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'2237e6e051e6847780209a775b33ca0d', N'29111520be609063bd1ddf1786720185', N'11b03859f17d9897c60b4f0caedcecfd', N'40', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'235647e92261714b1fc070133686f37f', N'ec1ec049710f81d0916098ab915bed34', N'53051079373757f4661b8f7069ae7c7a', N'73', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'239672b91baca7d4688c82b58eb781dd', N'0185593cbfcf2955bf80c95ac7032097', N'7dee5547ae0b08e4b8f006a8fbc7d4c9', N'26', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'27232aa54a0fea26ae541ab35712a63e', N'bec647ab6e35d42d6f04cf0268ef6b62', N'308b38049a5a5eee5b83066939d446cd', N'59', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'286a34e61fe3bbf1cf422dac20a50059', N'29111520be609063bd1ddf1786720185', N'213651b77df828ae8bad422f6085ea4f', N'83', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'2b5c903439d68fac6e77fa479d8f70c7', N'53cb4b65d9852491d1e03716a5b569f9', N'8394a622db0a517fe902fcccbaf5896d', N'55', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'2f7d78b12671e1787e18500709b5af95', N'b70b4da7a96466c337b73e15cee36bb9', N'c3088661e452cbe6835122c41f1e03bc', N'82', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'317f3e6b775cb3c22ca6641e94873004', N'dc0ab2923292a0b56f520d218d65aebb', N'3178fc40fd9d5b0d539dddee288ed0a3', N'59', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'3600e29c0090257d845bd4b828d34bc3', N'87fd2b7df3612e10a8e96021f32fc43f', N'2255fc25e0b70556631f0d737d7f92fb', N'48', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'365a63e7feac5b12e4c8dd6e8e1e44ed', N'00df0dc381fc9ca062948808f1dfc3b7', N'53051079373757f4661b8f7069ae7c7a', N'63', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'36bc78ac67d1687d89d237c5e623c08a', N'e6b9a5d5609446d1bacbc6f6d3464530', N'213651b77df828ae8bad422f6085ea4f', N'70', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'3d881b123acbf6963cc6667150bb2da2', N'677bb36aa219bcdd4e3095c79e502cea', N'53051079373757f4661b8f7069ae7c7a', N'73', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'3e5b61d221d54d4227ceb41bc76b9eea', N'677bb36aa219bcdd4e3095c79e502cea', N'14a2045d6c704d5064679303f5edf7da', N'11', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'425b2b54431679d817ab0cb0c7a8db43', N'4075298ea8513a66d3ae17cb57ff011b', N'10f96d28ae069e167965dfc07f3ddd63', N'70', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'433a6ed3d0a872bc98460fb2c32e620d', N'f663c0120929fac51fbb291d866ed1f5', N'308b38049a5a5eee5b83066939d446cd', N'28', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'46fd62e15e67457c5e40458c23f0b982', N'dc0ab2923292a0b56f520d218d65aebb', N'14b21c90a608dfc5e2f8b3e6735c367b', N'80', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'5049910363eeecb0d167b437e1017955', N'b70b4da7a96466c337b73e15cee36bb9', N'e25f2b4e2f2e54a99a0cc29363c486dc', N'97', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'526e4f070b67d6531e4cf540163e7702', N'4c827a37dbed7641059645efe41cc2a9', N'308b38049a5a5eee5b83066939d446cd', N'10', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'5ac49d24d37d33e30bdda26506f98900', N'e6b9a5d5609446d1bacbc6f6d3464530', N'c3088661e452cbe6835122c41f1e03bc', N'79', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'5c3eb6c8cce00abe90ebf7f605c3bf87', N'705cad0105982e85cb2679ab8aa2e946', N'3178fc40fd9d5b0d539dddee288ed0a3', N'70', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'5eaf79a80559047c2e954badee38de16', N'8b5efde0b828a256105a5e5b1cb1f365', N'3178fc40fd9d5b0d539dddee288ed0a3', N'76', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'5f1ed3844f7e22f7a31c69294ba7fdff', N'ec1ec049710f81d0916098ab915bed34', N'8394a622db0a517fe902fcccbaf5896d', N'43', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'649d8e9c95b6c8dd80fbd4da57622c57', N'b70b4da7a96466c337b73e15cee36bb9', N'7dee5547ae0b08e4b8f006a8fbc7d4c9', N'99', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'64b10fe31a3c1e63e74bc06872f0d667', N'63d720c8057349159adc05510de3b12d', N'e25f2b4e2f2e54a99a0cc29363c486dc', N'20', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'67aee1c2c24b52418da5cd7ddaa1da20', N'4c9981a25eb84ac3ddbc2861290e02bb', N'6a875bfd8b769ade02be67abe98de261', N'44', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'68cc0263c522069864e8292d5c401813', N'705cad0105982e85cb2679ab8aa2e946', N'14a2045d6c704d5064679303f5edf7da', N'41', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'7506416dcacf84d55c4f5dc58f552451', N'ed5733c7f4f232a9e633642b57038880', N'15729afc1254fa3ab1b53532b741b8f6', N'83', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'750f1f13bfd23c1cc591ff93a31edc13', N'e6b9a5d5609446d1bacbc6f6d3464530', N'308b38049a5a5eee5b83066939d446cd', N'35', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'75400e32d373f484d9cb37067cfb071f', N'612ea4708f8e8bb4f867c1875864d6ab', N'3db3622814ab1e880f4fdeade51db0f3', N'18', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'7876ef8304f2b55fea6dd22bd4a62c22', N'38765aea1f9e6b3f7d45e836b0497da1', N'308b38049a5a5eee5b83066939d446cd', N'33', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'79ad128acf8d6e6dd6d6be05c96a3e91', N'87fd2b7df3612e10a8e96021f32fc43f', N'8394a622db0a517fe902fcccbaf5896d', N'72', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'7d11c4f3eda54b78ea8e0532d08b4326', N'4075298ea8513a66d3ae17cb57ff011b', N'23faf48b10d58a0bc9418e149731588a', N'21', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'7ffe5b7e9d78437fa55297e8af6b93f2', N'4c9981a25eb84ac3ddbc2861290e02bb', N'14a2045d6c704d5064679303f5edf7da', N'64', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'84a4c1743922d26f542d63d5d6f0b372', N'f663c0120929fac51fbb291d866ed1f5', N'5093870ccabc8ad410378ebb006c3ad2', N'69', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'86ab872e3bf1bacb4dffe91f40248be6', N'd0293c6e5073c4e30773b48c5798fda1', N'7dee5547ae0b08e4b8f006a8fbc7d4c9', N'59', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'877e8ebcc93ef81d6202573bcc39f50e', N'29111520be609063bd1ddf1786720185', N'308b38049a5a5eee5b83066939d446cd', N'31', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'87ccc193133d21d200a3207b23f760eb', N'd0293c6e5073c4e30773b48c5798fda1', N'14a2045d6c704d5064679303f5edf7da', N'86', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'8b838e1f8a6a5bd625f68eb8da59d993', N'63d720c8057349159adc05510de3b12d', N'40ccf10a996b82ab58d01e7cfc34d96a', N'77', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'9647dc6c2fc5d0e97919ad1236e78ccd', N'4c827a37dbed7641059645efe41cc2a9', N'308b38049a5a5eee5b83066939d446cd', N'78', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'9762a3cc413040e6cd50ee89b8e55634', N'38765aea1f9e6b3f7d45e836b0497da1', N'3178fc40fd9d5b0d539dddee288ed0a3', N'92', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'9aa3b0dcf87e6d8c18522dd66722aa6e', N'b7ac494acd63c1766ab7bb2012fd7e60', N'898b0a531ace92712c60c7a0487a7abd', N'48', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'9bb3cee5678d49352ddfb80f8505d038', N'705cad0105982e85cb2679ab8aa2e946', N'a6c3f13892fa3b48083ed6efc8c1f6bb', N'64', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'9dd445383713b813f4b4c1f1e982fa59', N'612ea4708f8e8bb4f867c1875864d6ab', N'a2e222bc33f97fe34de8195c3267bc1a', N'40', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'a0b8ea5306ad1f9c3d8c03da3b5196a9', N'ed5733c7f4f232a9e633642b57038880', N'10f96d28ae069e167965dfc07f3ddd63', N'80', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'a35cad0bf0410809fee39e7f2097a9ee', N'4c9981a25eb84ac3ddbc2861290e02bb', N'a6c3f13892fa3b48083ed6efc8c1f6bb', N'54', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'a78a84c8318cd6bbee9985e459164edf', N'3569a83b48ec387e6a8cfcd52f7945fa', N'9b1f5416ddcb2e706d10e07e635177c8', N'29', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'aac5eee823fd24b87b46f25dd580273c', N'38765aea1f9e6b3f7d45e836b0497da1', N'15729afc1254fa3ab1b53532b741b8f6', N'47', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'b14eeedacc6160a8966e5e567c6043d8', N'076758e37ff88ccb56cfe0ae5fb9ca2a', N'c355f516bfb2bb2010c2586bfd313044', N'67', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'b3aa898b7e61b29aee6a3d326623e4fb', N'5beb72924df0c7f6128cfe9b9f63e3a2', N'd0ef95c27823ff64d5cfd19602e46361', N'98', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'b62f36990fe5f50896121eb82165825e', N'3569a83b48ec387e6a8cfcd52f7945fa', N'c3088661e452cbe6835122c41f1e03bc', N'21', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'ba0a36e6c0a49e7aa5cc589763ca83a3', N'e6b9a5d5609446d1bacbc6f6d3464530', N'c40920baa879846afef8400c5eeb943f', N'91', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'bd6920a569a972b1b244160c2ce223f5', N'ec1ec049710f81d0916098ab915bed34', N'd0ef95c27823ff64d5cfd19602e46361', N'96', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'c076f3c2f0e71f3ed89a0bf68abe47fb', N'907bc730dd04b0ab14ea8aa67a71ae18', N'73ea3d34802f4192fcdd760054714df5', N'86', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'c25a630125bf9c30429dd67f9123898e', N'0185593cbfcf2955bf80c95ac7032097', N'40ccf10a996b82ab58d01e7cfc34d96a', N'71', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'cbb582582f1a48542d3a368a6406fdc0', N'076758e37ff88ccb56cfe0ae5fb9ca2a', N'40ccf10a996b82ab58d01e7cfc34d96a', N'75', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'ce1bede9a59e1c2913a73e320b33d8a1', N'b7ac494acd63c1766ab7bb2012fd7e60', N'e25f2b4e2f2e54a99a0cc29363c486dc', N'36', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'd1da67571fab13834fd368c20c017a5b', N'b7ac494acd63c1766ab7bb2012fd7e60', N'10f96d28ae069e167965dfc07f3ddd63', N'71', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'd4624fed917b8629db33023632ee16af', N'5de0678d2f88545c55834ae8b582081f', N'11b03859f17d9897c60b4f0caedcecfd', N'35', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'dd01702758ae5f42081a013ab200e083', N'4075298ea8513a66d3ae17cb57ff011b', N'6a875bfd8b769ade02be67abe98de261', N'20', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'e2d716035e3e53d7fdd19d2d386bf7b3', N'00df0dc381fc9ca062948808f1dfc3b7', N'3178fc40fd9d5b0d539dddee288ed0a3', N'85', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'eab9fb645fd56fb21d32d9f3c253ea32', N'4075298ea8513a66d3ae17cb57ff011b', N'11b03859f17d9897c60b4f0caedcecfd', N'49', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'ee70ceebfdb29604eb35f51e68f9bc6b', N'53cb4b65d9852491d1e03716a5b569f9', N'5093870ccabc8ad410378ebb006c3ad2', N'69', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'ef5ac3ab02ac4d315a2dcb6f4c9599ed', N'bec647ab6e35d42d6f04cf0268ef6b62', N'56bd15dac13e64aa477ad7eb1d2b3062', N'49', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'f0bee61878c68442ab786c9255b49399', N'3dee6458f90e9e0c89bee8c5e13db1c3', N'40ccf10a996b82ab58d01e7cfc34d96a', N'45', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'f37ab8325503fc1661ca68a36a0ebc8e', N'bd6b0626167eda1ed9f1071402619733', N'14b21c90a608dfc5e2f8b3e6735c367b', N'51', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'f76dce7415b9635eb1f31b377d183495', N'907bc730dd04b0ab14ea8aa67a71ae18', N'3178fc40fd9d5b0d539dddee288ed0a3', N'52', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32')";
var_dump(my_query($conn, $sql));
echo "insert data success [linker][exam] ..." . PHP_EOL;

$sql = "INSERT INTO [student] VALUES (N'00df0dc381fc9ca062948808f1dfc3b7', N'简芮丁', N'13722634937', N'201809020010', N'526115742719937880', N'1', N'23', N'1996-09-24 11:43:32', NULL, N'618939467@qq.com', N'智能电网信息工程', NULL, N'8798802377', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'0185593cbfcf2955bf80c95ac7032097', N'汪祝饶', N'13497180513', N'201809020012', N'521919009524524964', N'1', N'18', N'2001-09-24 11:43:32', NULL, N'9241854390@qq.com', N'网络空间安全', NULL, N'5839693816', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'076758e37ff88ccb56cfe0ae5fb9ca2a', N'戈祝', N'13784493520', N'201809020008', N'529052697927030166', N'2', N'22', N'1997-09-24 11:43:32', NULL, N'1383630722@qq.com', N'数理基础科学', NULL, N'3747163357', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'29111520be609063bd1ddf1786720185', N'岑危别全', N'13006258998', N'201809020004', N'525998403993622984', N'2', N'21', N'1998-09-24 11:43:32', NULL, N'2897558596@qq.com', N'计算机科学与技术', NULL, N'8989158173', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'3569a83b48ec387e6a8cfcd52f7945fa', N'郑邬谭', N'13354663718', N'201809020024', N'526351964987821924', N'2', N'18', N'2001-09-24 11:43:32', NULL, N'9711785207@qq.com', N'网络与新媒体', NULL, N'691556521', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'38765aea1f9e6b3f7d45e836b0497da1', N'计牧叶姜', N'13619277872', N'201809020023', N'525933613310266372', N'1', N'26', N'1993-09-24 11:43:32', NULL, N'7517878312@qq.com', N'电影学', NULL, N'4614715268', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'3dee6458f90e9e0c89bee8c5e13db1c3', N'酆曾包禹', N'13139742885', N'201809020006', N'525523693674383967', N'3', N'23', N'1996-09-24 11:43:32', NULL, N'7903751207@qq.com', N'信息管理与信息系统', NULL, N'4008298179', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'4075298ea8513a66d3ae17cb57ff011b', N'巴仇郁', N'13295661494', N'201809020007', N'525550592283388573', N'3', N'20', N'1999-09-24 11:43:32', NULL, N'3227330298@qq.com', N'网络安全与执法', NULL, N'3032725635', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'4c827a37dbed7641059645efe41cc2a9', N'堵苗谢', N'13021946425', N'201809020003', N'522271900827251377', N'3', N'28', N'1991-09-24 11:43:32', NULL, N'770116587@qq.com', N'电磁场与无线技术', NULL, N'8828642719', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'4c9981a25eb84ac3ddbc2861290e02bb', N'桑邢宣', N'13129800345', N'201809020022', N'529933244614719381', N'3', N'24', N'1995-09-24 11:43:32', NULL, N'3908581046@qq.com', N'信息与计算科学', NULL, N'9869895801', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'53cb4b65d9852491d1e03716a5b569f9', N'甄屈胡侯', N'13953635647', N'201809020018', N'528662606158491198', N'3', N'26', N'1993-09-24 11:43:32', NULL, N'7287836998@qq.com', N'网络与新媒体', NULL, N'6606933865', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'5beb72924df0c7f6128cfe9b9f63e3a2', N'卓范后', N'13024181683', N'201809020016', N'521457119792278406', N'3', N'19', N'2000-09-24 11:43:32', NULL, N'480445306@qq.com', N'信息管理与信息系统', NULL, N'9610593287', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'5de0678d2f88545c55834ae8b582081f', N'扶相常卓', N'13178068801', N'201809020030', N'523131317205072657', N'3', N'19', N'2000-09-24 11:43:32', NULL, N'5782486673@qq.com', N'数字媒体技术', NULL, N'914213420', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'612ea4708f8e8bb4f867c1875864d6ab', N'巩曹禹', N'13221408548', N'201809020011', N'524433128441619480', N'1', N'29', N'1990-09-24 11:43:32', NULL, N'2674293141@qq.com', N'数字媒体技术', NULL, N'3980646677', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'63d720c8057349159adc05510de3b12d', N'敖莘叶', N'13716904393', N'201809020002', N'529123907605051581', N'1', N'20', N'1999-09-24 11:43:32', NULL, N'1904266468@qq.com', N'计算机科学与技术', NULL, N'9581658280', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'677bb36aa219bcdd4e3095c79e502cea', N'暴夔郗闵', N'13465887692', N'201809020001', N'520741128714142422', N'1', N'18', N'2001-09-24 11:43:32', NULL, N'976172844@qq.com', N'数字媒体技术', NULL, N'5796533248', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'705cad0105982e85cb2679ab8aa2e946', N'晁燕', N'13108147761', N'201809020019', N'521815858111612648', N'2', N'24', N'1995-09-24 11:43:32', NULL, N'8905634908@qq.com', N'电影学', NULL, N'1072974046', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'87fd2b7df3612e10a8e96021f32fc43f', N'杨云鲍', N'13969645371', N'201809020020', N'525364108395069496', N'2', N'22', N'1997-09-24 11:43:32', NULL, N'1581332675@qq.com', N'电子与计算机工程', NULL, N'6039366052', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'8b5efde0b828a256105a5e5b1cb1f365', N'鲁童', N'13624232560', N'201809020014', N'520870387890719915', N'3', N'29', N'1990-09-24 11:43:32', NULL, N'5592111000@qq.com', N'信息对抗技术', NULL, N'1557847782', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'907bc730dd04b0ab14ea8aa67a71ae18', N'祁沈邱', N'13294933173', N'201809020026', N'528576528524178625', N'3', N'26', N'1993-09-24 11:43:32', NULL, N'3644294787@qq.com', N'数理基础科学', NULL, N'5402308989', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'b70b4da7a96466c337b73e15cee36bb9', N'晁富', N'13974009647', N'201809020005', N'528315393889518345', N'2', N'22', N'1997-09-24 11:43:32', NULL, N'6488921336@qq.com', N'电波传播与天线', NULL, N'4548954230', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'b7ac494acd63c1766ab7bb2012fd7e60', N'班伏居柴', N'13183215164', N'201809020013', N'522071129451893528', N'2', N'27', N'1992-09-24 11:43:32', NULL, N'7858060164@qq.com', N'数学与应用数学', NULL, N'4226584478', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'bd6b0626167eda1ed9f1071402619733', N'祝韩刘', N'13416108253', N'201809020015', N'526727457110861359', N'1', N'28', N'1991-09-24 11:43:32', NULL, N'5969163617@qq.com', N'电子与计算机工程', NULL, N'3032342243', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'bec647ab6e35d42d6f04cf0268ef6b62', N'堵尤', N'13617135860', N'201809020009', N'528512220426212214', N'1', N'24', N'1995-09-24 11:43:32', NULL, N'7400035705@qq.com', N'信息与计算科学', NULL, N'3367725025', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'd0293c6e5073c4e30773b48c5798fda1', N'许利', N'13191892144', N'201809020021', N'526291752197425268', N'1', N'19', N'2000-09-24 11:43:32', NULL, N'7491260869@qq.com', N'信用管理', NULL, N'6892634129', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'dc0ab2923292a0b56f520d218d65aebb', N'傅邵', N'13269067244', N'201809020027', N'520555567154374345', N'3', N'22', N'1997-09-24 11:43:32', NULL, N'2271186294@qq.com', N'信息与计算科学', NULL, N'23608938', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'e6b9a5d5609446d1bacbc6f6d3464530', N'庞余乌', N'13659897389', N'201809020029', N'523326832873071793', N'3', N'22', N'1997-09-24 11:43:32', NULL, N'851751217@qq.com', N'网络工程', NULL, N'6101970489', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'ec1ec049710f81d0916098ab915bed34', N'古满游范', N'13721843811', N'201809020017', N'520130614105022028', N'2', N'20', N'1999-09-24 11:43:32', NULL, N'9631864713@qq.com', N'电子商务及法律', NULL, N'7195106645', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'ed5733c7f4f232a9e633642b57038880', N'池易后', N'13762623694', N'201809020025', N'523546446151842613', N'3', N'18', N'2001-09-24 11:43:32', NULL, N'9022921299@qq.com', N'信息对抗技术', NULL, N'9775649211', N'1', N'2', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32'),(N'f663c0120929fac51fbb291d866ed1f5', N'堵胥聂', N'13163560411', N'201809020028', N'524642407418598185', N'3', N'23', N'1996-09-24 11:43:32', NULL, N'7033278946@qq.com', N'空间信息与数字技术', NULL, N'4447633853', N'1', N'1', N'2019-09-24 11:43:32', N'2019-09-24 11:43:32')";
var_dump(my_query($conn, $sql));
echo "insert data success [linker][student] ..." . PHP_EOL;
//die;
//////////////////////////////////////////////////////////////////////////////////////////////////////////

$sql = <<<EOF
if not exists (select * from sys.databases where name = 'linker_two')
	create database [linker_two]
EOF;

$result = my_query($conn, $sql);


echo "create database success [linker] ..." . PHP_EOL;

var_dump(my_query($conn, "use linker_two"));

echo "create [linker_two] database success ..." . PHP_EOL;

$sql = <<<SQL
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[employee]') AND type IN ('U'))
	DROP TABLE [dbo].[employee]
SQL;
var_dump(my_query($conn, $sql));

$sql = <<<LCF
CREATE TABLE [dbo].[employee] (
  [num] int  NULL,
  [id] nchar(32) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [name] nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [age] int  NULL,
  [sex] nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL,
  [homeaddr] nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL
)
LCF;
var_dump(my_query($conn, $sql));
echo "create table success [linker_two][employee] ..." . PHP_EOL;
$sql = "INSERT INTO [employee] VALUES (N'1', N'1001', N'张三', N'26', N'男', N'beijinghdq'),(N'2', N'1002', N'李四', N'24', N'女', N'basle'),(N'3', N'1003', N'王五', N'25', N'男', N'khmnm'),(N'4', N'1004', N'赵六', N'15', N'男', N'en')";
var_dump(my_query($conn, $sql));

echo "insert data success [linker_two][employee] ..." . PHP_EOL;

$sql = <<<SQL
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[json_tab]') AND type IN ('U'))
	DROP TABLE [dbo].[json_tab]
SQL;
var_dump(my_query($conn, $sql));
$sql = <<<LCF
CREATE TABLE [dbo].[json_tab] (
  [id] nchar(32) COLLATE SQL_Latin1_General_CP1_CI_AS  NOT NULL,
  [data] nvarchar(max) COLLATE SQL_Latin1_General_CP1_CI_AS  NULL
)
LCF;
var_dump(my_query($conn, $sql));
echo "create table success [linker_two][json_tab] ..." . PHP_EOL;
$sql = "INSERT INTO [json_tab] VALUES (N'1', N'{\"mail\": \"jiangchengyao@gmail.com\", \"name\": \"David\", \"address\": \"Shangahai\"}'),(N'2', N'{\"mail\": \"amy@gmail.com\", \"name\": \"Amy\"}'),(N'3', N'{\"age\": \"10\", \"name\": \"梁朝富\"}'),(N'4', N'{\"age\": \"10\", \"name\": \"梁朝富\"}'),(N'5', N'{\"age\": \"24\", \"name\": \"梁朝伟\"}'),(N'6', N'{\"openid\": \"oOPChs5tikOoyZUXrtphNz1IBdvA\", \"meet_id\": 931, \"page_size\": 1000, \"page_index\": 1}'),(N'7', N'{\"code\": 0, \"data\": [{\"hb_id\": \"30940afae4e4c3aea59c2f9c2527a966\", \"money\": 2032, \"status\": 1, \"meet_id\": 931, \"open_id\": \"oOPChs5tikOoyZUXrtphNz1IBdvA\", \"start_time\": \"2016-08-04 02:36:58\"}, {\"hb_id\": \"0a9c0682b768dfd7438e29541086709e\", \"money\": 0, \"status\": 0, \"meet_id\": 931, \"open_id\": \"oOPChs5tikOoyZUXrtphNz1IBdvA\", \"start_time\": \"2016-08-04 02:16:00\"}], \"total_money\": 2032}')";
var_dump(my_query($conn, $sql));

echo "insert data success [linker_two][json_tab] ..." . PHP_EOL;


sqlsrv_close($conn);

exit;


function my_query($conn, $sql): bool
{
    $stmt = sqlsrv_prepare($conn, $sql);
    if (!$stmt) {
        exit('sqlsrv_prepare');
    }
    $result = sqlsrv_execute($stmt);

    if ($result === false) {
        print_r(sqlsrv_errors());
        exit;
    }
    return $result;
}