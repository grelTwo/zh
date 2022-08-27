<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/7 2:26 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\WebSocket\Actions\Index;


use App\WebSocket\Actions\ActionPayload;

class Heartbeat extends ActionPayload
{
    protected $action = "serverHeartbeat";
}