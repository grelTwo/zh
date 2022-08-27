<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/3 8:45 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\WebSocket;


use App\Module\Model\bean\WsAccountVo;
use App\MongoDb\Driver;
use EasySwoole\Component\Di;
use MongoDB\Client;
use Swoole\Http\Request;
use Swoole\WebSocket\Server;
use \Exception;

class WebSocketEvents
{
    /**
     * 打开了一个链接
     * @param Server $server
     * @param Request $request
     */
    public static function onOpen(Server $server, Request $request)
    {
        $fd = $request->fd;
        //onlineAccountMirror

        /** @var Client $client */
        $client = Di::getInstance()->get(Driver::DRIVER_MONGO_DB);
        $dbOnLineAccount = $client->selectDatabase('core')->selectCollection('onlineAccount');
        $dbOnLineAccountPro = $client->selectDatabase('core')->selectCollection('onlineAccountPro');
        $appCode = [
            ['name' => 'a网', 'code' => 'e0ed2d0fc4bba7465200ca6dd6c6feb2', 'account_id' => '14982021'],
            ['name' => 'z网', 'code' => '52e560fd042568b7a6320c0069a1b63d', 'account_id' => '955656972'],
            ['name' => 'l网', 'code' => '794768ef11a359618e247e25a88ad937', 'account_id' => '686261408'],
            ['name' => '中台', 'code' => '9b12d0b12138b872fecc1b4bc2225939', 'account_id' => '115656068']
        ];
        $rand = rand(0, 3);
        $wsAccount = new WsAccountVo([
            'account_id' => $appCode[$rand]['account_id'],
            'account_name' => 'user_' . $appCode[$rand]['account_id'],
            'app_name' => $appCode[$rand]['name'],
            'login_time' => isset($user['iat']) ? date('Y-m-d H:i:s', $user['iat']) : '',
            'exp_time' => isset($user['exp']) ? date('Y-m-d H:i:s', $user['exp']) : '',
            'app_code' => $appCode[$rand]['code'],
            'fd' => $fd,
            'ip' => $request->server['remote_addr'] ?? '0.0.0.0',
            'heartbeat_time' => date('Y-m-d H:i:s',time()),
        ]);
        $where = ['account_id' => $wsAccount->getAccountId(), 'app_code' => $wsAccount->getAppCode()];

        $onLineAccount = $dbOnLineAccount->findOne($where);
        if ($onLineAccount) {
            $dbOnLineAccount->updateOne($where, ['$set' => $wsAccount->toArray()]);
            $dbOnLineAccountPro->updateOne($where, ['$set' =>['heartbeat_time'=>date('Y-m-d H:i:s',time())]]);
        } else {
            $dbOnLineAccount->insertOne($wsAccount->toArray());
            $dbOnLineAccountPro->insertOne($wsAccount->toArray());
        }

        /** 当socket连接时做的事情 **/
        /*// 为用户分配身份并插入到用户表
        $fd = $request->fd;
        if (isset($request->get['username']) && !empty($request->get['username'])) {
            $username = $request->get['username'];
            $avatar = Gravatar::makeGravatar($username . '@swoole.com');
        } else {
            $random = Random::character(8);
            $avatar = Gravatar::makeGravatar($random . '@swoole.com');
            $username = '神秘乘客' . $random;
        }

        // 插入在线用户表
        OnlineUser::getInstance()->set($fd, $username, $avatar);

        // 发送广播告诉频道里的用户 有新用户上线
        $userInRoomMessage = new UserInRoom;
        $userInRoomMessage->setInfo(['fd' => $fd, 'avatar' => $avatar, 'username' => $username]);
        TaskManager::getInstance()->async(new BroadcastTask(['payload' => $userInRoomMessage->__toString(), 'fromFd' => $fd]));
        if (empty($request->get['is_reconnection']) || $request->get['is_reconnection'] == '0') {

            // 发送欢迎消息给用户
            $broadcastAdminMessage = new BroadcastAdmin;
            $broadcastAdminMessage->setContent("{$username}，欢迎乘坐EASYSWOOLE号特快列车，请系好安全带，文明乘车");
            $server->push($fd, $broadcastAdminMessage->__toString());

            // 提取最后10条消息发送给用户
            $lastMessages = ChatMessage::getInstance()->readMessage();
            $lastMessages = array_reverse($lastMessages);
            if (!empty($lastMessages)) {
                foreach ($lastMessages as $message) {
                    $server->push($fd, $message);
                }
            }
        }*/

    }

    /**
     * 链接被关闭时
     * @param \Swoole\Server $server
     * @param int $fd
     * @param int $reactorId
     * @throws Exception
     */
    public static function onClose(\Swoole\Server $server, int $fd, int $reactorId)
    {
        /** 当连接断开时做的事情 **/

        $info = $server->connection_info($fd);
        if (isset($info['websocket_status']) && $info['websocket_status'] !== 0) {

            /** @var Client $client */
            $client = Di::getInstance()->get(Driver::DRIVER_MONGO_DB);
            $db = $client->selectDatabase('core')->selectCollection('onlineAccount');
            /** 当退出的时候做个记录 */
            $db->updateOne(['fd'=>$fd], ['$set' => ['heartbeat_time'=>date('Y-m-d H:i:s',time())]]);
            /** 从统计在线用户表中移除 */
            $db->deleteOne(['fd' => $fd]);

//            // 移除用户并广播告知
//            OnlineUser::getInstance()->delete($fd);
//            $message = new UserOutRoom;
//            $message->setUserFd($fd);
//            TaskManager::getInstance()->async(new BroadcastTask(['payload' => $message->__toString(), 'fromFd' => $fd]));
        }
    }
}