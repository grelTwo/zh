<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/3 7:40 上午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\Events\Crontab;


use EasySwoole\Crontab\JobInterface;
use Throwable;

class DoSomething implements JobInterface
{

    /**
     * 定义 Crontab 名称
     * @return string
     */
    public function jobName(): string
    {
        return "DoSomething";
    }

    /**
     * 定义执行规则
     * @return string
     */
    public function crontabRule(): string
    {
        return '*/1 * * * *';
    }

    /**
     * 定义执行逻辑
     */
    public function run()
    {
        var_dump("do something..");
    }

    /**
     * 定义异常捕获
     * @param Throwable $throwable
     */
    public function onException(Throwable $throwable)
    {
        // TODO: Implement onException() method.
    }
}