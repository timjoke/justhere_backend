<?php

class AppController extends Controller
{

    public function actionContextHex()
    {
        //$trans = Yii::app()->db->beginTransaction();
        $id = 0;
        try
        {
            //echo 'begin...';
            for ($id = 1271; $id <= 3639;$id++)
            {
                $yaohe = null;
                $yaohe = Yaoheinfo::model()->find('yaoheID='.$id);
                if (isset($yaohe))
                {
                    $context = bin2hex($yaohe->context);
                    //echo $yaohe->context.'<br/>';
                    Yaoheinfo::model()->updateByPk($id, array('context'=>$context));
                }
            } 
            echo 'success,id='.$id;
            //$trans->commit();
        } catch (Exception $ex)
        {
            //$trans->rollback();
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            //return $this->init_result(RETURN_ERROR, $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    /**
     * 获取短信验证码
     */
    public function actionGetMessageCode()
    {
        try
        {
            $busApi = new busApi();
            $result = $obj = $busApi->getMessageCode();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     * 通过短信验证码注册用户
     * 忘记密码时，使用手机验证码当做密码
     */
    public function actionGetValidCode()
    {
        try
        {
            $busApi = new busApi();
            $result = $obj = $busApi->getValidCode();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  上传文件
     */
    public function actionUploadHeadImage()
    {
        try
        {
            $busApi = new busApi();
            $result = $obj = $busApi->uploadHeadImage();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  上传文件
     */
    public function actionUploadFile()
    {
        try
        {
            $busApi = new busApi();
            $result = $obj = $busApi->uploadFile();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  初始化
     */
    public function actionInit()
    {
        try
        {
            $busApi = new busApi();
            $result = $obj = $busApi->init();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  注册
     */
    public function actionRegister()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->register();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  绑定手机号
     */
    public function actionValidPhone()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->validPhone();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  登陆
     */
    public function actionLogin()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->login();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  登出
     */
    public function actionLoginOut()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->loginOut();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  改变用户登陆状态
     */
    public function actionChangeLoginStatus()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->changeLoginStatus();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  完善用户信息
     */
    public function actionFinishUserInfo()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->finishUserInfo();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  发布一个吆喝信息
     */
    public function actionPublishYaohe()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->publishYaohe();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  对吆喝信息发表评论
     */
    public function actionCommentYaohe()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->commentYaohe();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取一定范围内的吆喝信息
     */
    public function actionGetNearByYaoheInfo()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getNearByYaoheInfo();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取一定范围内的吆喝信息
     */
    public function actionGetNearYaoheInfo()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getNearYaoheInfo();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取一定范围内的吆喝信息
     */
    public function actionGetSelfYaoheInfo()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getSelfYaoheInfo();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取一个吆喝信息及其所有评论信息
     */
    public function actionGetYaoheDetailsInfo()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getYaoheDetailsInfo();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取某个用户发布的吆喝信息
     */
    public function actionGetYaoheInfoByUser()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getYaoheInfoByUser();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取留给某个用户信息
     */
    public function actionGetYaoheInfoByToUser()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getYaoheInfoByToUser();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取当前用户未读的消息数
     */
    public function actionGetUnreadCount()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getUnreadCount();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取当前用户未读的消息列表
     */
    public function actionGetUnreadMsg()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getUnreadMsg();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  修改好友备注
     */
    public function actionUpdateFriendName()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->updateFriendName();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取好友列表
     */
    public function actionGetAllFriends()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getAllFriends();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取好友的好友列表
     */
    public function actionGetOtherFriends()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getOtherFriends();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  查找用户
     */
    public function actionGetFriendByName()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getFriendByName();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  添加好友请求
     */
    public function actionAddFriendsRequest()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->addFriendsRequest();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  已读消息
     */
    public function actionReadMsg()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->readMsg();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  接受或拒绝加好友请求
     */
    public function actionConfirmOrDenyFriendRequest()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->confirmOrDenyFriendRequest();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  删除好友
     */
    public function actionDelFriend()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->delFriends();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  删除吆喝
     */
    public function actionDeleteYaohe()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->deleteYaohe();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  赞吆喝信息
     */
    public function actionGoodYaohe()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->goodYaohe();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  取消赞吆喝信息
     */
    public function actionUngoodYaohe()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->ungoodYaohe();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取指定用户的信息、好友数量、留下的消息数量、已发现消息数量和未发现消息数量
     */
    public function actionGetUserFullInfo()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getUserFullInfo();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  问题反馈
     */
    public function actionAddQuestion()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->addQuestion();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取问题反馈
     */
    public function actionGetAllQuestion()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getAllQuestion();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  获取手机联系人信息
     */
    public function actionGetAllContact()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getAllContact();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     *  保存手机联系人信息
     */
    public function actionSaveContact()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->saveContact();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }
    
    /**
     *  保存手机联系人信息
     */
    public function actionSavePassword()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->savePassword();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }
    
    /**
     *  保存手机联系人信息
     */
    public function actionResetPassword()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->resetPassword();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }
    
    /**
     *  保存手机联系人信息
     */
    public function actionGetVCodeInResetPWD()
    {
        try
        {
            $busApi = new busApi();
            $result = $busApi->getVCodeInResetPWD();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    /**
     * 测试用
     */
    public function actionTest()
    {
        try
        {
            $token = $_POST['access_token'];
            $data = array();

//            $session_id = Yii::app()->session->sessionId;
//            $session_key = 'validcode_status' . $session_id;
//            $data['last_valid_time'] = Yii::app()->session[$session_key];
//            $data['code'] = Yii::app()->session['validcode18611691649'];

            $data['token'] = Yii::app()->session[$token];
            $data['user_telphone'] = $data['token']->telphone;
            $result = array(
                'code' => RETURN_SUCCESS,
                'message' => 'success',
                'data' => $data
            );

//            $result = array(
//                'code' => 200,
//                'message' => 'success',
//                'data' => 'It works'
//            );
            echo json_encode($result);
            exit;
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    private function error($ex)
    {
        $result = array(
            'code' => RETURN_ERROR,
            'message' => $ex->getMessage(),
            'data' => $ex->getTrace()
        );
        echo json_encode($result);
        exit;
    }

//    public function actionAddjusthere()
//    {
//        try
//        {
//            $all_users = Userinfo::model()->findAll("userID<>1");
//
//            $trans = Yii::app()->db->beginTransaction();
//            foreach ($all_users as $value)
//            {
//                $friend = new Friends();
//                $friend->userFrom = $value->userID;
//                $friend->friendName = "恰好";
//                $friend->userTo = 1;
//                $friend->status = FRIEND_STATUS_ACCEPT;
//                $friend->invoker = 1;
//                $friend->insert();
//            }
//
//            $trans->commit();
//        }
//        catch (Exception $ex)
//        {
//            $trans->rollback();
//            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
//            //return $this->init_result(RETURN_ERROR, $ex->getMessage());
//            echo $ex->getMessage();
//            exit();
//        }
//        echo 'success!';
//    }
}
