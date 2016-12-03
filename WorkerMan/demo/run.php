<?php
require_once '../Workerman/Autoloader.php';
require_once './library/CurlTools.php';
require_once './library/PhpRedis.php';

use Workerman\Worker;
use Workerman\Lib\Timer;

class Request{
    
    public static $http;
    public static $taks_queue_key = "task_list";
    public static $adder = 1;

    public function initHttp(){
        if (self::$http === null) {
            self::$http = new CurlTools();
            echo "init http class!\n";
        }
        return self::$http;
    }

    public function doit($redis){
        $json_task = $redis->Lists()->rpop(self::$taks_queue_key);
        echo self::$adder;
        if(is_string($json_task)){
            $task_info = json_decode($json_task,true);
            if(isset($task_info['process_url']) && !empty($task_info['process_url']) ){
                $http = self::initHttp();
                $result = $http -> get($task_info['process_url'],array('params'=>$json_task));
                if(!empty(trim($result))){
                    echo " task execute success !\n";
                    file_put_contents("/tmp/workmantest.log",self::$adder."\t".$result."\n",FILE_APPEND);
                }else{
                    echo " response failed ! \n";
                }
            }else{
                echo " task info parse error!";
            }
        }else{
            echo " task queue is empty!\n";
        }
        self::$adder++;
    }
}

$task = new Worker();
$task -> count = 4;
$task -> req = new Request();
$task -> req -> initHttp();
$task -> redis = PhpRedis::getInstance();
$task -> redis -> init(array('host'=>'127.0.0.1','port'=>6801));
$task -> onWorkerStart = function($task)
{
    // 0.1 seconds
    $time_interval = 0.1;
    $timer_id = Timer::add($time_interval,array($task -> req,'doit'),array($task -> redis),true);
};

// run all workers
Worker::runAll();
