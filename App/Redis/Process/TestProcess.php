<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/21 2:21 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\Redis\Process;


use App\MongoDb\Driver;
use EasySwoole\Component\Di;
use EasySwoole\Component\Process\AbstractProcess;
use MongoDB\Client;

class TestProcess extends AbstractProcess
{

    protected function run($arg)
    {
        go(function (){
            /** @var Client $client */
            $client = Di::getInstance()->get(Driver::DRIVER_MONGO_DB);
            $db = $client->selectDatabase('zh-a')->selectCollection('song');

        });

    }
}