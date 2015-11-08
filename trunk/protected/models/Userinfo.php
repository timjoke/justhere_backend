<?php

/**
 * This is the model class for table "t_userinfo".
 *
 * The followings are the available columns in table 't_userinfo':
 * @property integer $userID
 * @property string $email
 * @property string $telphone
 * @property string $password
 * @property integer $usertype
 * @property string $userName
 * @property string $nickName
 * @property string $userHead
 * @property integer $sex
 * @property string $modeldes
 * @property string $createtime
 * @property string $weibo_name
 * @property string $weixin_name
 * @property integer $login_type
 * @property string $device_token
 * @property integer $login_status
 */
class Userinfo extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_userinfo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userID', 'required'),
            array('userID, telphone, usertype, sex, login_type,login_status', 'numerical', 'integerOnly' => true),
            array('email, password, modeldes', 'length', 'max' => 60),
            array('userName,nickName, userHead, weibo_name, weixin_name', 'length', 'max' => 50),
            array('device_token', 'length', 'max' => 70),
            array('createtime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('userID, email, telphone, password, usertype, userName,nickName, userHead, sex, modeldes, createtime, weibo_name, weixin_name, login_type,device_token,login_status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'userID' => 'User',
            'email' => 'Email',
            'telphone' => 'Telphone',
            'password' => 'Password',
            'usertype' => 'Usertype',
            'userName' => 'User Name',
            'nickName' => 'Nick Name',
            'userHead' => 'User Head',
            'sex' => 'Sex',
            'modeldes' => 'Modeldes',
            'createtime' => 'Createtime',
            'weibo_name' => 'Weibo Name',
            'weixin_name' => 'Weixin Name',
            'login_type' => 'Login Type',
            'device_token' => 'Device Token',
            'login_status' => 'Login Status'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('userID', $this->userID);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('telphone', $this->telphone);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('usertype', $this->usertype);
        $criteria->compare('userName', $this->userName, true);
        $criteria->compare('nickName', $this->nickName, true);
        $criteria->compare('userHead', $this->userHead, true);
        $criteria->compare('sex', $this->sex);
        $criteria->compare('modeldes', $this->modeldes, true);
        $criteria->compare('createtime', $this->createtime, true);
        $criteria->compare('weibo_name', $this->weibo_name, true);
        $criteria->compare('weixin_name', $this->weixin_name, true);
        $criteria->compare('login_type', $this->login_type);
        $criteria->compare('device_token', $this->device_token);
        $criteria->compare('login_status', $this->login_status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Userinfo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 查询用户信息,以及当前用户对该用户的备注
     * @param type $userid
     * @param type $curr_userid
     * @return type
     */
    public function getUsersByUserid($userid, $curr_userid) {
        $sql = 'SELECT userID,email,telphone,usertype,userName,nickName,password,userHead,sex,modeldes,device_token,login_status,
                (
                    SELECT friendName from t_friends
                    WHERE t_friends.userFrom=:curr_userid
                    AND t_friends.userTo=:userid LIMIT 1 
                ) as friendName    
                FROM t_userinfo
                WHERE userID =:userid';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':userid', $userid);
        $cmd->bindParam(':curr_userid', $curr_userid);
        $reader = $cmd->query();
        $ary = $reader->readAll();
        return count($ary) > 0 ? busUlitity::arrayToObject($ary[0]) : null;
    }

    /**
     * 查询用户信息,以及该用户对当前用户的备注
     * @param type $userid
     * @param type $curr_userid
     * @return type
     */
    public function getUsersAndFriendNameByUserid($userFrom, $userTo) {
        $sql = 'SELECT userID,email,telphone,usertype,userName,nickName,password,userHead,sex,modeldes,device_token,login_status,
                (
                    SELECT friendName from t_friends
                    WHERE t_friends.userFrom=:userTo
                    AND t_friends.userTo=:userFrom LIMIT 1 
                ) as friendName    
                FROM t_userinfo
                WHERE userID =:userFrom';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':userFrom', $userFrom);
        $cmd->bindParam(':userTo', $userTo);
        $reader = $cmd->query();
        $ary = $reader->readAll();
        return count($ary) > 0 ? busUlitity::arrayToObject($ary[0]) : null;
    }

    /**
     * 查询用户信息
     * @param type $username
     * @param type $pwd
     * @return type
     */
    public function getUsersByUsernameAndPwd($username, $pwd) {
        $sql = 'SELECT userID,email,telphone,usertype,userName,nickName,password,userHead,sex,device_token  
                FROM t_userinfo WHERE password =:pwd and 
                (telphone=:name or email=:name or userName=:name)';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':pwd', $pwd);
        $cmd->bindParam(':name', $username);
        $reader = $cmd->query();
        $ary = $reader->readAll();
        return count($ary) > 0 ? busUlitity::arrayToObject($ary[0]) : null;
    }

    /**
     * 查询yaoheid对应的用户信息
     * @param type $yaoheid
     * @param type $curr_userid
     * @return type
     */
    public function getUsersByYaoheid($yaoheid, $curr_userid) {
        $sql = 'SELECT ytu.userTo,ytu.replyStatus,ui.userID,ui.email,ui.telphone,ui.usertype,
            ui.userName,ui.nickName,ui.userHead,ui.sex,ui.device_token,ui.login_status,
                (
                    SELECT friendName from t_friends
                    WHERE t_friends.userFrom=:curr_userid
                    AND t_friends.userTo=ytu.userTo LIMIT 1 
                ) as friendName 
                FROM t_yaohetouser as ytu
                LEFT JOIN t_userinfo as ui ON ytu.userTo = ui.userID 
                WHERE ytu.yaoheID =:yaoheid';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':yaoheid', $yaoheid);
        $cmd->bindParam(':curr_userid', $curr_userid);
        $reader = $cmd->query();
        $ary = $reader->readAll();

        return $ary;
    }

    /**
     * 查询用户的好友列表
     * @param type $userid
     * @return type
     */
    public function getUserFriends($userid) {
        $sql = 'SELECT f.userFrom,f.friendName,f.status,f.invoker,ui.*,
                (
                    select content from t_usermsg where t_usermsg.relateID=f.friendID and t_usermsg.type=3  
                ) as validmsg 
                FROM t_friends as f
                INNER JOIN t_userinfo as ui ON f.userTo=ui.userID 
                WHERE f.userFrom=:userid and (f.status=2 or f.status=1) 
                AND ui.userID<>:userid';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':userid', $userid);
        $reader = $cmd->query();
        $ary = $reader->readAll();
        return $ary;
    }

    /**
     * 查询用户的好友列表
     * @param type $userid
     * @return type
     */
    private function getFriendship($user_from, $user_to) {
        $sql = 'SELECT f.userFrom,f.friendName,f.status,f.invoker,
                (
                    select content from t_usermsg where t_usermsg.relateID=f.friendID and t_usermsg.type=3 
                ) as validmsg 
                FROM t_friends as f               
                WHERE f.userFrom=:user_from and f.userTo=:user_to and f.status in(1,2)';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':user_from', $user_from);
        $cmd->bindParam(':user_to', $user_to);
        $reader = $cmd->query();
        $ary = $reader->readAll();
        if (isset($ary) && count($ary) > 0)
            return $ary[0];
        return $ary;
    }

    /**
     * 查询用户的好友的好友列表
     * @param type $userid
     * @return type
     */
    public function getOtherFriends($userid, $user_from) {
        $sql = 'SELECT ui.* 
                FROM t_friends as f
                INNER JOIN t_userinfo as ui ON f.userTo=ui.userID 
                WHERE f.userFrom=:userid and f.status in(2)  
                AND ui.userID<>:userid and ui.userID<>:user_from';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':userid', $userid);
        $cmd->bindParam(':user_from', $user_from);
        $reader = $cmd->query();
        $users = $reader->readAll();

        if (isset($users)) {
            foreach ($users as &$user) {
                $exist_friend = $this->getFriendship($user_from, $user['userID']);
                //var_dump($user);
                //exit();
                if (isset($exist_friend)) {
                    $user['status'] = (isset($exist_friend['status'])?$exist_friend['status']:"0");
                    $user['friendName'] = (isset($exist_friend['friendName'])?$exist_friend['friendName']:'');
                    $user['validmsg'] = (isset($exist_friend['validmsg'])?$exist_friend['validmsg']:'');
                }
                else
                {
                    //$user['status'] = "0";
                }
            }
        }

        return $users;
    }

    /**
     * 查询用户信息
     * @param type $username 查询的用户名/邮箱/手机号
     * @param type @userID 查询人的用户ID
     * @return type
     */
    public function getUsersByUsername($username, $userID) {
//                       (
//                    SELECT COUNT(*) FROM t_friends 
//                    WHERE t_friends.userFrom = u.userID 
//                    AND t_friends.userTo = :userid 
//                ) as isFriend 
        $sql = 'SELECT u.userID,u.email,u.telphone,u.usertype,u.userName,u.nickName,u.userHead,u.sex,
                f.friendID,f.status,f.invoker   
                FROM t_userinfo as u
                LEFT JOIN t_friends as f 
                ON u.userID = f.userTo AND f.userFrom = :userid 
                WHERE u.userID<>:userid
                AND (u.telphone like "%' . $username
                . '%" or u.email like "%' . $username
                . '%" or u.nickName like "%' . $username
                . '%" or u.userName like "%' . $username . '%")';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':userid', $userID);
        $reader = $cmd->query();
        $ary = $reader->readAll();
        return $ary;
    }
    
    /**
     * 查询用户的好友列表
     * @param type $userid
     * @return string
     */
    public function getRandomNickname() {
        $sql = 'select nickname from t_userinfo where usertype=5 ORDER BY RAND() LIMIT 1';
        $cmd = Yii::app()->db->createCommand($sql);
        $reader = $cmd->query();
        $ary = $reader->readAll();
        if (isset($ary) && count($ary) > 0)
            return $ary[0]["nickname"];
        return "";
    }

}
