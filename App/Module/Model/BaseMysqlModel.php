<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/2 9:24 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\Module\Model;


use EasySwoole\ORM\AbstractModel;

class BaseMysqlModel extends AbstractModel
{
    protected $autoTimeStamp = true;
    protected $createTime = "create_time";
    protected $updateTime = "update_time";
    protected $deleteTime = "delete_time"; /** todo 做软删除使用 **/

    /** todo 软删除方法 **/
}