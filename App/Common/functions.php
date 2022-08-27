<?php
/**
 * Introduce  : >_< 公共方法
 * Create By  : xuzhihua
 * CreateTime : 2022/8/2 8:27 下午
 * Slogan     : 一行代码，亿万生活
 */

if (!function_exists("test")) {
    function test(): string
    {
        return "It's Test fun." . PHP_EOL;
    }
}

if (!function_exists('isLogin')) {
    function isLogin(): bool
    {
        return true;
    }
}
