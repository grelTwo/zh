<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/7 3:38 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\HttpController\HttpController;


use App\Common\Utils\DocParserFactory;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Http\AbstractInterface\Controller;
use ReflectionClass;
use Throwable;

abstract class BaseController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function afterAction(?string $actionName): void
    {
        try {
            $reflectionClass = new ReflectionClass (get_called_class());
            $reflectionFunction = $reflectionClass->getMethod($actionName);

            if (isLogin()) {

                $username = "xzh";
                $userId = 1;

                $parser = DocParserFactory::getInstance();

                $classDocArr = $parser->parse($reflectionClass->getDocComment());
                $funDocArr = $parser->parse($reflectionFunction->getDocComment());

                if (isset($classDocArr['description']) && !empty($classDocArr['description'])) {
                    $classDescription = $classDocArr['description'];
                } else {
                    $classDescription = $classDocArr['long_description'];
                }

                if (isset($funDocArr['description']) && !empty($funDocArr['description'])) {
                    $funDescription = $funDocArr['description'];
                } else {
                    $funDescription = $funDocArr['long_description'];
                }

                $info = "{后台用户:".$username.",ID:".$userId."}在".$classDescription."模块，做了".$funDescription."操作";
                Logger::getInstance()->info($info);
            }
        } catch (Throwable $e) {
            var_dump($e->getMessage());
        }


    }
}