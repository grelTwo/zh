<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/3 10:10 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\WebSocket\Actions\Index;


use App\WebSocket\Actions\ActionPayload;

class Demo extends ActionPayload
{
    protected $action = "serverDemo";//和前端对应方法

    protected $info;

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param mixed $info
     */
    public function setInfo($info): void
    {
        $this->info = $info;
    }


}