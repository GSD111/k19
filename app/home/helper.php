<?php

/**
 * @param $msg 提示信息
 * @param $url 跳转地址
 * 失败跳转
 */
function error($msg)
{
    $str = <<<str

<script>
alert("$msg");
window.history.go(-1);
</script>
str;
    echo $str;
    die;
}
