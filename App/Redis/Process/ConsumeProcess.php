<?php


namespace App\Redis\Process;


use App\MongoDb\Driver;
use App\Redis\RedisQueue;
use Co\Http\Client;
use EasySwoole\Component\Di;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\HttpClient\HttpClient;
use EasySwoole\Utility\File;

const CONSUME = 'consume_queue';

class ConsumeProcess extends AbstractProcess
{

    protected function run($arg)
    {
        // TODO: Implement run() method.
        go(function (){
            /** @var \MongoDB\Client $client */
            $client = Di::getInstance()->get(Driver::DRIVER_MONGO_DB);
            $db = $client->selectDatabase('zh-a')->selectCollection('song');
            $res = $db->aggregate([
                ['$skip'=>1],
                ['$limit'=>2]
            ]);
            var_dump($res);

        });

    }
}