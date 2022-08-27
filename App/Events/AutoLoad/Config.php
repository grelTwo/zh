<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/14 4:12 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\Events\AutoLoad;

use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Command\Utility;

class Config
{
    use Singleton;

    /**
     * 自动加载配置文件
     */
    public function autoLoad()
    {
        try {
            $instance = \EasySwoole\EasySwoole\Config::getInstance();
            $path = EASYSWOOLE_ROOT . '/' . 'Conf' . '/';
            $files = scandir($path) ?? [];

            foreach ($files as $file) {

                $routerFile = $path . $file;
                if (!file_exists($routerFile) || ($file == '.' || $file == '..')) {
                    continue;
                }

                $data = require_once $routerFile ?? [];
                foreach ($data as $key => $conf) {
                    $instance->setConf(strtolower(basename($file, '.php')), (array)$data);
                }

                echo Utility::displayItem('Config', "{$path}{$file}");
                echo "\n";
            }
        } catch (\Throwable $throwable) {
            echo 'Config Initialize Fail :' . $throwable->getMessage();
        }
    }
}