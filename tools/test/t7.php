<?php
$a=[1,2,3,4,5,6,7];


/**
 * @param $a
 * @user LCF
 * @date
 */
function tt($a)
{
    for($i=2,$j=1;$i<6,$j<9;$i++,$j++){
        echo 'i='.$i.PHP_EOL;
        echo 'j='.$j.PHP_EOL;
    }
}
tt($a);
//var_dump(tt($a));
