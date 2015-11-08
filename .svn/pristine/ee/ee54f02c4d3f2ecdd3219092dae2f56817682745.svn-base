<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of busApi
 *
 * @author jimmy
 */
class busApi
{

    /**
     * 获取短信验证码
     */
    public function getMessageCode()
    {
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
        Yii::log('调用方法：getMessageCode,请求参数[access_token]=' . $access_token);

        //验证并获取access_token
        $user = $this->get_access_token($access_token);

        if (!isset($user->telphone) || $user->telphone == "")
        {
            return $this->init_result(RETURN_TELPHONE_NOT_EXSIT, "用户未绑定手机号");
        }

        $busSms = new busSms();
        if (!$busSms->is_mobile($user->telphone))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params tel_no is available!");
        }

        $code = $busSms->random_number(Yii::app()->params['validcode_len']);
        $msg = sprintf('[%s]短信验证码，%s分钟内有效！', $code, Yii::app()->params['validcode_ts']);
        $send_result = $busSms->send($user->telphone, $msg);
        if (!$send_result)
        {
            return $this->init_result(RETURN_ERROR, "message send failed!");
        }

        $data = array();
        $data['code'] = $code;
        $data["time"] = Yii::app()->params['validcode_ts'];
        return $this->init_result(RETURN_SUCCESS, 'success', $data);
    }

    /**
     * 通过短信验证码注册用户
     */
    public function getValidCode()
    {
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
        $is_telno_change = isset($_POST['is_telno_change']) ? $_POST['is_telno_change'] : null;
        $tel_no = isset($_POST['telno']) ? $_POST['telno'] : null;
        Yii::log('调用方法：getValidCode,请求参数[access_token]=' . $access_token . ',[tel_no]=' . $tel_no . ',[is_telno_change]' . $is_telno_change);
        //验证参数
        if (!isset($tel_no))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params tel_no is empty!");
        }

        $busSms = new busSms();
        if (!$busSms->is_mobile($tel_no))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params tel_no is available!");
        }
        $exit_user = Userinfo::model()->findByAttributes(array('telphone' => $tel_no));

        if (isset($exit_user))
        {
            return $this->init_result(RETURN_USER_PHONE_EXIST, '用户手机号已存在');
        }

        $code = $busSms->random_number(Yii::app()->params['validcode_len']);
        $msg = sprintf(Yii::app()->params['validcode_msg'], $code, Yii::app()->params['validcode_ts']);
        $send_result = $busSms->send($tel_no, $msg);
        if (!$send_result)
        {
            return $this->init_result(RETURN_ERROR, "message send failed!");
        }

        $data = array();
        $data['code'] = $code;

        //不存在此手机号码的用户
//        try
//        {
//            $trans = Yii::app()->db->beginTransaction();
//
//            $userinfo = new Userinfo();
//            $userinfo->userName = $tel_no;
//            $userinfo->nickName = $tel_no;
//            $userinfo->telphone = $tel_no;
//            $userinfo->password = md5($code);
//            $userinfo->createtime = date("Y-m-d H:i:s");
//            $userinfo->login_type = LOGIN_TYPE_YAOHE;
//            $userinfo->login_status = LOGIN_STATUS_ONLINE_FORE;
//            $userinfo->save(false);
//            $user_id = $userinfo->primaryKey;
//
////新建好友关系，默认加恰好为好友
//            $friend = new Friends();
//            $friend->userFrom = $user_id;
//            $friend->friendName = "恰好";
//            $friend->userTo = 1;
//            $friend->status = FRIEND_STATUS_ACCEPT;
//            $friend->invoker = 1;
//            $friend->insert();
//
//            $trans->commit();
//        } catch (Exception $ex)
//        {
//            $trans->rollback();
//            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
//            return $this->init_result(RETURN_ERROR, $ex->getMessage());
//        }
        return $this->init_result(RETURN_SUCCESS, 'success', $data);
    }

    /**
     * 发布一个吆喝信息
     */
    public function locationTest()
    {
//获取参数
        $locations = isset($_POST['arr']) ? $_POST['arr'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：locationTestjimmytest,请求参数[arr]=' . $_POST['arr']);
//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params access_token is empty!");
        }
        if (!isset($locations))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params arr is empty!");
        }
        try
        {
            $location_array = json_decode($locations);
            foreach ($location_array as $value)
            {
                $locationLog = new LocationLog();
                $locationLog->userID = $access_token;
                $locationLog->longitude = $value->lng;
                $locationLog->latitude = $value->lat;
                $locationLog->createtime1 = $value->time;
                $locationLog->createtime = date("Y-m-d H:i:s");
                $locationLog->insert();
            }
        } catch (Exception $ex)
        {
            $this->init_result(RETURN_ERROR, $ex->getMessage());
        }
        return $this->init_result(RETURN_SUCCESS, 'success');
    }

    /**
     * 发布一个吆喝信息
     */
    public function locationGet()
    {
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
        $b_time = isset($_POST['b_time']) ? $_POST['b_time'] : null;
        $e_time = isset($_POST['e_time']) ? $_POST['e_time'] : null;
        $lastid = isset($_POST['lastid']) ? $_POST['lastid'] : null;
//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params access_token is empty!");
        }
        $sql = 'SELECT * 
                FROM t_locationlog 
                WHERE locID>:id and userID=:userid';

        if (isset($b_time))
        {
            $sql = $sql . ' and createtime>"' . $b_time . '"';
        }
        if (isset($e_time))
        {
            $sql = $sql . ' and createtime<"' . $e_time . '"';
        }
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':id', $lastid);
        $cmd->bindParam(':userid', $access_token);
        $reader = $cmd->query();
        $data = $reader->readAll();
        return $this->init_result(RETURN_SUCCESS, 'success', $data);
    }

    /**
     * 上传头像
     * @return type array
     */
    public function uploadHeadImage()
    {
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：uploadHeadImage,请求参数[$access_token]=' . $access_token, CLogger::LEVEL_INFO);

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params access_token is empty!");
        }
        $uploadedfile = "file";
        if (!isset($_FILES[$uploadedfile]))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params is empty!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

//文件大小必须小于 1MB
        if ($_FILES[$uploadedfile]["size"] > 3 * 1024 * 1024)
        {
            return $this->init_result(RETURN_UPFILE_SIZE_OVER, "file size more than 1MB!");
        }

//文件类型为 gif，jpeg，pjpeg
        if (!($_FILES[$uploadedfile]["type"] == "image/gif" || $_FILES[$uploadedfile]["type"] == "image/jpeg" || $_FILES[$uploadedfile]["type"] == "image/png"))
        {
            return $this->init_result(RETURN_UPFILE_TYPE_ERROR, "file type is not gif,jpeg,png!");
        }

//文件错误
        if ($_FILES[$uploadedfile]["error"] > 0)
        {
            return $this->init_result(RETURN_UPFILE_ERROR, "Error: " . $_FILES[$uploadedfile]["error"]);
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $target_path = "/upload/" . $user->userID . "/"; //接收文件目录  
        $file_ext = substr($_FILES[$uploadedfile]["type"], 6);
        $filename = 'pic_' . time() . '.' . $file_ext;
        $target_path = $target_path . $filename; //$_FILES[$uploadedfile]["type"];

        if (!file_exists(Yii::getPathOfAlias('webroot') . "/upload"))
        {
            mkdir(Yii::getPathOfAlias('webroot') . "/upload", 0777);
        }

        if (!file_exists(Yii::getPathOfAlias('webroot') . "/upload/" . $user->userID))
        {
            mkdir(Yii::getPathOfAlias('webroot') . "/upload/" . $user->userID, 0777);
        }

        if (move_uploaded_file($_FILES[$uploadedfile]['tmp_name'], Yii::getPathOfAlias('webroot') . $target_path))
        {
            Userinfo::model()->updateByPk($user->userID, array('userHead' => $target_path));
//            $file = new Fileinfo();
//            $file->filename = $filename;
//            $file->filepath = $target_path;
//            $file->uploadtime = date("Y-m-d H:i:s");
//            $file->IsCover = 0;
//            $file->insert();
            $data = array();
            $data['image_url'] = $target_path;
//$data['file_id'] = $file->primaryKey;

            return $this->init_result(RETURN_SUCCESS, "success", $data, $user->userID);
        } else
        {
            return $this->init_result(RETURN_UPFILE_ERROR, "Error: " . $_FILES[$uploadedfile]["error"]);
        }
    }

    /**
     * 上传文件
     * @return type array
     */
    public function uploadFile()
    {
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：uploadFile,请求参数[$access_token]=' . $access_token, CLogger::LEVEL_INFO);

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params access_token is empty!");
        }
        $uploadedfile = "file";
        if (!isset($_FILES[$uploadedfile]))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params file is empty!");
        }

