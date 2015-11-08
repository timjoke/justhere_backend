<?php

/**
 * 发送短信类，支持长短信；
 * 示例：
 * 
 * 
 *  $sms = new Sms();
 *  $result = $sms->send('138000138000','短信内容');
 *  if($result)
 *      echo '发送成功';
 *  else
 *      echo '发送失败';
 */
class busSms
{

    /**
     * 发送短信
     * @param type $mobile 手机号
     * @param type $msg 发送内容
     * @param type $send_time 发送时间
     * @return boolean 是否成功
     */
    public function send($mobile, $msg, $send_time = '')
    {
        try
        {
            $sms_server = Yii::app()->params['sms_server_url'];
            $context = new ZMQContext();
            $sender = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
            $sender->connect($sms_server);
            $sms = new OperResult();
            $sms->mobile = $mobile;
            $sms->msg = $msg;
            //$sms->dealer_name = $dealer_name;
            //$sms->is_test = Yii::app()->params['local_test'];

            $sender->send(json_encode($sms));
            return true;
        }
        catch (Exception $ex)
        {
            Yii::log('调用短信接口失败：' . $ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($ex->getTrace());
            return false;
        }
    }

    /**
     * 将字符串按长度分割成数组
     * @param  string  $str 传入字符串
     * @param  integer $l   字符串长度
     * @return mixed      数组或false
     */
    private function str_to_array($str, $l = 0)
    {
        if ($l > 0)
        {
            $ret = array();
            $len = mb_strlen($str, "UTF-8");
            for ($i = 0; $i < $len; $i += $l)
            {
                $ret[] = mb_substr($str, $i, $l, "UTF-8");
            }
            return $ret;
        }
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * 生成随机数验证码
     * @param int $length 长度
     * @return string
     */
    public function random_number($length)
    {
        $output = '';
        for ($a = 0; $a < $length; $a++)
        {
            $output .= rand(0, 9);
        }
        return $output;
    }

    /**
     * 验证手机号码
     *
     * @param string $mobilePhone
     * @return boolean
     */
    public function is_mobile($mobilePhone)
    {
        if (preg_match("/^1[0-9]{10}$/", $mobilePhone))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}
