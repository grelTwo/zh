<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/13 6:21 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\Common\Utils;


class DocParserFactory
{
    private static $di;

    public static function getInstance(): DocParser
    {
        if (self::$di == null) {
            self::$di = new DocParser();
        }
        return self::$di;
    }

}