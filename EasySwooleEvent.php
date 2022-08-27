<?php


namespace EasySwoole\EasySwoole;


use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use Throwable;

class EasySwooleEvent implements Event
{
    /**
     * @throws Throwable
     */
    public static function initialize()
    {
        \App\Events\EventRegister::initialize();
    }

    /**
     * @param EventRegister $register
     * @throws Throwable
     */
    public static function mainServerCreate(EventRegister $register)
    {
        \App\Events\EventRegister::mainServerCreate($register);
    }

}