<?php

require_once 'carbon.php';

use Carbon\Carbon;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * busValidcode 的注释
 *
 * @作者 roy
 */
class busValidcode
{

    public function check()
    {
        $result = new OperResult();
        $session_id = Yii::app()->session->sessionId;

        $session_key = 'validcode_status' . $session_id;
        $last_valid_time = Yii::app()->session[$session_key];
        $validcode_ts = Yii::app()->params['validcode_ts'];
        $now = new Carbon();
        if (isset($last_valid_time))
        {
            $ts = $now->diffInSeconds($last_valid_time);
            if ($now > $last_valid_time && $ts <= $validcode_ts * 60)
            {
                //失败，未达到时间间隔
                $result->code = SUCCESS;
                $result->seconds = $validcode_ts * 60 - $ts;
                $result->message = '未到可发送间隔';
            }
            else
            {
                $result->code = 1;
            }
        }
        else
        {
            $result->code = 1;
        }

        return $result;
    }

    /*
     * 发送短信验证码
     */

    public function send_validcode($mobile)
    {
        $result = new OperResult();

        $session_id = Yii::app()->session->sessionId;
        $session_key = 'validcode_status' . $session_id;
        $last_valid_time = Yii::app()->session[$session_key];
        $validcode_ts = Yii::app()->params['validcode_ts'];

        if (isset($last_valid_time))
        {
            $ts = Carbon::now()->diffInSeconds($last_valid_time);
            if ($ts <= $validcode_ts * 60)
            {
                //失败，未达到时间间隔
                $result->code = RETURN__MESSAGE_SEND_NO_LONGGER;
                $result->seconds = $validcode_ts * 60 - $ts;
                $result->message = 'Sending too frequent!';

                return $result;
            }
        }

        $code = $this->random_number(Yii::app()->params['validcode_len']);
        $validcode_ts = Yii::app()->params['validcode_ts'];
        $msg = sprintf(
                Yii::app()->params['validcode_msg'], $code, $validcode_ts);

        try
        {
            $this->send_jxt($msg, $mobile);

            $result->code = RETURN_SUCCESS;
            $result->valid_code = $code;
            $result->seconds = $validcode_ts * 60;

            Yii::app()->session[$session_key] = Carbon::now();
            Yii::app()->session['validcode' . $mobile] = $code;
            Yii::log($code);
        }
        catch (Exception $ex)
        {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            $result->message = $ex->getMessage();
            $result->code = RETURN_ERROR;
        }
        return $result;
    }

    /**
     * 检查验证码是否正确
     * @param type $mobile
     * @param type $validcode
     * @return Bool
     */
    public function check_validcode($mobile, $validcode)
    {
        return Yii::app()->session['validcode' . $mobile] == $validcode;
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

    public function random_words($length)
    {
        $chars = 'ABDFETYPNK';
        $code = $this->random_number(4);
        $code2 = '';
        for ($idx = 0; $idx < strlen($code); $idx++)
        {
            $code2 .= $chars[$code[$idx]];
        }

        return $code2;
    }

    /**
     * 发送短信 亿美软通接口
     * @param type $message 短信内容
     * @param type $mobile 手机号
     */
    private function send_emay($message, $mobile)
    {
        $message = '【爱吆喝】' . $message . '，退订回复TD';
        try
        {
            $url = 'http://sdkhttp.eucp.b2m.cn/sdkproxy/sendsms.action?cdkey=3SDK-EMY-0130-JCWRQ&password=638511';
            $url .= '&phone=' . $mobile;
            $url .= '&message=' . urlencode($message);

            $data = $this->get($url);
            $obj = simplexml_load_string(trim($data));
            if ($obj->error != 0)
            {
                Yii::log('发送短信到' . $mobile . '失败.' . $data, CLogger::LEVEL_INFO);
            }
        }
        catch (Exception $e)
        {
            Yii::log('发送短信到' . $mobile . '失败：' . $ex->getMessage(), CLogger::LEVEL_ERROR);
        }
    }

    /**
     * 发送短信-吉信通接口
     * @param type $message 短信内容
     * @param type $mobile 手机号
     */
    private function send_jxt($message, $mobile)
    {
        $message = '【爱吆喝】' . $message . '，退订回复TD,jxt';
        try
        {
            $client = new SoapClient('http://service2.winic.org/Service.asmx?WSDL', array(
                'soap_version' => SOAP_1_1,
                'trace' => true,));
            $aryPara = array(
                'uid' => 'bjiyaohe',
                'pwd' => 'bj123456',
                'tos' => $mobile,
                'msg' => $message);


            $res = $client->SendMessages($aryPara);
            if ($res->SendMessagesResult > 0)
            {
                Yii::log('成功发送短信' . $message . '到' . $mobile);
            }
            else
            {
                Yii::log('发送短信' . $message . '到' . $mobile . '失败，错误码：' . $res->SendMessagesResult);
            }

            return true;
        }
        catch (Exception $ex)
        {
            Yii::log('发送短信到' . $mobile . '失败：' . $ex->getMessage(), CLogger::LEVEL_ERROR);
            return false;
        }
    }

    /**
     * 
     * @param string  $url
     */
    private function get($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回  
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
        return curl_exec($ch);
    }

}
