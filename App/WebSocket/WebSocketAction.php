<?php

namespace App\WebSocket;

/**
 * 当前端接受一下常量时做的操作,需要跟前段协商规定
 * Class WebSocketAction
 * @package App\WebSocket
 */
class WebSocketAction
{
    /** 例如：给前端发送INDEX_TEST常量，前端找对应的方法做数据处理 */
    // 1xx INDEX 测试类消息
    const INDEX_TEST = 001;   // 给前端返回测试数据方法
}