//文件大小必须小于 1MB
        if ($_FILES[$uploadedfile]["size"] > 3 * 1024 * 1024)
        {
            return $this->init_result(RETURN_UPFILE_SIZE_OVER, "file size more than 1MB!");
        }

//文件类型为 gif，jpeg，pjpeg
        if (!($_FILES[$uploadedfile]["type"] == "image/gif" || $_FILES[$uploadedfile]["type"] == "image/jpeg" || $_FILES[$uploadedfile]["type"] == "image/png"))
        {
            return $this->init_result(RETURN_UPFILE_TYPE_ERROR, "file type is not gif,jpeg,png!");
        }

//文件错误
        if ($_FILES[$uploadedfile]["error"] > 0)
        {
            return $this->init_result(RETURN_UPFILE_ERROR, "Error: " . $_FILES[$uploadedfile]["error"]);
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $target_path = "/upload/" . $user->userID . "/"; //接收文件目录  
        $file_ext = substr($_FILES[$uploadedfile]["type"], 6);
        $filename = 'pic_' . time() . '.' . $file_ext;
        $target_path = $target_path . $filename; //$_FILES[$uploadedfile]["type"];

        if (!file_exists(Yii::getPathOfAlias('webroot') . "/upload"))
        {
            mkdir(Yii::getPathOfAlias('webroot') . "/upload", 0777);
        }

        if (!file_exists(Yii::getPathOfAlias('webroot') . "/upload/" . $user->userID))
        {
            mkdir(Yii::getPathOfAlias('webroot') . "/upload/" . $user->userID, 0777);
        }

        if (move_uploaded_file($_FILES[$uploadedfile]['tmp_name'], Yii::getPathOfAlias('webroot') . $target_path))
        {
            $file = new Fileinfo();
            $file->filename = $filename;
            $file->filepath = $target_path;
            $file->uploadtime = date("Y-m-d H:i:s");
            $file->IsCover = 0;
            $file->insert();
            $data = array();
            $data['image_url'] = $target_path;
            $data['file_id'] = $file->primaryKey;

            return $this->init_result(RETURN_SUCCESS, "success", $data, $user->userID);
        } else
        {
            return $this->init_result(RETURN_UPFILE_ERROR, "Error: " . $_FILES[$uploadedfile]["error"]);
        }
    }

    /**
     * 初始化
     */
    public function init()
    {
//获取参数
        $os_type = isset($_POST['os_type']) ? $_POST['os_type'] : null;
        $version_id = isset($_POST['version_id']) ? $_POST['version_id'] : null;
        $version_mini = isset($_POST['version_mini_id']) ? $_POST['version_mini_id'] : null;

        Yii::log('调用方法：init，请求参数:[$os_type]=' . $os_type
                . ' [$versionId]=' . $version_id
                . ' [$versionMini]=' . $version_mini
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($os_type, $version_id, $version_mini))
        {
            $err_msg = "";
            if (!isset($os_type))
                $err_msg .= "os_type,";
            if (!isset($version_id))
                $err_msg .= "version_id,";
            if (!isset($version_mini))
                $err_msg .= "version_mini_id";

            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }
        if (!is_numeric($os_type) || !is_numeric($version_id))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, 'params invalid!');
        }

//查找app版本信息
        $app_version = AppVersionInfo::model()->findByAttributes(array("ostype" => $os_type));
        if (!isset($app_version))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, 'os_type not exist!');
        }

        $data = array();

//主版本号小于最新版本号，返回有更新
        if ($version_id < $app_version->baseNum)
        {
            $data['is_update'] = 1;
            return $this->init_result(RETURN_SUCCESS, 'success', $data);
        }

//主版本号等于最新版本号，返回有更新
        if ($version_id == $app_version->baseNum && $version_mini < $app_version->slave1Num)
        {
            $data['is_update'] = 1;
            return $this->init_result(RETURN_SUCCESS, 'success', $data);
        }

        $data['is_update'] = 0;
        return $this->init_result(RETURN_SUCCESS, 'success', $data, 0, M_INIT);
    }

    /**
     * 注册
     */
    public function register()
    {
//获取参数
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $tel = isset($_POST['tel']) ? $_POST['tel'] : "";
        $pwd = isset($_POST['pwd']) ? $_POST['pwd'] : null;
        $device_token = isset($_POST['device_token']) ? $_POST['device_token'] : "";
        Yii::log('调用方法：register，请求参数:'
                . '[$username]=' . $username
                . ',[$pwd]=' . $pwd
                . ',[$tel]=' . $tel
                . ',[$device_token]=' . $device_token, CLogger::LEVEL_INFO);

//验证用户名参数
        if (!isset($username))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params(username) is empty!");
        }

//验证密码
        if (!isset($pwd))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params(pwd) is empty!");
        }

//验证用户名长度
        if (strlen($username) > 50)
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params(username) is too long!");
        }

//验证用户名长度
        if (strlen($pwd) < 3 || strlen($pwd) > 20)
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params(pwd) is too long or too short!");
        }

        $user = Userinfo::model()->findByAttributes(array('userName' => $username));

        if (!isset($user))
        {

            try
            {
                $trans = Yii::app()->db->beginTransaction();

                $userinfo = new Userinfo();
                $userinfo->userName = $username;
                $userinfo->nickName = Userinfo::model()->getRandomNickname();
                $userinfo->telphone = $tel;
                $userinfo->password = md5($pwd);
                $userinfo->createtime = date("Y-m-d H:i:s");
                $userinfo->login_type = LOGIN_TYPE_YAOHE;
                $userinfo->login_status = LOGIN_STATUS_ONLINE_FORE;
                $userinfo->save(false);
                $user_id = $userinfo->primaryKey;

//新建好友关系，默认加恰好为好友
                $friend = new Friends();
                $friend->userFrom = $user_id;
                $friend->friendName = "恰好";
                $friend->userTo = 1;
                $friend->status = FRIEND_STATUS_ACCEPT;
                $friend->invoker = 1;
                $friend->insert();

//                $friend2 = new Friends();
//                $friend2->userFrom = 1;
//                $friend2->userTo = $user_id;
//                $friend2->friendName = $username;
//                $friend2->status = FRIEND_STATUS_ACCEPT;
//                $friend2->invoker = $user_id;
//                $friend2->insert();

                $trans->commit();
            } catch (Exception $ex)
            {
                $trans->rollback();
                Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
                return $this->init_result(RETURN_ERROR, $ex->getMessage());
            }



            $new_guid = busUlitity::create_guid();
            //Yii::app()->session[$new_guid] = $user;
            Yii::app()->cache->add($new_guid, $user);
            $data = array();
//$data["access_token"] = $new_guid;
            $data["access_token"] = $user_id;
            $data["user"] = Userinfo::model()->getUsersByUsernameAndPwd($userinfo->userName = $username, $userinfo->password);
            $data["user_id"] = $user_id;
            return $this->init_result(RETURN_SUCCESS, 'success', $data, $user_id);
        } else
        {
            return $this->init_result(RETURN_USER_NAME_EXIST, "userName has already exist!");
        }
    }

    /**
     * 绑定手机号
     */
    public function validPhone()
    {
//获取参数
        $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
        Yii::log('调用方法：validPhone，请求参数:[$phone]=' . $phone . ',[$access_token]' . $access_token, CLogger::LEVEL_INFO);

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params(access_token) is empty!");
        }

//验证参数
        if (!isset($phone))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params(phone) is empty!");
        }

        if (!busUlitity::is_mobile($phone))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params(phone) format error!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $exist_user = Userinfo::model()->findByAttributes(array('telphone' => $phone));
        if (isset($exist_user))
        {
            return $this->init_result(RETURN_USER_PHONE_EXIST, "phone has already exist!");
        }

