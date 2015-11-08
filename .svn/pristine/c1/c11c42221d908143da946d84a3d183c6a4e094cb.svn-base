<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * busUlitity 的注释
 *
 * @作者 roy
 */
class busUlitity
{

    static function arrayToObject($e)
    {
        if (gettype($e) != 'array')
            return;
        foreach ($e as $k => $v)
        {
            if (gettype($v) == 'array' || getType($v) == 'object')
            {
                $e[$k] = (object) self::arrayToObject($v);
            }
        }
        return (object) $e;
    }

    static function objectToArray($e)
    {
        $e = (array) $e;
        foreach ($e as $k => $v)
        {
            if (gettype($v) == 'resource')
                return;
            if (gettype($v) == 'object' || gettype($v) == 'array')
                $e[$k] = (array) self::objectToArray($v);
        }
        return $e;
    }

    /**
     * 检测是否手机号
     * @param type $mobile
     * @return type
     */
    static function is_mobile($mobile)
    {
        return preg_match("/^[1][358]\d{9}$/", $mobile);
    }

    /**
     * 
     * @param string  $url
     */
    static function get($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回  
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
        return curl_exec($ch);
    }

    /**
     * 发送post请求
     * @param type $url
     * @param type $data
     * @return type
     */
    static function post($url, $data = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 上传图片
     * @param type $imgFile
     * @param int $pictype 图片类型 1 商家logo；2 菜品图片；
     * @param int $entity_id 实体id
     * @param int $biztype 具体业务内的类型 1 商家logo 或 菜品图片
     */
    static function UploadImg($imgFile, $pictype, $entity_id, $biztype)
    {
        if (isset($imgFile))
        {
            $filetype = $imgFile->type;
            $extensionName = $imgFile->extensionName;
            $pos = strpos($filetype, 'image');
            if ($pos === false)
            {
                //提示不是图片
                return '上传的文件不是图片';
            } elseif ($pos == 0)
            {
                //上传图片
                $pic = pic::model()->findByAttributes(array('entity_id' => $entity_id, 'pic_type' => $pictype));
                $filefullpach = '';
                if (!isset($pic))
                {//图片信息在数据库中不存在
                    //判断文件夹是否存在 图片文件夹格式 参数img_upload_dir+图片类型_id_具体业务内的类型
                    $dir_date = date("Ym");
                    $dir_full = Yii::app()->params['img_upload_dir'] . 'upload/' . $dir_date . '/';
                    if (!is_dir($dir_full))
                    {
                        mkdir($dir_full);
                    }
                    $newfilename = $pictype . '_' . $entity_id . '_' . $biztype . '.' . $extensionName;
                    $relpath = 'upload/' . $dir_date . '/' . $newfilename; //相对路径 用于图片保存数据库
                    $pic = new Pic;
                    $pic->entity_id = $entity_id;
                    $pic->pic_type = $pictype;
                    $pic->pic_url = $relpath;
                    $pic->save();
                    $filefullpach = $dir_full . $newfilename;
                } else
                {
                    $filefullpach = Yii::app()->params['img_upload_dir'] . $pic->pic_url;
                }

                if ($imgFile->saveAs($filefullpach) == FALSE)
                {
                    //提示保存文件错误
                    return '保存文件出错，请联系管理员';
                } else
                {//保存文件对象到数据库
                }
            }
        } else
        {
            return -3;
        }
        return '上传图片成功';
    }

    /**
     * 格式化金钱类型的字符串，保留两位小数
     * @param string $model 
     * @return string
     */
    static function formatMoney($money, $num = 2)
    {
        $s = floatval($money);
        $money = sprintf("%.2f", $s);
        return $money;
    }

    /**
     * 格式化日期类型字符串 将字符串格式化为 y-M-d h:m
     * @param string $date
     * @return string
     */
    static function formatDate($date)
    {
        return busUlitity::getDatestr($date, 0, 16);
    }

    /**
     * 格式化日期类型字符串 将字符串格式化仅有时间 格式为 h:m
     * @param string $date
     * @return string
     */
    static function formatOnlyTime($date)
    {
        return busUlitity::getDatestr($date, 11, 5);
    }

    /**
     * 格式化日期时间字符串
     * @param string $date 待格式化的原始字符串
     * @param int $index 起始索引
     * @param int $length 长度
     * @return string 
     */
    static function getDatestr($date, $index, $length)
    {
        if (isset($date) == FALSE)
        {
            return '';
        } elseif (strlen($date) == 19)
        {
            return substr($date, $index, $length);
        } else
        {
            return $date;
        }
    }

    /**
     * 隐藏字符串中的数字
     * @param type $model
     * @return type
     */
    static function hideNumber($model)
    {
        for ($i = 0; $i < 10; $i++)
        {
            $model = str_replace($i, '*', $model);
        }
        return $model;
    }

    static function array_insert($myarray, $value, $position = 0)
    {
        $fore = ($position == 0) ? array() : array_splice($myarray, 0, $position);
        $fore[] = $value;
        $ret = array_merge($fore, $myarray);
        return $ret;
    }

    /**
     * 列表控件如果没有数据需要显示的提示
     * @param type $data 列表控件的数据源
     */
    static function dataEmptyMessage($data)
    {
        if ($data->itemCount == 0)
        {
            echo '<div class="emptyArea">' . DATAEMPTYMESSAGE . '</div>';
        }
    }

    /**
     * 将两个json字符串连接到一起
     * [{ "firstName": "Brett" }] 和 [{ "secondName": "bill" }] 连接后为[{ "firstName": "Brett" },{ "secondName": "bill" }]
     * @param string $json_a
     * @param string $json_b
     * @return string
     */
    static function joinjson($json_a, $json_b)
    {
        if ($json_a == '[]' && $json_b == '[]')
        {
            return '[]';
        } elseif ($json_a == '[]')
        {
            return $json_b;
        } elseif ($json_b == '[]')
        {
            return $json_a;
        } else
        {
            $json_a = substr($json_a, 0, strlen($json_a) - 1);
            $json_b = substr($json_b, 1, strlen($json_b));
            return $json_a . ',' . $json_b;
        }
    }

    /**
     * 时间差 
     * @param string $beginDate_str
     * @param string $endDate_str
     * @return int 天数
     */
    static function DateSubDay($beginDate_str, $endDate_str)
    {
        $beginDate = strtotime($beginDate_str);
        $endDate = strtotime($endDate_str);
        $day = floor(($endDate - $beginDate) / 86400);
        return $day;
    }

    /**
     * 报表使用的x轴时间列表，如果为时间差大于一天显示日期格式，其他情况显示一天的小时格式。
     * @param type $beginDate 
     * @param type $endDate
     * @return type  json格式，不包含最外层的中括号
     */
    static function X_reportjsArray($beginDate, $endDate)
    {
        $list = array();

        $days_sub = busUlitity::DateSubDay($beginDate, $endDate);
        if ($days_sub > 0)
        {//日期格式
            $beginDate_time = strtotime($beginDate);

            for ($i = 0; $i < $days_sub + 1; $i++)
            {
                $time_temp = date('m-d', strtotime('+' . $i . ' day', $beginDate_time));
                array_push($list, '\'' . $time_temp . '\'');
            }
        } else
        {//小时格式
            for ($i = 0; $i < 24; $i++)
            {
                array_push($list, $i);
            }
        }
        $list = implode(',', $list);
        return $list;
    }

    private static $static_server_idx = 1;

    /**
     * 获得静态文件服务器地址，不带"http:",以/结尾，适用js、css；
     * @return string
     */
    public static function get_static_url()
    {
        $default_url = '//192.168.2.10:8002/';
        if (Yii::app()->params['local_test'] === FALSE)
        {
            if (self::$static_server_idx > 5)
            {
                self::$static_server_idx = 1;
            }

            $url = '//img' . self::$static_server_idx . '.dacaipu.cn/';

            self::$static_server_idx++;

            return $url;
        } else
        {
            return $default_url;
        }
    }

    /**
     * 获得静态文件服务器地址，不带"http:",以/结尾，适用img；
     * @return type
     */
    public static function get_http_static_url()
    {
        return 'http:' . busUlitity::get_static_url();
    }

    /**
     * 生成全球唯一标示符 guid
     * @return string guid字符串
     */
    public static function create_guid()
    {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = chr(123)// "{"
                . substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12)
                . chr(125); // "}"
        return $uuid;
    }

