<?php


namespace App\Redis\Process;


use App\MongoDb\Driver;
use EasySwoole\Component\Di;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\HttpClient\HttpClient;
use MongoDB\Client;

const QueueName = 'product_queue';

class ProductProcess extends AbstractProcess
{

    protected function run($arg)
    {

        // TODO: Implement run() method.
        go(function () {
//            for ($i = 1; $i <= 3; $i++) {
                $this->aa(["https://api.ehshig.net/singer/getSingerType/type/1/location/all/lo/all/language/cn"]);
                $this->aa(["https://api.ehshig.net/singer/getSingerType/type/2/location/all/lo/all/language/cn"]);
                $this->aa(["https://api.ehshig.net/singer/getSingerType/type/3/location/all/lo/all/language/cn"]);
                $this->aa(["https://api.ehshig.net/singer/getSingerType/type/5/location/all/lo/all/language/cn"]);
//            }
        });
    }

    private function aa($songUrlList=[]){
        go(function () use ($songUrlList){

            /** @var Client $client */
            $client = Di::getInstance()->get(Driver::DRIVER_MONGO_DB);
            $db = $client->selectDatabase('zh-a')->selectCollection('song');
            while (1)  {
                $getSongFirst = "https://api.ehshig.net/vip/path/getPathTest";
                $prefix = "https://api.ehshig.net/";
//                $songUrlList = [
//                            "https://api.ehshig.net/singer/getlist/sex/0/app/android/version/63/language/cn",
//                            "https://api.ehshig.net/singer/getListLocation/location/all/language/cn",
//                    "https://api.ehshig.net/singer/getSingerType/type/1/location/all/lo/all/language/cn",
//                    "https://api.ehshig.net/singer/getSingerType/type/2/location/all/lo/all/language/cn",
//                    "https://api.ehshig.net/singer/getSingerType/type/3/location/all/lo/all/language/cn",
//                            "https://api.ehshig.net/singer/getSingerType/type/4/location/all/lo/all/language/cn",
//                    "https://api.ehshig.net/singer/getSingerType/type/5/location/all/lo/all/language/cn",
//                ];

                foreach ($songUrlList as $songUrl) {
                    $httpClient = new HttpClient($songUrl);
                    $json = $httpClient->get()->json();
                    foreach ($json->list as $i => $v){
                        //歌手详情
                        $httpClientGetListBySinger = new HttpClient($prefix.$v->murl."/language/cn");
                        $getListBySingerJson = $httpClientGetListBySinger->get()->json();
                        $singer = $getListBySingerJson->singer;
                        foreach ($getListBySingerJson->list as $ii => $vv){
                            $httpGetSongFirst = new HttpClient($getSongFirst);
                            $d = [
                                "ssr"=>"26aaf850-05c6-4126-92e5-b99dc5e7d190",
                                "divice"=>"c7c90a2bb986fb4f49bb298b29a289a0a059",
                                "token"=>"e7b967c8-6c06-2153-2bf1-bb92ed1158f8",
                                "uuid"=>"c7c90a2bb986fb4f49bb298b29a289a0a059",
                                "id"=>$vv->id,
                                "language"=>"cn",
                                "tokenstr"=>"159c9b8939643e715f54e873646e0223",
                            ];
                            $data=[];
                            if(isset($vv->listingpay) && $vv->listingpay == 1){
                                $data = [
                                    'singer_id'=>$v->id??"",
                                    'singer_name'=>$v->name??"",
                                    'singer_photos'=>$v->photos??"",
                                    'singer_songNum'=>$v->songNum??"",
                                    'singer_location'=>$singer->location??"",
                                    'singer_songnum'=>$singer->songnum??0,
                                    'singer_isfavorite'=>$singer->isfavorite??"",

                                    'song_id'=>$vv->id??"",
                                    'song_ids'=>$vv->ids??"",
                                    'song_name'=>$vv->name??"",
                                    'song_sn'=>$vv->sn??"",
                                    'song_vip'=>$vv->vip??"",
                                    'song_listingpay'=>$vv->listingpay??"",
                                    'song_name_bc'=>"",
                                    'song_photos'=>"",
                                    'song_path'=>"",
                                ];
                            }{
                                //音乐
                                $getSongFirstJson = $httpGetSongFirst->post($d)->json();

                                $httpGetSongFirst2 = new HttpClient("http://pc.ehshig.net/album/getLrc/id/{$vv->id}");
                                $getSongFirstJson2 = $httpGetSongFirst2->post()->json();
                                if($getSongFirstJson->errorCode ==0){
                                    $getSongFirstJson->path;
                                    $data = [
                                        'singer_id'=>$v->id??"",
                                        'singer_name'=>$v->name??"",
                                        'singer_photos'=>$v->photos??"",
                                        'singer_songNum'=>$v->songNum??"",
                                        'singer_location'=>$singer->location??"",
                                        'singer_songnum'=>$singer->songnum??0,
                                        'singer_isfavorite'=>$singer->isfavorite??"",

                                        'song_id'=>$vv->id??"",
                                        'song_ids'=>$vv->ids??"",
                                        'song_name'=>$vv->name??"",
                                        'song_sn'=>$vv->sn??"",
                                        'song_vip'=>$vv->vip??"",
                                        'lrc'=>$getSongFirstJson2->lrc??"",
                                        'song_listingpay'=>0,
                                        'song_name_bc'=>$getSongFirstJson->name_bc??"",
                                        'song_photos'=>$getSongFirstJson->photos??"",
                                        'song_path'=>$getSongFirstJson->path??"",
                                    ];
                                }
                            }
                            if(!$db->findOne(['id'=>$vv->id])){
                                $db->insertOne(['id'=>$vv->id,'data'=>$data]);
                                Logger::getInstance()->console("歌曲:".$vv->name."|ID:".$vv->id."已进入队列");
                            }else{
                                Logger::getInstance()->console("歌曲:".$vv->name."已存在");
                            }
                        }
                    }
                }
                \co::sleep(0.5);
            }
        });
    }
}