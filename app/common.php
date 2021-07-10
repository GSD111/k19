<?php
// 应用公共文件


/*
 * 定义打印调试函数
 */
function p()
{
    $numargs = func_get_args();
    foreach ($numargs as $v) {
        if (request()->isCli()) {
            print_r($v);
            echo "\n";
        } else {
            echo "<pre>";
            print_r($v);
            echo "</pre>";
            echo '<hr>';
        }
    }
}