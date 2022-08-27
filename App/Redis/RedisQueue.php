<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/4 7:51 上午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\Redis;


use EasySwoole\Component\Singleton;
use EasySwoole\RedisPool\RedisPool;
use EasySwoole\Redis\Redis;
use Throwable;

/**
 * Redis队列
 */
class RedisQueue
{
    use Singleton;

    public static $redisName = 'redis';

    public function push($key, $value): ?bool
    {
        try {
            return RedisPool::invoke(function (Redis $redis) use ($key, $value) {
                //判断是否数组
                if (is_array($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                }
                return $redis->rPush($key, $value);
            }, self::$redisName, 0);
        } catch (Throwable $exception) {
            var_dump($exception->getMessage());
            return null;
        }

    }

    public function pop($key): ?bool
    {
        try {
            return RedisPool::invoke(function (Redis $redis) use ($key) {
                return $redis->lPop($key);
            }, self::$redisName, 0);
        } catch (Throwable $exception) {
            var_dump($exception->getMessage());
            return null;
        }
    }

}