    /**
     *  ios推送消息
     * @param type $userFrom 来自用户
     * @param type $userTo 目标用户
     * @param type $message_type 消息类型
     * @param type $other 留信息：地址；加好友：验证信息；
     * @param type $data 用于ios端的推送信息；
     * @param type $msg_id 消息id；
     * @return boolean
     */
    public static function applePushByUserid($userFrom, $userTo, $message_type, $other, $data, $msg_id)
    {
        if($userFrom == $userTo)
        {
            //自己给自己的消息不做消息推送
            return;
        }
        $userFromInfo = Userinfo::model()->getUsersAndFriendNameByUserid($userFrom, $userTo);
        $userToInfo = Userinfo::model()->findByPk($userTo);
        $unreadCount = Usermsg::model()->count('userID=:userID AND status=:status', array(":userID" => $userTo, ":status" => MESSAGE_STATUS_UNREAD));
        Yii::log("cloud,userTo=".$userTo.",token=".$userToInfo->device_token);
        if(isset($userFromInfo, $userToInfo))
        {
            if($userToInfo->login_status != LOGIN_STATUS_OFFLINE)
            {
                return busUlitity::applePush($userToInfo->device_token, busUlitity::getMessageByType($userFromInfo, $message_type, $other, $data), $unreadCount);
            }
        }
        return false;
    }

