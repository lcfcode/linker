<?php
/**
 * @author 梁朝富
 * @mail lcf@jionx.com
 * @function 调试使用的打印函数
 */

if (!function_exists('p')) {
    function p($var)
    {
        $phprunMode = php_sapi_name();
        $flag = stristr('cli', $phprunMode);
        if ($flag) {
            echo PHP_EOL . "------------------------------------------------------------" . PHP_EOL;
            (is_bool($var) || is_null($var)) ? var_dump($var) : print_r($var);
            echo PHP_EOL . "------------------------------------------------------------" . PHP_EOL;
            return;
        }
        echo '<hr><pre>';
        (is_bool($var) || is_null($var)) ? var_dump($var) : print_r($var);
        echo '</pre><br>';
        return;
    }
}
//var_dump()
if (!function_exists('pd')) {
    function pd($var)
    {
        $phprunMode = php_sapi_name();
        $flag = stristr('cli', $phprunMode);
        if ($flag) {
            echo PHP_EOL . "------------------------------------------------------------" . PHP_EOL;
            var_dump($var);
            echo PHP_EOL . "------------------------------------------------------------" . PHP_EOL;
            return;
        }
        echo '<hr><pre>';
        var_dump($var);
        echo '</pre><hr>';
        return;
    }
}
if (!function_exists('console')) {
    function console($content)
    {
        $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        echo "<script>console.group('debug.info');console.info({$content});console.groupEnd();</script>";
    }
}
/**
 * @link https://www.php.net/manual/zh/function.var-dump.php
 * 方法来源于网上
 */
if (!function_exists('do_dump')) {
    function do_dump(&$var, $var_name = NULL, $indent = NULL, $reference = NULL)
    {
        $do_dump_indent = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
        $reference = $reference . $var_name;
        $keyvar = 'the_do_dump_recursion_protection_scheme';
        $keyname = 'referenced_object_name';
        if (is_array($var) && isset($var[$keyvar])) {
            $real_var = &$var[$keyvar];
            $real_name = &$var[$keyname];
            $type = ucfirst(gettype($real_var));
            echo "$indent$var_name <span style='color:#a2a2a2'>$type</span> = <span style='color:#e87800;'>&amp;$real_name</span><br>";
            return;
        }
        $var = array($keyvar => $var, $keyname => $reference);
        $avar = &$var[$keyvar];
        $type = ucfirst(gettype($avar));
        if ($type == "String") {
            $type_color = "<span style='color:#008000'>";
        } elseif ($type == "Integer") {
            $type_color = "<span style='color:#ff4343'>";
        } elseif ($type == "Double") {
            $type_color = "<span style='color:#0099c5'>";
            $type = "Float";
        } elseif ($type == "Boolean") {
            $type_color = "<span style='color:#92008d'>";
        } elseif ($type == "NULL") {
            $type_color = "<span style='color:#901717'>";
        } else {
            $type_color = "<span style='color:#000000'>";
        }

        if (is_array($avar)) {
            $count = count($avar);
            echo $indent . ($var_name ? "{$var_name} => " : "") . "<span style='color:#a2a2a2'>{$type} ({$count})</span><br>{$indent}(<br>";
            $keys = array_keys($avar);
            foreach ($keys as $name) {
                $value = &$avar[$name];
                do_dump($value, "['{$name}']", $indent . $do_dump_indent, $reference);
            }
            echo "$indent)<br>";
        } elseif (is_object($avar)) {
            echo "{$indent}{$var_name} <span style='color:#a2a2a2'>{$type}</span><br>{$indent}(<br>";
            foreach ($avar as $name => $value) {
                do_dump($value, $name, $indent . $do_dump_indent, $reference);
            }
            echo "{$indent})<br>";
        } elseif (is_int($avar)) {
            echo "{$indent}{$var_name} = <span style='color:#a2a2a2'>{$type}(" . strlen($avar) . ")</span> {$type_color}{$avar}</span><br>";
        } elseif (is_string($avar)) {
            echo "{$indent}{$var_name} = <span style='color:#a2a2a2'>{$type}(" . strlen($avar) . ")</span> {$type_color}\"{$avar}\"</span><br>";
        } elseif (is_float($avar)) {
            echo "{$indent}{$var_name} = <span style='color:#a2a2a2'>{$type}(" . strlen($avar) . ")</span> $type_color$avar</span><br>";
        } elseif (is_bool($avar)) {
            echo "{$indent}{$var_name} = <span style='color:#a2a2a2'>{$type}(" . strlen($avar) . ")</span> $type_color" . ($avar == 1 ? "TRUE" : "FALSE") . "</span><br>";
        } elseif (is_null($avar)) {
            echo "{$indent}{$var_name} = <span style='color:#a2a2a2'>{$type}(" . strlen($avar) . ")</span> {$type_color}NULL</span><br>";
        } else {
            echo "{$indent}{$var_name} = <span style='color:#a2a2a2'>{$type}(" . strlen($avar) . ")</span> {$avar}<br>";
        }
        $var = $var[$keyvar];
    }
}

