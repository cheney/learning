<?php
class GlobalFun
{
    //获取客户端IP
    public static function getClientIp()
    {
        $ClinetIP = '';
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ClinetIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ClinetIP = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $ClinetIP = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ClinetIP = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $ClinetIP = getenv('HTTP_CLIENT_IP');
            } else {
                $ClinetIP = getenv('REMOTE_ADDR');
            }
        }

        return $ClinetIP;
    }
    /**
    * 判断内网IP
    *
    * @param $ip
    *
    * @returns
    */
    public static function isPrivateIp() {
        $ip = self::getClientIp();
        //分割字符串
        $token = strtok($ip, '.');
        //组合数组
        while ($token !== false){
            $strIP[] = $token;
            $token = strtok(".");
        }
        //判断IP地址是否合法
        if(count($strIP)!=4){
            return false;
        }
        //判断是否为A类内网IP
        if($strIP[0] == '10'){
            if($strIP[1]>=0 && $strIP[1] <= 255){
                if($strIP[2]>=0 && $strIP[2] <= 255){
                    if($strIP[3]>=0 && $strIP[3] <= 255){
                        return true;
                    }
                }
            }
            return false;
        }
        //判断是否为B类内网IP
        if($strIP[0] == '172'){
            if($strIP[1] >= 16 && $strIP[1] <= 31){
                if($strIP[2]>=0 && $strIP[2] <= 255){
                    if($strIP[3]>=0 && $strIP[3] <= 255){
                        return true;
                    }
                }
            }
            return false;
        }
        //判断是否为C类内网IP
        if($strIP[0] == '192'){
            if($strIP[1] == '168'){
                if($strIP[2]>=0 && $strIP[2] <= 255){
                    if($strIP[3]>=0 && $strIP[3] <= 255){
                        return true;
                    }
                }
            }
            return false;
        }
        //判断是否为自身调用
        if($ip == '127.0.0.1' || $ip == '0.0.0.0'){
            return true;
        }
        //错误的IP地址
        return false;
    }

}