//发送验证码
        $sms = new busValidcode();
        $result = $sms->send_validcode($phone);
        if ($result->code != RETURN_SUCCESS)
        {
            return $this->init_result($result->code, $result->message);
        }
        $user->telphone = $phone;
        if (!$user->update())
        {
            return $this->init_result(RETURN_ERROR, 'telphone update failed!');
        }
        $data = array();
        $data["user_id"] = $result->user_id;
        $data["valid_code"] = $result->valid_code;
        $data["valid_time"] = $result->seconds;
        return $this->init_result($result->code, 'success', $data, $user->userID, M_VALID_PHONE);
    }

    /**
     * 登陆
     */
    public function login()
    {
//获取参数
        $name = isset($_POST['name']) ? $_POST['name'] : null;
        $password = isset($_POST['pwd']) ? $_POST['pwd'] : null;
        $device_token = isset($_POST['device_token']) ? $_POST['device_token'] : null;

        Yii::log('调用方法：login，请求参数:[$name]=' . $name
                . ' [$password]=' . $password
                . ' [$device_token]=' . $device_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($name, $password))
        {
            $err_msg = "";
            if (!isset($name))
                $err_msg .= "name,";
            if (!isset($password))
                $err_msg .= "password";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }
//        $user = Userinfo::model()->findByAttributes(array('password' => $password), 'telphone=:telphone or email=:email or userName=:userName', array(
//            ':telphone' => $name,
//            ':email' => $name,
//            ':userName' => $name
//        ));
        $password = md5($password);
        Yii::log('password' . $password, CLogger::LEVEL_INFO);
        $user = Userinfo::model()->getUsersByUsernameAndPwd($name, $password);

        if (!isset($user))
        {
            return $this->init_result(RETURN_USER_PWD_WRONG, 'username or pwd wrong');
        }

//更新device_token,及登陆状态
        if (isset($device_token) && $device_token != $user->device_token)
        {
            Userinfo::model()->updateByPk($user->userID, array('device_token' => $device_token, 'login_status' => LOGIN_STATUS_ONLINE_FORE));
        } else
        {
            Userinfo::model()->updateByPk($user->userID, array('login_status' => LOGIN_STATUS_ONLINE_FORE));
        }

        $new_guid = busUlitity::create_guid();
        //Yii::app()->session[$new_guid] = $user;
        Yii::app()->cache->add($new_guid, $user);
        $data = array();
//$data["access_token"] = $new_guid;
        $data["access_token"] = $user->userID;
        $data["user"] = $user;
        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID, M_LOGIN);
    }

    /**
     * 登出
     */
    public function loginOut()
    {
//获取参数
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
        $os_type = isset($_POST['os_type']) ? $_POST['os_type'] : null;

        Yii::log('调用方法：loginOut，请求参数:'
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (access_token) is empty!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        if (isset($os_type) && $os_type == "web")
        {
//Userinfo::model()->updateByPk($user->userID, array('device_token' => "", 'login_status' => LOGIN_STATUS_OFFLINE));
        } else
        {
            Userinfo::model()->updateByPk($user->userID, array('device_token' => "", 'login_status' => LOGIN_STATUS_OFFLINE));
        }

//移除缓存token
//Yii::app()->cache->add($new_guid, $user);

        return $this->init_result(RETURN_SUCCESS, 'success');
    }

    /**
     * 改变用户登陆状态
     */
    public function changeLoginStatus()
    {
//获取参数
        $login_status = isset($_POST['login_status']) ? $_POST['login_status'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：changeLoginStatus，请求参数:'
                . ' [$login_status]=' . $login_status
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($login_status) || !is_numeric($login_status) ||
                !($login_status == LOGIN_STATUS_OFFLINE || $login_status == LOGIN_STATUS_ONLINE_FORE || $login_status == LOGIN_STATUS_ONLINE_BACK))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (login_status) is invalid!");
        }

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (access_token) is empty!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        Userinfo::model()->updateByPk($user->userID, array('login_status' => $login_status));

        return $this->init_result(RETURN_SUCCESS, 'success');
    }

    /**
     * 完善用户信息
     */
    public function finishUserInfo()
    {
//获取参数
        $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $sex = isset($_POST['sex']) ? $_POST['sex'] : null;
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $nickname = isset($_POST['nickname']) ? $_POST['nickname'] : null;
        //$pwd = isset($_POST['pwd']) ? $_POST['pwd'] : null;
        $image_url = isset($_POST['image_url']) ? $_POST['image_url'] : null;
        $os_type = isset($_POST['os_type']) ? $_POST['os_type'] : null;
        $modeldes = isset($_POST['modeldes']) ? $_POST['modeldes'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;



        Yii::log('调用方法：finishUserInfo，请求参数:'
                . ' [$phone]=' . $phone
                . ' [$email]=' . $email
                . ' [$sex]=' . $sex
                . ' [$username]=' . $username
                . ' [$nickname]=' . $nickname
                //. ' [$pwd]=' . $pwd
                . ' [$image_url]=' . $image_url
                . ' [$os_type]=' . $os_type
                . ' [$modeldes]=' . $modeldes
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($sex, $os_type, $access_token))
        {
            $err_msg = "";
            if (!isset($sex))
                $err_msg .= "sex,";
            if (!isset($os_type))
                $err_msg .= "os_type,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);
        $userid = $user->userID;


//        //查找用户
//        $user = Userinfo::model()->findByAttributes(array('userID' => $userid));
//        if (!isset($user))
//        {
//            return $this->init_result(RETURN_USER_NOT_EXIST, 'user not exist!');
//        }

        if (isset($phone) && $phone != $user->telphone)
        {
//检查修改的用户信息中 手机号 是否已被使用
            $exist_user = Userinfo::model()->findByAttributes(array(), 'telphone=:telphone and userID<>:userid', array(
                ':telphone' => $phone,
                ':userid' => $userid
            ));
            if (isset($exist_user))
            {
                return $this->init_result(RETURN_USER_PHONE_EXIST, 'user telphone already exist!');
            }
        }

        if (isset($email) && $email != $user->email)
        {
//检查修改的用户信息中 邮箱 是否已被使用
            $exist_user = Userinfo::model()->findByAttributes(array(), 'email=:email and userID<>:userid', array(
                ':email' => $email,
                ':userid' => $userid
            ));
            if (isset($exist_user))
            {
                return $this->init_result(RETURN_USER_EMAIL_EXIST, 'user email already exist!');
            }
        }

//        if (isset($username))
//        {
//            //检查修改的用户信息中 用户名 是否已被使用
//            $exist_user = Userinfo::model()->findByAttributes(array(), 'userName=:userName and userID<>:userid', array(
//                ':userName' => $username,
//                ':userid' => $userid
//            ));
//            if (isset($exist_user))
//            {
//                return $this->init_result(RETURN_USER_NAME_EXIST, 'user name already exist!');
//            }
//        }
//保存用户信息
        if (isset($phone) && $phone != $user->telphone)
            $user->telphone = $phone;
        if (isset($nickname) && $nickname != $user->nickName)
            $user->nickName = $nickname;
        if (isset($email) && $email != $user->email)
            $user->email = $email;
        if (isset($sex) && $sex != $user->sex)
            $user->sex = $sex;
//        if (isset($username))
//            $user->userName = $username;
//        if (isset($pwd) && $pwd != $user->password)
//            $user->password = md5($pwd);
        if (isset($os_type) && $os_type != $user->usertype)
            $user->usertype = $os_type;
        if (isset($modeldes) && $modeldes != $user->modeldes)
            $user->modeldes = $modeldes;
//        if (isset($image_url) && $image_url != $user->userHead)
//            $user->userHead = $image_url;
        $user->save(false);

        $data = array();
        //$data["password"] = $user->password;
        $data["telphone"] = $user->telphone;
        $data["email"] = $user->email;
        $data["sex"] = $user->sex;
        $data["userHead"] = $user->userHead;
        $data["nickName"] = $user->nickName;
        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID);
    }

    /**
     * 发布一个吆喝信息
     */
    public function publishYaohe()
    {
//获取参数
        $context = isset($_POST['context']) ? $_POST['context'] : null;
        $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;
        $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
        $address = isset($_POST['address']) ? $_POST['address'] : null;
        $radius = isset($_POST['radius']) ? $_POST['radius'] : null;
        $fileID = isset($_POST['file_id']) ? $_POST['file_id'] : null;
        $usersTo = isset($_POST['usersTo']) ? $_POST['usersTo'] : null;

        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：publishYaohe，请求参数:'
                . ' [$context]=' . $context
                . ' [$longitude]=' . $longitude
                . ' [$latitude]=' . $latitude
                . ' [$address]=' . $address
                . ' [$radius]=' . $radius
                . ' [$fileID]=' . $fileID
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

        //Yii::log("userTo=" . $usersTo);
        if (is_string($usersTo))
        {
            $usersTo = json_decode($usersTo);
        }


//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (access_token) is empty!");
        }

//验证吆喝内容不为空
        if (!isset($context))
        {
            return $this->init_result(RETURN_CONTEXT_EMPTY, "yaohe context is empty");
        }

//        //验证吆喝联系人不为空
//        if (!isset($yaoheInfo['contact_tel']) || $yaoheInfo['contact_tel'] == '')
//        {
//            return $this->init_result(RETURN_CONTACT_TEL_EMPTY, "yaohe contact_tel is empty");
//        }
//验证经度不为空
        if (!isset($longitude))
        {
            return $this->init_result(RETURN_LONGITUDE_EMPTY, "yaohe longitude is empty");
        }

//验证纬度不为空
        if (!isset($latitude))
        {
            return $this->init_result(RETURN_LATITUDE_EMPTY, "yaohe latitude is empty");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

//保存吆喝信息
        $yaohe = new Yaoheinfo();
        $yaohe->userID = $user->userID;
        $yaohe->context = bin2hex($context);
        $yaohe->publish_date = date("Y-m-d H:i:s");
        $yaohe->status = YAOHE_STATUS_ABLE;
        $yaohe->longitude = $longitude;
        $yaohe->latitude = $latitude;
        $yaohe->address = $address;
        $yaohe->radius = $radius;
        if (isset($fileID))
        {
            $yaohe->yaohe_type = YAOHE_TYPE_IMAGE;
            $yaohe->fileinfo_id = $fileID;
        } else
        {
            $yaohe->yaohe_type = YAOHE_TYPE_WORD;
        }

        $trans = Yii::app()->db->beginTransaction();
        try
        {
            $yaohe->insert();
            $yaohe_id = $yaohe->primaryKey;
            if (isset($usersTo) && count($usersTo) > 0 && is_array($usersTo))
            {
                $sub_yaohe_context = mb_strlen($context, 'utf-8') > 5 ? mb_substr($context, 0, 5, 'utf-8') . '...' : $context;
                foreach ($usersTo as $value)
                {
                    if ($value == 0)
                    {
                        break;
                    }
                    $yaoheToUser = new YaoheToUser();
                    $yaoheToUser->yaoheID = $yaohe_id;
                    $yaoheToUser->userTo = $value;
                    $yaoheToUser->replyStatus = REPLY_STATUS_NO;
                    $yaoheToUser->insert();

//保存用户消息
                    Usermsg::model()->addUserMsg($user->userID, $value, MESSAGE_TYPE_YAOHE, $yaohe_id, $address, $sub_yaohe_context, $yaohe_id);
                }
            }
//            else
//            {
//                $yaoheToUser = new YaoheToUser();
//                $yaoheToUser->yaoheID = $yaohe_id;
//                $yaoheToUser->userTo = 0;
//                $yaoheToUser->replyStatus = REPLY_STATUS_NO;
//                $yaoheToUser->insert();
//            }
            $trans->commit();
        } catch (Exception $ex)
        {
            $trans->rollback();
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            return $this->init_result(RETURN_ERROR, $ex->getMessage());
        }
        $data = array();
        $data["yaohe_id"] = $yaohe_id;
        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID);
    }

    /**
     * 对吆喝信息发表评论
     */
    public function commentYaohe()
    {
//获取参数
        $yaoheID = isset($_POST['yaoheID']) ? $_POST['yaoheID'] : null;
        $commentedID = isset($_POST['commentedID']) ? $_POST['commentedID'] : null;
        $content = isset($_POST['content']) ? $_POST['content'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：commentYaohe，请求参数:[$yaoheID]=' . $yaoheID
                . ' [$commentedID]=' . $commentedID
                . ' [$content]=' . $content
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($yaoheID, $content, $access_token))
        {
            $err_msg = "";
            if (!isset($yaoheID))
                $err_msg .= "yaoheID,";
            if (!isset($content))
                $err_msg .= "content,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

        $content = bin2hex($content);

//验证吆喝ID是数字，且不为空
        if ($yaoheID == '' || !is_numeric($yaoheID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params yaoheID invalid!");
        }

//验证被评论ID是数字，且不为空
        if (isset($commentedID) && !is_numeric($commentedID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params commentedID invalid!");
        }
//验证被评论的吆喝是否存在
        $comment_yaohe = Yaoheinfo::model()->findByPk($yaoheID);
        if (!isset($comment_yaohe))
        {
            return $this->init_result(RETURN_YAOHEID_NOT_EXSIT, "params yaoheid is not exsit!");
        }
        $context_bin = busUlitity::hex2bin($comment_yaohe->context);
        //Yii::log('context_bin='.$context_bin);
        $sub_yaohe_context = mb_strlen($context_bin, 'utf-8') > 5 ? mb_substr($context_bin, 0, 5, 'utf-8') . '...' : $context_bin;
        $message_user_to_id = $comment_yaohe->userID;
        $relate_id = $comment_yaohe->yaoheID;
//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $isFirst = YaoheToUser::model()->isFirstCommentByUserid($user->userID, $yaoheID);
        try
        {
            $trans = Yii::app()->db->beginTransaction();

//保存评论信息
            $comment = new UserComment();
            $comment->yaoheID = $yaoheID;
            $comment->userFromID = $user->userID;
            if (isset($commentedID) && $commentedID > 0)
            {
                $comment->commentedID = $commentedID;
                $commented_comment = UserComment::model()->findByPk($commentedID);
                $message_user_to_id = $commented_comment->userFromID;
                $relate_id = $commentedID;
                $content_bin = busUlitity::hex2bin($commented_comment->content);
                $sub_yaohe_context = mb_strlen($content_bin, 'utf-8') > 5 ? mb_substr($content_bin, 0, 5, 'utf-8') . '...' : $content_bin;
            } else
            {
                $comment->commentedID = 0;
            }
            $comment->content = $content;
            $comment->createtime = date('Y-m-d H:i:s');
            $comment->insert();
            $comment_new_id = $comment->primaryKey;

            $message_type = null;
            if ($isFirst)
            {
                $message_type = MESSAGE_TYPE_FINDYAOHE;
//保存未读消息
//$usermsg->content = $user->userName . '发现并评论了你的吆喝(' . $sub_yaohe_context . ')';
//更改该用户发现此吆喝的状态
                YaoheToUser::model()->updateAll(array("replyStatus" => REPLY_STATUS_YES), "yaoheID=:yaoheID AND userTo=:userTo", array(":yaoheID" => $yaoheID, ":userTo" => $user->userID));
            } else
            {
                if (isset($commentedID) && $commentedID > 0)
                {
                    $message_type = MESSAGE_TYPE_COMMENT_COMMENT;
                } else
                {
                    $message_type = MESSAGE_TYPE_COMMENT;
                }
//$usermsg->content = $user->userName . '评论了你的吆喝(' . $sub_yaohe_context . ')';
            }
//保存用户消息
            Usermsg::model()->addUserMsg($user->userID, $message_user_to_id, $message_type, $relate_id, $comment_yaohe->address, $sub_yaohe_context, $comment_yaohe->yaoheID);
            $trans->commit();
        } catch (Exception $ex)
        {
            $trans->rollback();
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            return $this->init_result(RETURN_ERROR, $ex->getMessage());
        }
        $data = array();
        $data['commentid'] = $comment_new_id;
        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID);
    }

    /**
     * 赞吆喝信息
     */
    public function goodYaohe()
    {
//获取参数
        $yaoheID = isset($_POST['yaoheid']) ? $_POST['yaoheid'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：goodYaohe，请求参数:[$yaoheid]=' . $yaoheID
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($yaoheID, $access_token))
        {
            $err_msg = "";
            if (!isset($yaoheID))
                $err_msg .= "yaoheid,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

//验证吆喝ID是数字，且不为空
        if ($yaoheID == '' || !is_numeric($yaoheID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params yaoheid invalid!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

//验证被赞的吆喝是否存在
        $good_yaohe = Yaoheinfo::model()->findByPk($yaoheID);
        if (!isset($good_yaohe))
        {
            return $this->init_result(RETURN_YAOHEID_NOT_EXSIT, "params yaoheid is not exsit!");
        }

        $exsit_good = Good::model()->find('yaoheID=:yaoheID AND userFrom=:userFrom and status=1', array(':yaoheID' => $yaoheID, ':userFrom' => $user->userID));
        if (isset($exsit_good))
        {
            return $this->init_result(RETURN_ALREADY_GOOD, "you have already done it!");
        }
        $context_bin = busUlitity::hex2bin($good_yaohe->context);
        $sub_yaohe_context = mb_strlen($context_bin, 'utf-8') > 5 ? mb_substr($context_bin, 0, 5, 'utf-8') . '...' : $context_bin;

        try
        {
            $trans = Yii::app()->db->beginTransaction();

            $exist_good = Good::model()->findByAttributes(array('yaoheID' => $yaoheID, 'userFrom' => $user->userID));
            $is_notice_user = false;
            if (!isset($exist_good))
            {
//保存赞信息
                $good = new Good();
                $good->yaoheID = $yaoheID;
                $good->userFrom = $user->userID;
                $good->status = GOOD_STATUS_AVAILABLE;
                $good->insert();
                $is_notice_user = true;
            } else
            {
                $exist_good->status = GOOD_STATUS_AVAILABLE;
                $exist_good->save(false);
            }

            if ($is_notice_user)
            {
                //保存用户消息
                Usermsg::model()->addUserMsg($user->userID, $good_yaohe->userID, MESSAGE_TYPE_GOOD, $yaoheID, "", $sub_yaohe_context, $yaoheID);
            }
            $trans->commit();
        } catch (Exception $ex)
        {
            $trans->rollback();
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            return $this->init_result(RETURN_ERROR, $ex->getMessage());
        }

        return $this->init_result(RETURN_SUCCESS, 'success', $user->userID);
    }

    /**
     * 删除吆喝信息
     */
    public function deleteYaohe()
    {
//获取参数
        $yaoheID = isset($_POST['yaoheid']) ? $_POST['yaoheid'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：deleteYaohe，请求参数:[$yaoheid]=' . $yaoheID
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($yaoheID, $access_token))
        {
            $err_msg = "";
            if (!isset($yaoheID))
                $err_msg .= "yaoheid,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

//验证吆喝ID是数字，且不为空
        if ($yaoheID == '' || !is_numeric($yaoheID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params yaoheid invalid!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

//验证被删的吆喝是否存在
        $good_yaohe = Yaoheinfo::model()->findByPk($yaoheID);
        if (!isset($good_yaohe))
        {
            return $this->init_result(RETURN_YAOHEID_NOT_EXSIT, "params yaoheid is not exsit!");
        }

//修改吆喝的状态为不可用
        $good_yaohe->status = YAOHE_STATUS_DISABLE;
        $good_yaohe->save();

        return $this->init_result(RETURN_SUCCESS, 'success', $user->userID);
    }

    /**
     * 取消赞吆喝信息
     */
    public function ungoodYaohe()
    {
//获取参数
        $yaoheID = isset($_POST['yaoheid']) ? $_POST['yaoheid'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：ungoodYaohe，请求参数:[$yaoheid]=' . $yaoheID
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($yaoheID, $access_token))
        {
            $err_msg = "";
            if (!isset($yaoheID))
                $err_msg .= "yaoheid,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

//验证吆喝ID是数字，且不为空
        if ($yaoheID == '' || !is_numeric($yaoheID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params yaoheid invalid!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $exist_good = Good::model()->findByAttributes(array('yaoheID' => $yaoheID, 'userFrom' => $user->userID));
        if (isset($exist_good))
        {
            $exist_good->status = GOOD_STATUS_UNAVAILABLE;
            $exist_good->save(false);
        }

        //Good::model()->deleteAll('yaoheID=:yaoheID and userFrom=:userFrom', array(':yaoheID' => $yaoheID, ':userFrom' => $user->userID));
        return $this->init_result(RETURN_SUCCESS, 'success', $user->userID, M_UNGOOD_YAOHE);
    }

    /**
     * 获取当前范围内的吆喝信息
     */
    public function getNearByYaoheInfo()
    {
//获取参数
        $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;
        $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
        $radius = isset($_POST['radius']) ? $_POST['radius'] : null;
        $ids_list = isset($_POST['ids_list']) ? $_POST['ids_list'] : null;
        $last_time = isset($_POST['last_time']) ? $_POST['last_time'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：getNearByYaoheInfo，请求参数:'
                . '[$longitude]=' . $longitude
                . ' [$latitude]=' . $latitude
                . ' [$radius]=' . $radius
                . ' [$ids_list]=' . (isset($ids_list) ? implode(',', $ids_list) : "")
                . ' [$last_time]=' . $last_time
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($radius, $last_time, $access_token))
        {
            $err_msg = "";
            if (!isset($longitude))
                $err_msg .= "longitude,";
            if (!isset($latitude))
                $err_msg .= "latitude,";
            if (!isset($radius))
                $err_msg .= "radius,";
            if (!isset($last_time))
                $err_msg .= "last_time,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

        //验证并获取access_token
        $user = $this->get_access_token($access_token);
        $data = array();
        if (isset($longitude, $latitude) && $longitude == "0" && $latitude == "0")
        {
            //保存用户位置信息
            $this->add_user_location_log($longitude, $latitude, $user->userID);
        }

        if ($radius % 2 == 0)
        {
            $last_time = "2014-01-01";
        }

        $data["all_radius"] = Yaoheinfo::model()->getNearByYaoheInfo($longitude, $latitude, $radius, $ids_list, $last_time, $user->userID);
        $data_user = Yaoheinfo::model()->getYaoheInfoByUserID($user->userID);
        $data_to_user = Yaoheinfo::model()->getYaoheInfoByFriend($user->userID);
        $data_system = Yaoheinfo::model()->getSystemYaoheInfo($user->userID);
        $data["all_toself"] = array_merge($data_user, $data_to_user, $data_system);

        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID, M_GET_NEARBY_YAOHEINFO);
    }

    /**
     * 获取附近的吆喝信息
     */
    public function getNearYaoheInfo()
    {
//获取参数
        $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;
        $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
        $radius = isset($_POST['radius']) ? $_POST['radius'] : null;
        $ids_list = isset($_POST['ids_list']) ? $_POST['ids_list'] : null;
        //$last_time = isset($_POST['last_time']) ? $_POST['last_time'] : null;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：getNearYaoheInfo，请求参数:'
                . '[$longitude]=' . $longitude
                . ' [$latitude]=' . $latitude
                . ' [$radius]=' . $radius
                //. ' [$ids_list]=' . (isset($ids_list) ? implode(',', $ids_list) : "")
                //. ' [$last_time]=' . $last_time
                . ' [$page]=' . $page
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($radius, $access_token))
        {
            $err_msg = "";
            if (!isset($longitude))
                $err_msg .= "longitude,";
            if (!isset($latitude))
                $err_msg .= "latitude,";
            if (!isset($radius))
                $err_msg .= "radius,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }
        if (is_string($ids_list) && trim($ids_list) != "")
        {
            $ids_list = json_decode($ids_list);
            if (!is_array($ids_list))
            {
                $ids_list = null;
            }
        } else if (!is_array($ids_list))
        {
            $ids_list = null;
        }

        //验证并获取access_token
        $user = $this->get_access_token($access_token);
        $data = array();

        //更新时间，始终获取系统当前时间
        $last_time = date('Y-m-d H:i:s');

        $data["all_radius"] = Yaoheinfo::model()->getNearYaoheInfo($longitude, $latitude, $radius, $ids_list, $last_time, $user->userID, $page);
        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID, M_GET_NEARBY_YAOHEINFO);
    }

    /**
     * 获取与我相关的吆喝信息
     */
    public function getSelfYaoheInfo()
    {
//获取参数
//        $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;
//        $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
//        $radius = isset($_POST['radius']) ? $_POST['radius'] : null;
        $ids_list = isset($_POST['ids_list']) ? $_POST['ids_list'] : null;
        $update_time = isset($_POST['update_time']) ? $_POST['update_time'] : date('Y-m-d H:i:s'); //上次刷新时间
        $last_time = isset($_POST['last_time']) ? $_POST['last_time'] : $update_time; //查询起始时间
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：getSelfYaoheInfo，请求参数:'
                . ' [$ids_list]=' . (isset($ids_list) ? implode(',', $ids_list) : "")
                . ' [$last_time]=' . $last_time
                . ' [$update_time]=' . $update_time
                . ' [$page]=' . $page
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($update_time, $last_time, $access_token))
        {
            $err_msg = "";
            if (!isset($update_time))
                $err_msg .= "update_time,";
            if (!isset($last_time))
                $err_msg .= "last_time,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

        if ($update_time == $last_time)
        {
            //如果上次获取时间和本次刷新时间相同，即认为是新用户第一次使用刷新
            //把上次获取时间设定在恰好最早的时间
            $last_time = '2014-01-01';
        }

        //验证并获取access_token
        $user = $this->get_access_token($access_token);
        $data = array();

        $data["all_toself"] = Yaoheinfo::model()->getSelfYaoheInfo($user->userID, $last_time, $update_time, $ids_list, $page);

        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID, M_GET_NEARBY_YAOHEINFO);
    }

    /**
     * 获取一个吆喝信息及其所有评论信息
     */
    public function getYaoheDetailsInfo()
    {
//获取参数
        $yaoheID = isset($_POST['yaoheID']) ? $_POST['yaoheID'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：getYaoheDetailsInfo，请求参数:'
                . '[$yaoheID]=' . $yaoheID
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($yaoheID, $access_token))
        {
            $err_msg = "";
            if (!isset($yaoheID))
                $err_msg .= "yaoheID,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

//验证吆喝ID是数字，且不为空
        if ($yaoheID == '' || !is_numeric($yaoheID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params yaoheID invalid!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $data = Yaoheinfo::model()->getYaoheInfoByID($yaoheID, $user->userID);
        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID);
    }

    /**
     * 获取某个用户发布的吆喝信息
     */
    public function getYaoheInfoByUser()
    {
//获取参数
        $userID = isset($_POST['userid']) ? $_POST['userid'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：getYaoheInfoByUser，请求参数:'
                . '[$userID]=' . $userID
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (access_token) is empty!");
        }

//验证用户ID是数字，且不为空
        if (!is_numeric($userID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params userid invalid!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $userID = isset($userID) ? $userID : $user->userID;
        $data = array();
        $data["all_radius"] = Yaoheinfo::model()->getYaoheInfoByUserID($userID);
        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID);
    }

    /**
     * 获取留给某个用户信息
     */
    public function getYaoheInfoByToUser()
    {
//获取参数
        $userID = isset($_POST['userid']) ? $_POST['userid'] : null;
        $find_status = isset($_POST['reply_status']) ? $_POST['reply_status'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：getYaoheInfoByToUser，请求参数:'
                . '[$userID]=' . $userID
                . '[$find_status]=' . $find_status
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (access_token) is empty!");
        }

//验证用户ID是数字，且不为空
        if (isset($userID) && !is_numeric($userID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params userid invalid!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);
        $data = array();
        $data["all_radius"] = Yaoheinfo::model()->getYaoheInfoByToUserID($user->userID, $find_status);
        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID);
    }

    /**
     * 获取当前用户未读的消息
     */
    public function getUnreadMsg()
    {
//获取参数
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：getUnreadMsg，请求参数:'
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (access_token) is empty!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $data = Usermsg::model()->getByuserid($user->userID, MESSAGE_STATUS_UNREAD);
        if (isset($data))
        {
            foreach ($data as &$item)
            {
                Usermsg::model()->updateByPk($item["msgID"], array("status"=>MESSAGE_STATUS_READ));
                $item["status"] = MESSAGE_STATUS_READ;
            }
        }
        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID);
    }

    /**
     * 获取指定用户的信息、好友数量、留下的消息数量、已发现消息数量和未发现消息数量
     */
    public function getUserFullInfo()
    {
//获取参数
        $userID = isset($_POST['userid']) ? $_POST['userid'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：getUserFullInfo，请求参数:'
                . '[$userID]=' . $userID
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($userID, $access_token))
        {
            $err_msg = "";
            if (!isset($userID))
                $err_msg .= "userID,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

//验证用户ID是数字，且不为空
        if (isset($userID) && !is_numeric($userID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params userid invalid!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $exist_friend_ship = Friends::model()->find('userFrom=:userFrom and userTo=:userTo', array(':userFrom' => $user->userID, ':userTo' => $userID));
        if (isset($exist_friend_ship))
        {
            $friend_status = $exist_friend_ship->status;
        } else
        {
            $friend_status = 0;
        }

        $data = array();
        $data["userinfo"] = Userinfo::model()->getUsersByUserid($userID, $user->userID);
        $data["friend_count"] = Friends::model()->count("userFrom=:userFrom and status=2", array(":userFrom" => $userID));
        $data["yaohe_count"] = Yaoheinfo::model()->count("userID=:userID and status=1", array(":userID" => $userID));
        $data["yaohe_found_count"] = YaoheToUser::model()->count("userTo=:userTo and replyStatus=2", array(":userTo" => $userID));
        $data["yaohe_unfound_count"] = YaoheToUser::model()->count("userTo=:userTo and replyStatus=1", array(":userTo" => $userID));
        $data["friend_status"] = $friend_status;

        return $this->init_result(RETURN_SUCCESS, 'success', $data);
    }

    /**
     * 获取当前用户未读的消息数
     */
    public function getUnreadCount()
    {
//获取参数
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：getUnreadCount，请求参数:'
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (access_token) is empty!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $data = array();
//        $count_friend = Usermsg::model()->count('userID=:userID AND status=:status AND type=:type', array(":userID" => $user->userID, ":status" => MESSAGE_STATUS_UNREAD, ":type" => MESSAGE_TYPE_FRIEND));
//        $count_yaohe = Usermsg::model()->count('userID=:userID AND status=:status AND type=:type', array(":userID" => $user->userID, ":status" => MESSAGE_STATUS_UNREAD, ":type" => MESSAGE_TYPE_YAOHE));
//        $count_comment = Usermsg::model()->count('userID=:userID AND status=:status AND type=:type', array(":userID" => $user->userID, ":status" => MESSAGE_STATUS_UNREAD, ":type" => MESSAGE_TYPE_COMMENT));
//        $data["count_yaohe"] = $count_yaohe + $count_comment + $count_friend;
//        $data["count_friend"] = $count_friend;
        $data["count_yaohe"] = Usermsg::model()->count('userID=:userID AND status=:status', array(":userID" => $user->userID, ":status" => MESSAGE_STATUS_UNREAD));
        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID);
    }

    /**
     * 获取用户好友列表
     */
    public function getAllFriends()
    {
//获取参数
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：getAllFriends，请求参数:'
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (access_token) is empty!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $data = Userinfo::model()->getUserFriends($user->userID);
        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID);
    }

    /**
     * 获取用户好友的好友列表
     */
    public function getOtherFriends()
    {
//获取参数
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
        $userid = isset($_POST['userid']) ? $_POST['userid'] : null;

        Yii::log('调用方法：getOtherFriends，请求参数:'
                . ' [$access_token]=' . $access_token
                . ' [$userid]=' . $userid
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (access_token) is empty!");
        }

        //验证参数
        if (!isset($userid))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (userid) is empty!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $data = Userinfo::model()->getOtherFriends($userid, $user->userID);
        return $this->init_result(RETURN_SUCCESS, 'success', $data);
    }

    /**
     * 查询用户
     */
    public function getFriendByName()
    {
//获取参数
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：getFriendByName，请求参数:'
                . ' [$username]=' . $username
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($username))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (username) is empty!");
        }

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (access_token) is empty!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $data = Userinfo::model()->getUsersByUsername($username, $user->userID);
        if (!isset($data))
        {
            return $this->init_result(RETURN_USER_NOT_EXIST, 'user is not exsit!');
        }
        return $this->init_result(RETURN_SUCCESS, 'success', $data, $user->userID);
    }

    /**
     * 添加好友请求
     */
    public function addFriendsRequest()
    {
//获取参数
        $userID = isset($_POST['userid']) ? $_POST['userid'] : null;
        $validmsg = isset($_POST['validmsg']) ? $_POST['validmsg'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：addFriendsRequest，请求参数:'
                . '[$userID]=' . $userID
                . '[$validmsg]=' . $validmsg
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($userID, $access_token))
        {
            $err_msg = "";
            if (!isset($userID))
                $err_msg .= "userID,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

//验证用户ID是数字，且不为空
        if (isset($userID) && !is_numeric($userID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params userid invalid!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

//不能添加自己为好友
        if ($user->userID == $userID)
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "Can't add self to you friend!");
        }

        $exist_friend = Friends::model()->findByAttributes(array(), 'userFrom=:userFrom and userTo=:userTo', array(
            'userFrom' => $user->userID,
            'userTo' => $userID
        ));
        if (isset($exist_friend))
        {
            if ($exist_friend->status == FRIEND_STATUS_REQUEST)
            {
                //已经发出加好友申请
                return $this->init_result(RETURN_ALREADYFRIEND, "your already send request!");
            } else if ($exist_friend->status == FRIEND_STATUS_ACCEPT)
            {
                //已经是好友，不能在加好友了
                return $this->init_result(RETURN_ALREADYFRIEND, "yours are already friends!");
            } else if ($exist_friend->status == FRIEND_STATUS_DENY)
            {
                //拒绝过的好友，还可以再添加
                Friends::model()->updateAll(array("status" => FRIEND_STATUS_REQUEST, "invoker" => $user->userID), "(userFrom=:userFrom and userTo=:userTo) or (userFrom=:userTo and userTo=:userFrom)", array(":userFrom" => $user->userID, 'userTo' => $userID));
            }
        } else
        {
            try
            {
                $trans = Yii::app()->db->beginTransaction();

//新建好友关系，好友是相对的，所以要插入2个关系
                $friend = new Friends();
                $friend->userFrom = $user->userID;
                $userTo = Userinfo::model()->findByPk($userID);
                if (isset($userTo))
                {
                    if ($userTo->nickName != null && $userTo->nickName != "")
                    {
                        $friend->friendName = $userTo->nickName;
                    } else
                    {
                        $friend->friendName = $userTo->userName;
                    }
                }
                $friend->userTo = $userID;
                $friend->status = FRIEND_STATUS_REQUEST;
                $friend->invoker = $user->userID;
                $friend->insert();

                $friend2 = new Friends();
                $friend2->userFrom = $userID;
                $friend2->userTo = $user->userID;
                $friend2->friendName = ($user->nickName == "" || $user->nickName == null ? $user->userName : $user->nickName);
                $friend2->status = FRIEND_STATUS_REQUEST;
                $friend2->invoker = $user->userID;
                $friend2->insert();

//保存用户消息
                Usermsg::model()->addUserMsg($user->userID, $userID, MESSAGE_TYPE_FRIEND, $friend2->primaryKey, $validmsg);
                $trans->commit();
            } catch (Exception $ex)
            {
                $trans->rollback();
                Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
                return $this->init_result(RETURN_ERROR, $ex->getMessage());
            }
        }
        return $this->init_result(RETURN_SUCCESS, 'success', $user->userID);
    }

    /**
     * 修改好友备注
     */
    public function updateFriendName()
    {
//获取参数
        $userID = isset($_POST['userid']) ? $_POST['userid'] : null;
        $friendName = isset($_POST['friendName']) ? $_POST['friendName'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：addFriendsRequest，请求参数:'
                . '[$userID]=' . $userID
                . '[$friendName]=' . $friendName
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($userID, $friendName, $access_token))
        {
            $err_msg = "";
            if (!isset($userID))
                $err_msg .= "userID,";
            if (!isset($friendName))
                $err_msg .= "friendName,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

//验证用户ID是数字，且不为空
        if (isset($userID) && !is_numeric($userID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params userid invalid!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

//保存好友备注
        $friend = Friends::model()->findByAttributes(array('userFrom' => $user->userID, 'userTo' => $userID));
        if (!isset($friend))
        {
            return $this->init_result(RETURN_ERROR, 'friend not exist!');
        }
        $friend->friendName = $friendName;
        $friend->save(false);
        return $this->init_result(RETURN_SUCCESS, 'success', $user->userID);
    }

    /**
     * 删除好友
     */
    public function delFriends()
    {
//获取参数
        $userID = isset($_POST['userid']) ? $_POST['userid'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：delFriends，请求参数:'
                . '[$userID]=' . $userID
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($userID, $access_token))
        {
            $err_msg = "";
            if (!isset($userID))
                $err_msg .= "userID,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

//验证用户ID是数字，且不为空
        if (isset($userID) && !is_numeric($userID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params userid invalid!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

//删除好友关系
        Friends::model()->deleteAll('(userFrom=:userFrom and userTo=:userTo) or (userFrom=:userTo and userTo=:userFrom)', array(
            ':userFrom' => $user->userID,
            ':userTo' => $userID
        ));

        return $this->init_result(RETURN_SUCCESS, 'success', $user->userID);
    }

    /**
     * 已读消息
     */
    public function readMsg()
    {
//获取参数
        $msgid = isset($_POST['msgid']) ? $_POST['msgid'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：readMsg，请求参数:'
                . '[$msgid]=' . $msgid
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($msgid, $access_token))
        {
            $err_msg = "";
            if (!isset($msgid))
                $err_msg .= "msgid,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

//验证消息ID是数字
        if (!is_numeric($msgid))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params msgid invalid!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $usermsg = Usermsg::model()->findByPk($msgid);
        if (!isset($usermsg))
        {
            return $this->init_result(RETURN_USER_MSG_NOTFOUND, "user message not found!");
        }
        if ($usermsg->status != MESSAGE_STATUS_UNREAD)
        {
            return $this->init_result(RETURN_USER_MSG_HANDLED, "user message has handled!");
        }
        $usermsg->status = MESSAGE_STATUS_READ;
        $usermsg->save(false);

        return $this->init_result(RETURN_SUCCESS, 'success', $user->userID);
    }

    /**
     * 接受或拒绝加好友请求
     */
    public function confirmOrDenyFriendRequest()
    {
//获取参数
        $userID = isset($_POST['userid']) ? $_POST['userid'] : null;
        $way = isset($_POST['way']) ? $_POST['way'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：confirmOrDenyFriendRequest，请求参数:'
                . '[$userID]=' . $userID
                . '[$way]=' . $way
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($userID, $way, $access_token))
        {
            $err_msg = "";
            if (!isset($userID))
                $err_msg .= "userID,";
            if (!isset($way))
                $err_msg .= "way,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

//验证消息ID是数字
        if (!is_numeric($userID))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params userid invalid!");
        }

//验证消息ID是数字
        if (!($way == FRIEND_STATUS_ACCEPT || $way == FRIEND_STATUS_DENY))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params way invalid!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        try
        {
            $trans = Yii::app()->db->beginTransaction();

//修改好友关系状态
            Friends::model()->updateAll(array('status' => $way), '(userFrom=:userFrom and userTo=:userTo) or (userFrom=:userTo and userTo=:userFrom)', array(
                ':userFrom' => $user->userID,
                ':userTo' => $userID
            ));

            $message_type = null;
            if ($way == FRIEND_STATUS_ACCEPT)
            {
                $message_type = MESSAGE_TYPE_FRIEND_ACCEPT;
            } else if ($way == FRIEND_STATUS_DENY)
            {
                $message_type = MESSAGE_TYPE_FRIEND_DENY;
            }
//保存用户消息
            Usermsg::model()->addUserMsg($user->userID, $userID, $message_type);
            $trans->commit();
        } catch (Exception $ex)
        {
            $trans->rollback();
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            return $this->init_result(RETURN_ERROR, $ex->getMessage());
        }

        return $this->init_result(RETURN_SUCCESS, 'success', $user->userID);
    }

    /**
     * 问题反馈
     */
    public function addQuestion()
    {
//获取参数
        $desc = isset($_POST['desc']) ? $_POST['desc'] : null;
        $device_info = isset($_POST['device_info']) ? $_POST['device_info'] : null;
        $os_info = isset($_POST['os_info']) ? $_POST['os_info'] : null;
        $app_info = isset($_POST['app_info']) ? $_POST['app_info'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：finishUserInfo，请求参数:'
                . ' [$desc]=' . $desc
                . ' [$device_info]=' . $device_info
                . ' [$os_info]=' . $os_info
                . ' [$app_info]=' . $app_info
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($desc, $access_token))
        {
            $err_msg = "";
            if (!isset($desc))
                $err_msg .= "sex,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

//验证并获取access_token
        $user = $this->get_access_token($access_token);

        $new_question = new Question();
        $new_question->questionDesc = $desc;
        $new_question->userID = $user->userID;
        $new_question->deviceInfo = $device_info;
        $new_question->osInfo = $os_info;
        $new_question->appInfo = $app_info;
        $new_question->createtime = date("Y-m-d H:i:s");
        $new_question->insert();

        return $this->init_result(RETURN_SUCCESS, 'success');
    }

    /**
     * 问题反馈
     */
    public function getAllQuestion()
    {
//获取参数
        $start_time = isset($_POST['start_time']) ? $_POST['start_time'] : null;
        $data = Question::model()->getAll($start_time);
        return $this->init_result(RETURN_SUCCESS, 'success', $data);
    }

    /**
     * 获取手机联系人信息
     */
    public function getAllContact()
    {
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：getAllContact，请求参数:'
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($access_token))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params (access_token) is empty!");
        }
        //验证并获取access_token
        $user = $this->get_access_token($access_token);
        $data = array();
        $data['contact'] = Telcontact::model()->getContactByUserid($user->userID);
        return $this->init_result(RETURN_SUCCESS, 'success', $data);
    }

    /**
     * 保存手机联系人信息
     */
    public function saveContact()
    {
        $contact = isset($_POST['contact']) ? $_POST['contact'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：saveContact，请求参数:'
                . ' [$contact]=' . $contact
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($contact, $access_token))
        {
            $err_msg = "";
            if (!isset($contact))
                $err_msg .= "contact,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

        //验证并获取access_token
        $user = $this->get_access_token($access_token);

        //验证json 和 数组
        $contact_array = json_decode($contact);
        if (!isset($contact_array) || !is_array($contact_array))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "contact array is not valid!");
        }

        try
        {
            $trans = Yii::app()->db->beginTransaction();
            foreach ($contact_array as $value)
            {
                $new_contact = new Telcontact();
                $new_contact->user_contact_id = $user->userID;
                $new_contact->contact_name = $value->name;
                $new_contact->contact_tel = $value->tel;
                $new_contact->contact_email = $value->email;
                $new_contact->contact_status = USER_CONTACT_STATUS_UNINVITED;
                $exist_user = Userinfo::model()->find('telphone=:telphone', array(':telphone' => $value->tel));
                if (isset($exit_user))
                {
                    $new_contact->contact_user_id = $exist_user->userID;
                } else
                {
                    $new_contact->contact_user_id = 0;
                }
                $new_contact->insert(false);
            }
            $trans->commit();
        } catch (Exception $ex)
        {
            $trans->rollback();
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            return $this->init_result(RETURN_ERROR, $ex->getMessage());
        }

        return $this->init_result(RETURN_SUCCESS, 'success');
    }

    /**
     * 保存密码
     */
    public function savePassword()
    {
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;

        Yii::log('调用方法：saveContact，请求参数:'
                . ' [$password]=' . $password
                . ' [$access_token]=' . $access_token
                , CLogger::LEVEL_INFO);

//验证参数
        if (!isset($password, $access_token))
        {
            $err_msg = "";
            if (!isset($password))
                $err_msg .= "password,";
            if (!isset($access_token))
                $err_msg .= "access_token";
            return $this->init_result(RETURN_PARAMS_ERROR, "params (" . $err_msg . ") is empty!");
        }

        //验证并获取access_token
        $user = $this->get_access_token($access_token);

        try
        {
            $trans = Yii::app()->db->beginTransaction();
            $user->password = md5($password);
            $user->save();
            $trans->commit();
        } catch (Exception $ex)
        {
            $trans->rollback();
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            return $this->init_result(RETURN_ERROR, $ex->getMessage());
        }

        return $this->init_result(RETURN_SUCCESS, 'success');
    }

    /**
     * 按init_result方式输出通信数据
     * @param type $code 状态码
     * @param type $message 提示信息
     * @param type $data 数据
     * @param type $userid 用户id
     * @param type $method 调用接口
     * @return $result
     */
    private function init_result($code, $message = '', $data = array(), $userid = -1, $method = 0)
    {
        $userid = (int) $userid;
        if ($userid > 0 && $method > 0)
        {
            try
            {
//保存接口的访问次数
                $exsit_apicount = Apicount::model()->findByAttributes(
                        array(
                            'userID' => $userid,
                            'method' => $method,
                            'createtime' => date("Y-m-d")
                ));
                if (isset($exsit_apicount))
                {
                    $exsit_apicount->todayCount = $exsit_apicount->todayCount + 1;
                    $exsit_apicount->save(false);
                } else
                {
                    $api_count = new Apicount();
                    $api_count->method = $method;
                    $api_count->userID = $userid;
                    $api_count->todayCount = 1;
                    $api_count->createtime = date("Y-m-d");
                    $api_count->insert();
                }
            } catch (Exception $ex)
            {
                Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
//return $this->init_result(RETURN_ERROR, $ex->getMessage());
            }
        }

//web版返回英文的返回信息,非web版返回中文的返回信息
        //if (isset($_SERVER["HTTP_USER_AGENT"]) && stripos($_SERVER["HTTP_USER_AGENT"], "iPhone"))
        //{
        $message = $this->get_message_by_code($code);
        //}

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );
        return $result;
//echo json_encode($result);
//exit;
    }

    /**
     * 获取access_token
     * @param type $token
     * @return $user 成功：返回$user对象；失败：输入消息，结束
     */
    private function get_access_token($token)
    {
//        $user = Yii::app()->cache->get($token);
//        if (isset($user) && isset($user->userID))
//        {
//            return $user;
//        }
//        else
//        {
//            echo json_encode($this->init_result(RETURN_ACCESS_TOKEN_OUT, "access_token out of date"));
//            exit();
//        }
//临时方案，token为userid
        //$user = Yii::app()->session[$token];
        $user = Yii::app()->cache->get($token);
        if (isset($user) && isset($user->userID))
        {
            return $user;
        } else
        {
//$user = Userinfo::model()->getUsersByUserid($token);
            $user = Userinfo::model()->findByPk($token);
            if (!isset($user))
            {
                echo json_encode($this->init_result(RETURN_USER_NOT_EXIST, "user is not exist!"));
                exit();
            }
            //Yii::app()->session[$token] = $user;
            Yii::app()->cache->add($token, $user);
            return $user;
        }
    }

    private function get_message_by_code($code)
    {
        $message = "";
        switch ($code)
        {
            case RETURN_SUCCESS:
                $message = "成功";
                break;
            case RETURN_ACCESS_TOKEN_OUT:
            case RETURN_PARAMS_ERROR:
            case RETURN_CONTEXT_EMPTY:
            case RETURN_CONTACT_TEL_EMPTY:
            case RETURN_LONGITUDE_EMPTY:
            case RETURN_LATITUDE_EMPTY:
            case RETURN_UPFILE_ERROR:
            case RETURN_YAOHEID_NOT_EXSIT:
            case RETURN_ERROR:
                $message = "失败";
                break;
            case RETURN_USER_PWD_WRONG:
                $message = "用户名或密码错误";
                break;
            case RETURN_USER_NOT_EXIST:
            case RETURN_USER_MSG_NOTFOUND:
                $message = "用户不存在";
                break;
            case RETURN_MESSAGE_SEND_NO_LONGGER:
                $message = "操作失败，请稍后重试";
                break;
            case RETURN_USER_PHONE_EXIST:
                $message = "手机号已存在";
                break;
            case RETURN_USER_PHONE_NOT_EXIST:
                $message = "手机号不存在";
                break;
            case RETURN_USER_EMAIL_EXIST:
                $message = "邮件地址已存在";
                break;
            case RETURN_USER_NAME_EXIST:
                $message = "用户名已存在";
                break;
            case RETURN_USER_MSG_HANDLED:
                $message = "消息已经处理";
                break;
            case RETURN_UPFILE_SIZE_OVER:
                $message = "图片太大，最大为1MB";
                break;
            case RETURN_UPFILE_TYPE_ERROR:
                $message = "上传文件格式错误";
                break;
            case RETURN_ALREADYFRIEND:
                $message = "已经是好友了";
                break;
            case RETURN_ALREADY_GOOD:
                $message = "已经赞过了";
                break;
            default:
                $message = "未知错误";
                break;
        }
        return $message;
    }

    private function add_user_location_log($lng, $lat, $userID)
    {
        try
        {
            $locationLog = new LocationLog();
            $locationLog->userID = $userID;
            $locationLog->longitude = $lng;
            $locationLog->latitude = $lat;
            $locationLog->createtime = date("Y-m-d H:i:s");
            $locationLog->insert();
        } catch (Exception $ex)
        {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
        }
    }

    /**
     * 重置密码时，获取手机验证码
     */
    public function getVCodeInResetPWD()
    {
        //$access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
        $tel = isset($_POST['tel']) ? $_POST['tel'] : null;
        Yii::log('调用方法：getVCodeInResetPWD,请求参数,[tel]=' . $tel);
        //验证参数
        if (!isset($tel))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "请输入手机号码");
        }

        $busSms = new busSms();
        if (!$busSms->is_mobile($tel))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params tel_no is available!");
        }
        $exit_user = Userinfo::model()->findByAttributes(array('telphone' => $tel));

        if (!isset($exit_user))
        {
            return $this->init_result(RETURN_USER_PHONE_NOT_EXIST, '手机号不存在');
        }


        $code = $busSms->random_number(Yii::app()->params['validcode_len']);
        $msg = sprintf(Yii::app()->params['validcode_msg'], $code, Yii::app()->params['validcode_ts']);
        $send_result = $busSms->send($tel, $msg);
        if (!$send_result)
        {
            return $this->init_result(RETURN_ERROR, "短信发送失败!");
        }

        $exit_user->password = md5($code);
        $exit_user->save(false);

        $data = array();
        $data['code'] = $code;
        $data['access_token'] = $exit_user->userID;
        return $this->init_result(RETURN_SUCCESS, 'success', $data);
    }

    /**
     * 重置密码
     */
    public function resetPassword()
    {
        //$access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
        $tel_no = isset($_POST['telno']) ? $_POST['telno'] : null;
        Yii::log('调用方法：resetPassword,请求参数,[tel_no]=' . $tel_no);
        //验证参数
        if (!isset($tel_no))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params tel_no is empty!");
        }

        $busSms = new busSms();
        if (!$busSms->is_mobile($tel_no))
        {
            return $this->init_result(RETURN_PARAMS_ERROR, "params tel_no is available!");
        }
        $exit_user = Userinfo::model()->findByAttributes(array('telphone' => $tel_no));

        if (!isset($exit_user))
        {
            return $this->init_result(RETURN_USER_PHONE_NOT_EXIST, '手机号不存在');
        }


        $code = $busSms->random_number(Yii::app()->params['validcode_len']);
        $msg = sprintf(Yii::app()->params['validcode_msg'], $code, Yii::app()->params['validcode_ts']);
        $send_result = $busSms->send($tel_no, $msg);
        if (!$send_result)
        {
            return $this->init_result(RETURN_ERROR, "短信发送失败!");
        }

        $data = array();
        $data['code'] = $code;

        $exit_user->password = md5($code);
        $exit_user->save(false);

        return $this->init_result(RETURN_SUCCESS, 'success', $data);
    }

}