    /**
     * ios推送消息
     * @param type $deviceToken 设备token
     * @param type $message 消息内容
     * @return boolean
     */
    public static function applePush($deviceToken, $message, $unreadCount)
    {
        if (!isset($deviceToken) || $deviceToken == "")
        {
            return false;
        }

        try
        {
            $apns_server = Yii::app()->params['apns_server_url'];
            //$apns_server = 'tcp://127.0.0.1:55559';
            $context = new ZMQContext();
            $sender = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
            $sender->connect($apns_server);
            $apns = new OperResult();
            $apns->device_token = $deviceToken;
            $apns->message = $message;
            $apns->unread_count = $unreadCount;

            $sender->send(json_encode($apns));
        } catch (Exception $ex)
        {
            Yii::log('调用APNS推送消息接口失败：' . $ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($ex->getTrace());
        }

//        // 这里是我们上面得到的deviceToken，直接复制过来（记得去掉空格）
////$deviceToken = "4c8a8269bd2554d6f8ee0e8d88a600117d1a103f11f3784cb946e49b1acc734c";//qyh
//        //$deviceToken = "20246d01665988639d336f8c02c935c2e0accf63a97c05ac8025db39abf942c9"; //zk
//// Put your private key's passphrase here:
//        $passphrase = 'iyaoheteam';
//
//// Put your alert message here:
//        //$message = '收到回一个！';
//
//        $ctx = stream_context_create();
//        stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
//        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
//
//// Open a connection to the APNS server
////这个为正是的发布地址
////$fp = stream_socket_client(“ssl://gateway.push.apple.com:2195“, $err, $errstr, 60, //STREAM_CLIENT_CONNECT, $ctx);
////这个是沙盒测试地址，发布到appstore后记得修改哦
//        $fp = stream_socket_client(
//                'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
//        if (!$fp)
//        {
//            //exit("Failed to connect: $err $errstr" . PHP_EOL);
//            Yii::log("Failed to connect: $err $errstr" . PHP_EOL, CLogger::LEVEL_ERROR);
//        }
//
//        //echo 'Connected to APNS' . PHP_EOL;
//// Create the payload body
//        $body['aps'] = array(
//            'alert' => $message,
//            'sound' => 'default'
//        );
//
//// Encode the payload as JSON
//        $payload = json_encode($body);
//
//// Build the binary notification
//        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
//
//// Send it to the server
//        $result = fwrite($fp, $msg, strlen($msg));
//
//        // Close the connection to the server
//        fclose($fp);
//
//        if (!$result)
//        {
//            return false;
//            //echo 'Message not delivered' . PHP_EOL;
//        }
//        else
//        {
//            return true;
//            //echo 'Message successfully delivered' . PHP_EOL;
//        }
    }

    /**
     * ios推送消息
     * @param type $message2user 目标用户
     * @param type $msgType 消息类型
     * @param type $other 留信息：地址；加好友：验证信息；
     * @return type
     */
    private function getMessageByType($message2user, $msgType, $other, $data)
    {
        $template_str = "";
        $displayName = "";
        if (isset($message2user->friendName) && $message2user->friendName != "")
            $displayName = $message2user->friendName;
        else if (isset($message2user->nickName) && $message2user->nickName != "")
            $displayName = $message2user->nickName;
        else
            $displayName = $message2user->userName;

        switch ($msgType)
        {
            case "1":
                $template_str = $displayName . " 在 " . $other . " 给你留了一个消息";
                break;
            case "2":
                $template_str = $displayName . " 评论了你的消息(" . $data . ")";
                break;
            case "3":
                $template_str = $displayName . " 请求加你为好友";
                break;
            case "4":
                $template_str = $displayName . " 赞了你的消息(" . $data . ")";
                break;
            case "5":
                $template_str = $displayName . " 在 " . $other . " 发现了你的消息(" . $data . ")";
                break;
            case "6":
                $template_str = $displayName . " 接受了你的加好友请求";
                break;
            case "7":
                $template_str = $displayName . " 拒绝了你的加好友请求";
                break;
            case "8":
                $template_str = $displayName . " 回复了你的评论(" . $data . ")";
                break;
            default:
                $template_str = $displayName . " 给你留了一个消息";
                break;
        }
        return $template_str;
    }

    public static function hex2bin($str_hex)
    {
        try
        {
            $len = strlen($str_hex);
            if ($len % 2)
            {
                
            }
            else
            {
                $str_hex = hex2bin($str_hex);
            }
        } catch (Exception $e)
        {
            Yii::log($e->getMessage());
        }
        return $str_hex;
    }

}
