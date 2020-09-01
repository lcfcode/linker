#### mysql json格式查询

    -- 表达式 ： json列->'$.键'
    -- select data ->'$.name' from json_tab;
    
    -- 等价于 ：JSON_EXTRACT(json列 , '$.键')
    -- select json_extract(data, '$.name') from json_tab;
    
    -- select json_extract(data, '$.data') from json_tab;
    -- select json_extract(data, '$.data') from json_tab where uid=7;
    
    -- select json_extract(data, '$[0]') from json_tab where uid=7;
    
    -- select json_extract(data, '$[0].data') from json_tab where uid=7;
    --<-- 上下两句等价 -->
    -- select json_extract(data, '$.data') from json_tab where uid=7;
    
    -- select json_extract(data, '$[0].data[0]') from json_tab where uid=7;
    
    -- select json_extract(data, '$[0].data[0].hb_id') from json_tab where uid=7;
    
    -- select json_extract(data, '$.name') from json_tab;
    -- <-- 上下两句等价 -->
    -- select json_extract(data, '$[0].name') from json_tab;
    
    -- update json_tab set json_extract(data, '$[0].name')='张学友' where uid=1;
    
    -- select json_extract(data, '$.name'),json_extract(data,'$.address') from json_tab where json_extract(data, '$.name') = 'David';
    
    -- select json_extract(data, '$.name') from json_tab where json_extract(data, '$.name') like '%a%';
    
    -- select json_extract(data, '$.name') from json_tab where json_extract(data, '$.name') like '%朝%';


#### mysql分组例子


    select * from employee group by d_id,sex;
    +-----+------+------+-----+-----+------------+
    | num | d_id | name | age | sex | homeaddr   |
    +-----+------+------+-----+-----+------------+
    |   1 | 1001 | 张三 |  26 | 男  | beijinghdq |
    |   2 | 1002 | 李四 |  24 | 女  | basle      |
    |   3 | 1003 | 王五 |  25 | 男  | khmnm      |
    |   4 | 1004 | 赵六 |  15 | 男  | en         |
    +-----+------+------+-----+-----+------------+
    4 rows in set
    
    select * from employee group by sex;
    +-----+------+------+-----+-----+------------+
    | num | d_id | name | age | sex | homeaddr   |
    +-----+------+------+-----+-----+------------+
    |   2 | 1002 | 李四 |  24 | 女  | basle      |
    |   1 | 1001 | 张三 |  26 | 男  | beijinghdq |
    +-----+------+------+-----+-----+------------+
    2 rows in set
    
    select sex from employee group by sex;
    +-----+
    | sex |
    +-----+
    | 女  |
    | 男  |
    +-----+
    2 rows in set
    
    select sex,avg(age) from employee group by sex;
    +-----+----------+
    | sex | avg(age) |
    +-----+----------+
    | 女  | 24.0000  |
    | 男  | 22.0000  |
    +-----+----------+
    2 rows in set
    
    select sex,count(sex) from employee group by sex;
    +-----+------------+
    | sex | count(sex) |
    +-----+------------+
    | 女  |          1 |
    | 男  |          3 |
    +-----+------------+
    2 rows in set
    
    select sex,count(sex) from employee group by sex having count(sex)>2;
    +-----+------------+
    | sex | count(sex) |
    +-----+------------+
    | 男  |          3 |
    +-----+------------+
    1 row in set
    
    select sex,count(sex) from employee group by sex having count(sex)>2;
    +-----+------------+
    | sex | count(sex) |
    +-----+------------+
    | 男  |          3 |
    +-----+------------+
    1 row in set
    
    select sex,count(sex) from employee group by sex having count(sex)>0;
    +-----+------------+
    | sex | count(sex) |
    +-----+------------+
    | 女  |          1 |
    | 男  |          3 |
    +-----+------------+
    2 rows in set