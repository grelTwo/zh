<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/14 3:52 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\HttpController\MiniController;


use App\MongoDb\Driver;
use EasySwoole\Component\Di;
use EasySwoole\Validate\Validate;

class Auth extends BaseController
{

    /**
     *
     * @ValidateParams true
     */
    public function test()
    {
        /** @var \MongoDB\Client $client */
        $client = Di::getInstance()->get(Driver::DRIVER_MONGO_DB);
        $db = $client->selectDatabase('zh-a')->selectCollection('song');
//        $db = $client->selectDatabase('core')->selectCollection('onlineAccountPro');
        $i = 1;
        $z = 1;
        while (1){
            $where = ['time'=>'more'];
            $info = $db->findOne($where);
            if($info['data']['song_listingpay']==1){
                $db->updateOne(['id'=>$info['id']],['$set'=>['local_file'=>'loading','time'=>0]]);
                continue;
            }
//            if(!empty($info['data']['song_name'])){
//                $localFileName = $info['data']['song_name'].'.mp3';
//            }else{
//                $localFileName = $info['data']['song_sn'].'.mp3';
//            }
//            $localFileName = str_replace(' ','-',$localFileName);

            $url = $info['data']['song_path'];

            $a = explode('/',$url);
            $len = count($a);
            $url = "";
            foreach ($a as $ii => $re) {
                if($len-1>$ii){
                    $url.=$re."/";
                }else{
                    $songName = urlencode($re);
                }
            }
            $url .= $songName;

            $singer_location = $info['data']['singer_location'];

            $localFileName =$songName;

//            $local_url = "/tmp/zh-a/".$singer_location."/".$localFileName;

            $db->updateOne(['id'=>$info['id']],['$set'=>['local_file'=>$localFileName,'time'=>1]]);
            $this->aa($url,$singer_location,$localFileName,$info,$db,$z);
            var_dump("第".$i++."首歌".$localFileName."下载完毕");
            \co::sleep(0.5);
        }
    }

    public function aa($url,$singer_location,$name,$dbinfo,$db,&$z){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $local_url = "/tmp/zh-a/".$singer_location."/";
        if (!file_exists($local_url)){
            mkdir ($local_url,0777,true);
        }
        $info = curl_exec($curl);
        curl_close($curl);
        $newFileName = $local_url.$name;
        $fp2 = @fopen($newFileName, "w+");
        if(!is_bool($info)){
            fwrite($fp2, $info);
        }else{
            var_dump('无法下载'.($z++).$url);
        }
        fclose($fp2);
        if(filesize($newFileName)==0){
            $db->updateOne(['id'=>$dbinfo['id']],['$set'=>['time'=>'more']]);
            \co::sleep(1);
        }
        return $newFileName;//返回新的文件路径及文件名
    }
}