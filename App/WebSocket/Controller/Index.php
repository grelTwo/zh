<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/3 10:08 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\WebSocket\Controller;


use App\MongoDb\Driver;
use App\WebSocket\Actions\Index\Demo;
use App\WebSocket\Actions\Index\Heartbeat;
use App\WebSocket\Actions\Index\GetOnLine;
use EasySwoole\Component\Di;
use EasySwoole\Socket\Client\WebSocket as WebSocketClient;
use MongoDB\Client;

class Index extends Base
{
    public function index()
    {
//        $message = new Demo();
//        $message->setInfo("info...");
//        $this->response()->setMessage($message);
    }

    public function getOnline()
    {
        /** @var Client $client */
        $client = Di::getInstance()->get(Driver::DRIVER_MONGO_DB);
        $dbOnLineAccountPro = $client->selectDatabase('core')->selectCollection('onlineAccount');

        $where = [
            [
                '$group' => [
                    '_id' => ['account_id' => '$account_id', 'account_name' => '$account_name', 'app_name' => '$app_name', 'app_code' => '$app_code'],
                    'count' => [
                        '$sum' => 1
                    ]
                ]
            ],
            [
                '$group' => [
                    '_id' => ['app_name' => '$_id.app_name'],
                    'count' => [
                        '$sum' => 1
                    ]
                ]
            ],
            [
                '$project' => [
                    '_id'=>0,
                    'app_name'=>'$_id.app_name',
                    'count'=>1
                ]
            ],
            [
                '$sort' => [
                    'app_name' => 1,
                ]
            ]
        ];

        $res= $dbOnLineAccountPro->aggregate($where)->toArray();

        $message = new GetOnLine();
        $message->setOnLineInfo($res);
        $this->response()->setMessage($message);

    }

    public function version()
    {
        /** @var WebSocketClient $client */
        $client = $this->caller()->getClient();
        $fd = $client->getFd();
        $broadcastPayload = $this->caller()->getArgs();
        //记录版本号不做其他事
        /** @var Client $client */
        $client = Di::getInstance()->get(Driver::DRIVER_MONGO_DB);
        $dbOnLineAccountPro = $client->selectDatabase('core')->selectCollection('onlineAccountPro');
        $dbOnLineAccountPro->updateOne(['fd'=>$fd],['$set'=>['version'=>$broadcastPayload['version']]]);
    }

    /**
     * 心跳
     */
    public function heartbeat()
    {
        $heartbeat = new Heartbeat();
        $this->response()->setMessage($heartbeat);
    }

}