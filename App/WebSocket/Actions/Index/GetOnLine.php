<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/9 7:27 上午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\WebSocket\Actions\Index;


use App\WebSocket\Actions\ActionPayload;

class GetOnLine extends ActionPayload
{
    protected $action = "GET_ON_LINE";

    protected $onLineInfo;

    /**
     * @return mixed
     */
    public function getOnLineInfo()
    {
        return $this->onLineInfo;
    }

    /**
     * @param mixed $onLineInfo
     */
    public function setOnLineInfo($onLineInfo): void
    {
        $this->onLineInfo = $onLineInfo;
    }

}