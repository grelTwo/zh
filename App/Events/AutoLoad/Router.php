<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/14 4:08 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\Events\AutoLoad;


use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Command\Utility;
use FastRoute\RouteCollector;

class Router
{
    protected $router = [];

    use Singleton;

    /**
     * 待注入路由配置
     */
    public function autoLoad(): void
    {
        try {
            $path = EASYSWOOLE_ROOT . '/' . 'App' . '/' . 'Module' . '/';
            $files = scandir($path) ?? [];

            foreach ($files as $key => $dir) {
                //过滤非目录
                if (strpos($dir, '.') !== false) {
                    unset($files[$key]);
                }
            }

            // 获取路由文件下所有目录
            foreach ($files as $dir) {
                $routerFile = $path . $dir . '/router.php';

                if (!file_exists($routerFile)) {
                    continue;
                }
                $data = require_once $routerFile;
                echo  Utility::displayItem('Router',$routerFile);
                echo "\n";

                $this->router[] = $data;
            }

        } catch (\Throwable $throwable) {
            echo 'Router Initialize Fail :' . $throwable->getMessage();
        }
    }

    /**
     * 路由注册
     * @param RouteCollector $routeCollector
     */
    public function initialize(RouteCollector $routeCollector): void
    {
        foreach ($this->router as $file) {
            foreach ($file as $rKey => $rType) {
                foreach ($rType as $perfix => $routerFunction) {
                    $routeCollector->addGroup($rKey . $perfix, $routerFunction);
                }
            }
        }
    }
}