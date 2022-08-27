<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/2 10:00 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\Events;


use App\Events\AutoLoad\Router;
use App\MongoDb\Driver;
use App\Redis\Process\ProductProcess;
use App\WebSocket\WebSocketEvents;
use App\WebSocket\WebSocketParser;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Config as GlobalConfig;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\RedisPool\RedisPool;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\FileWatcher\FileWatcher;
use EasySwoole\FileWatcher\WatchRule;
use EasySwoole\EasySwoole\Swoole\EventRegister as EZEventRegister;
use EasySwoole\ORM\Db\Config;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use App\Events\Crontab\DoSomething;
use EasySwoole\Socket\Dispatcher;
use Throwable;

class EventRegister
{
    /**
     * @throws Throwable
     */
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        /** MYSQL ORM 配置&注册连接 */
        $config = new Config(GlobalConfig::getInstance()->getConf('MYSQL'));
        DbManager::getInstance()->addConnection(new Connection($config));

        /** Redis 注册连接池 **/
        $redisConfig = new RedisConfig(GlobalConfig::getInstance()->getConf('REDIS'));
        RedisPool::getInstance()->register($redisConfig, 'redis');

        /** 加载配置文件 */
        AutoLoad\Config::getInstance()->autoLoad();

        /** 路由初始化 */
        Router::getInstance()->autoLoad();


    }

    /**
     * @param EZEventRegister $register
     * @throws Throwable
     */
    public static function mainServerCreate(\EasySwoole\EasySwoole\Swoole\EventRegister $register)
    {
        /** 热重启 */
        $watcher = new FileWatcher();
        $rule = new WatchRule(EASYSWOOLE_ROOT . "/App"); // 设置监控规则和监控目录
        $watcher->addRule($rule);
        $watcher->setOnChange(function () {
//            Logger::getInstance()->info('file change ,reload!!!');
            ServerManager::getInstance()->getSwooleServer()->reload();
        });
        $watcher->attachServer(ServerManager::getInstance()->getSwooleServer());

        $register->add($register::onWorkerStart, function () {
            // 链接预热
            // ORM 1.4.31 版本之前请使用 getClientPool()
            DbManager::getInstance()->getConnection()->__getClientPool()->keepMin();
        });

        /** 定时任务 **/
        // 配置定时任务
        $crontabConfig = new \EasySwoole\Crontab\Config();
        // 1.设置执行定时任务的 socket 服务的 socket 文件存放的位置，默认值为 当前文件所在目录
        // 这里设置为框架的 Temp 目录
        $crontabConfig->setTempDir(EASYSWOOLE_TEMP_DIR);
        // 2.设置执行定时任务的 socket 服务的名称，默认值为 'EasySwoole'
        $crontabConfig->setServerName('EasySwoole');
        // 3.设置用来执行定时任务的 worker 进程数，默认值为 3
        $crontabConfig->setWorkerNum(3);
        // 4.设置定时任务执行出现异常的异常捕获回调
        $crontabConfig->setOnException(function (Throwable $throwable) {
            // 定时任务执行发生异常时触发（如果未在定时任务类的 onException 中进行捕获异常则会触发此异常回调）
        });
        // 创建定时任务实例
//        $crontab = Crontab::getInstance($crontabConfig);

        // 注册定时任务
//        $crontab->register(new DoSomething());

        /** webSocket */
        // 注册服务事件
        $register->add(EZEventRegister::onOpen, [WebSocketEvents::class, 'onOpen']);
        $register->add(EZEventRegister::onClose, [WebSocketEvents::class, 'onClose']);

        // 收到用户消息时处理
        $conf = new \EasySwoole\Socket\Config;
        $conf->setType($conf::WEB_SOCKET);
        $conf->setParser(new WebSocketParser);
        $dispatch = new Dispatcher($conf);
        $register->set(EZEventRegister::onMessage,
            function (\Swoole\Server $server, \Swoole\WebSocket\Frame $frame) use ($dispatch) {
                $dispatch->dispatch($server, $frame->data, $frame);
            });

        try {
            /** MongoDB 注册 **/
            $mongoClient = new Driver();
            Di::getInstance()->set(Driver::DRIVER_MONGO_DB, $mongoClient->getDB());
        } catch (Throwable $e) {
            var_dump($e->getMessage());
        }

        /** 爬虫 */
//        ServerManager::getInstance()->getSwooleServer()->addProcess((new ProductProcess())->getProcess());
    }


}