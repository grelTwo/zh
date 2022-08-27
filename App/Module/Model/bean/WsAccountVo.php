<?php

namespace App\Module\Model\bean;

use EasySwoole\Spl\SplBean;

class WsAccountVo extends SplBean
{
    protected $app_code;

    protected $app_name;

    protected $account_id;

    protected $account_name;

    protected $fd;

    protected $login_time;

    protected $heartbeat_time;

    protected $ip;

    protected $exp_time;

    /**
     * @return mixed
     */
    public function getAppCode()
    {
        return $this->app_code;
    }

    /**
     * @return mixed
     */
    public function getAppName()
    {
        return $this->app_name;
    }

    /**
     * @return mixed
     */
    public function getAccountId()
    {
        return $this->account_id;
    }

    /**
     * @return mixed
     */
    public function getAccountName()
    {
        return $this->account_name;
    }

    /**
     * @return mixed
     */
    public function getFd()
    {
        return $this->fd;
    }

    /**
     * @return mixed
     */
    public function getLoginTime()
    {
        return $this->login_time;
    }

    /**
     * @return mixed
     */
    public function getHeartbeatTime()
    {
        return $this->heartbeat_time;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return mixed
     */
    public function getExpTime()
    {
        return $this->exp_time;
    }

    /**
     * @param mixed $exp_time
     */
    public function setExpTime($exp_time): void
    {
        $this->exp_time = $exp_time;
    }

    /**
     * @param mixed $fd
     */
    public function setFd($fd): void
    {
        $this->fd = $fd;
    }


}
