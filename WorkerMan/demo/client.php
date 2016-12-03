<?php
/**
 * User: cheney(phpfun@126.com)
 * Date: 2016/11/1
 * Time: 16:52
 * @description 
 * task queue key : task_list
 * task queue value : (json)
 *      $demo['process_url'] = "http://127.0.0.1/demo/client.php?action=doit"; //process url(must)
 *      $demo['task_source'] = "my_test";  // task source use add log
 *      $demo['class_name'] = "Test";  //full class name (namespace)
 *      $demo['method_name'] = "output"; // method name 
 *      $demo['method_type'] = "static"; // method static or null( null is public);
 *      $demo['params'] = "1";   // method param (only one)
 *      $demo['callback_url'] = "http://test.com/demo/client.php?action=callback"; // if have callback, after exec call url
 */
require_once './library/PhpRedis.php';
require_once './library/Task.php';
require_once './library/GlobalFun.php';

$action = $_REQUEST['action'];
$params = $_REQUEST['params'];

if($action == 'add' || empty($action)){

    //添加一个任务
    $Redis = PhpRedis::getInstance();
    $Redis->init(array('host'=>'127.0.0.1','port'=>'6801'));
    $demo['process_url'] = "http://127.0.0.1/demo/client.php?action=doit";
    $demo['class_name'] = "Test";
    $demo['method_name'] = "output";
    $demo['task_source'] = "my_test";
    $demo['method_type'] = ""; // or empty;
    $demo['params'] = "hello world";
    $demo['callback_url'] = "http://127.0.0.1/demo/client.php?action=callback";
    $set_val = json_encode($demo);
    echo $Redis->Lists()->push('task_list',$set_val)."<br>";

}elseif($action == 'doit'){

    //执行任务(只允许内部IP调用)
    $is_internal = GlobalFun::isPrivateIp();
    if(!$is_internal){
        echo 0;
    }else{
        echo json_encode(Task::exec($params));
    }

}elseif($action == 'callback'){

    echo "the callback params is :".$params;

}

class Test{
    public function output($string){
        echo $string;
    }
}
