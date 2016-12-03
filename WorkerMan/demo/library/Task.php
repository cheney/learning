<?php
/**
 * 任务处理类
 * User: cheney(phpfun@126.com)
 * Date: 2016/11/1
 * Time: 16:48
 */

require_once './CurlTools.php';

class Task
{
    //执行任务
    public static function exec($json_task){

        $result['code'] = -1;
        $result['data'] = $json_task;

        if($json_task){
            $task_info = json_decode($json_task,true);
            //check task info
            if(!is_array($task_info) || empty($task_info['class_name']) || empty($task_info['method_name']) ){
                $result['msg'] = "task parse error!";
                return $result;
            }
            //check task executeable
            if(!is_callable(array($task_info['class_name'],$task_info['method_name']))){
                $result['msg'] = "call to undefined class or method !";
                return $result;
            }
            //call method
            $result['code']  = 200;
            $result['msg'] = "task exec success!"; 
            if(isset($task_info['method_type']) && $task_info['method_type'] == 'static'){
                $result['data'] = $task_info['class_name'] :: $task_info['method_name']($task_info['params']);
            }else{
                $obj_clss = new $task_info['class_name']();
                $result['data'] =  $obj_clss -> $task_info['method_name']($task_info['params']);
            }
            //callback
            if($task_info['callback_url']){
                //check retrun value validate
                if(is_string($result['data'])){
                    json_decode($result['data']);
                    if(json_last_error() == JSON_ERROR_NONE){
                        $callback_params = $result['data'];
                    }else{
                        $callback_params = json_encode($result['data']);
                    }
                }else{
                    $callback_params = json_encode($result['data']);
                }
                $curl = new CurlTools();
                $result['callback_data'] = $curl -> get($task_info['callback_url'],array('params'=>$callback_params));
            }
            return $result;
        }else{
            $result['code']  = 200;
            $result['msg'] ="task empty!"; 
            return $result;
        }
    }

}