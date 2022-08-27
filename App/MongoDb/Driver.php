<?php
/**
 * Introduce  : >_<
 * Create By  : xuzhihua
 * CreateTime : 2022/8/7 2:38 下午
 * Slogan     : 一行代码，亿万生活
 */

namespace App\MongoDb;


use EasySwoole\Component\Di;
use MongoDB\Client;

class Driver
{
    const DRIVER_MONGO_DB = "DRIVER_MONGO_DB";

    protected $db;
    private $host = "mongodb";
    private $user = "march";
    private $password = "March888";
    private $port = "27017";

    public function __construct(string $host = '', string $user = '', string $password = '', string $port = '')
    {
        if ($host) {
            $this->host = $host;
        }
        if ($user) {
            $this->user = $user;
        }
        if ($password) {
            $this->password = $password;
        }
        if ($port) {
            $this->port = $port;
        }
        $mongoUrl = "mongodb://" . $this->user . ":" . $this->password . "@" . $this->host . ":" . $this->port;
    }

    function getDB(): Client
    {
        if (!$this->db) {
            $mongoUrl = "mongodb://march:March888..@mongodb:27017";
            $this->db = new Client($mongoUrl);
        }
        return $this->db;
    }

}