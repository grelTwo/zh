<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/14 4:16 下午
 * Slogan     : 一行代码，亿万生活
 */

use FastRoute\RouteCollector;

return [
    '/api' => [
        '/mini-a' => static function (RouteCollector $route) {
            $route->addGroup('/auth', function (\FastRoute\RouteCollector $collector) {
                // 校验注册
                $collector->addRoute(['GET'], '/test', '/MiniController/Auth/test');
                $collector->addRoute(['GET'], '/a', '/HttpController/Index/index');
            });
        }
    ]
];