<?php

/**
 * This is the model class for table "t_usermsg".
 *
 * The followings are the available columns in table 't_usermsg':
 * @property integer $msgID
 * @property integer $userID
 * @property integer $userFrom
 * @property integer $type
 * @property integer $relateID
 * @property integer $yaoheID
 * @property string $content
 * @property integer $status
 * @property string $createtime
 */
class Usermsg extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_usermsg';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userID,userFrom, type, status,relateID,yaoheID', 'numerical', 'integerOnly' => true),
            array('content', 'length', 'max' => 200),
            array('createtime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('msgID, userID,userFrom, type,relateID,yaoheID, content, status, createtime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'msgID' => 'Msg',
            'userID' => 'User',
            'userFrom' => 'User From',
            'type' => 'Type',
            'relateID' => 'Relate ID',
            'yaoheID' => 'Yaohe ID',
            'content' => 'Content',
            'status' => 'Status',
            'createtime' => 'Createtime',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('msgID', $this->msgID);
        $criteria->compare('userID', $this->userID);
        $criteria->compare('userFrom', $this->userFrom);
        $criteria->compare('type', $this->type);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('relateID', $this->relateID);
        $criteria->compare('yaoheID', $this->yaoheID);
        $criteria->compare('status', $this->status);
        $criteria->compare('createtime', $this->createtime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Usermsg the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 获取用户未读消息
     * @param type $userID
     * @param type $status
     * @return type
     */
    public function getByuserid($userID, $status)
    {
        $sql = 'SELECT ut.*,uf.userHead,uf.userName,uf.nickName,
                CASE 
                    WHEN (ut.type=1 or ut.type=2 or ut.type=4 or ut.type=5) THEN (SELECT left(context,5) FROM t_yaoheinfo WHERE yaoheID=ut.relateID)
                    WHEN ut.type=8 THEN (SELECT left(content,5) FROM t_usercomment WHERE commentID=ut.relateID) 
                END as data,
                (
                    SELECT friendName from t_friends
                    WHERE t_friends.userFrom=:userID
                    AND t_friends.userTo=ut.userFrom 
                ) as friendName
                FROM t_usermsg as ut
                LEFT JOIN t_userinfo as uf ON uf.userID=ut.userFrom  
                WHERE ut.userID =:userID  AND status=:status
                ORDER BY ut.createtime DESC';
        // $sql.=  ' AND status=:status';
//        WHEN (ut.type=1 or ut.type=4 or ut.type=5) THEN (SELECT context FROM t_yaoheinfo WHERE yaoheID=ut.relateID)
//                    WHEN (ut.type=2 or ut.type=8) THEN (SELECT content FROM t_usercomment WHERE commentID=ut.relateID)

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':userID', $userID);
        $cmd->bindParam(':status', $status);
        $reader = $cmd->query();
        $ary = $reader->readAll();

        return $ary;
    }

    /**
     * 添加用户未读消息
     * @param type $userFrom 来自用户
     * @param type $userTo 目标用户
     * @param type $msgType 消息类型
     * @param type $relateID 相关数据id
     * @param type $other 留信息：地址；加好友：验证信息；
     * @param type $data 用于ios端的推送信息；
     */
    public function addUserMsg($userFrom, $userTo, $msgType, $relateID = 0, $other = "", $data = "", $yaoheid = 0)
    {
        if ($userFrom == $userTo)
        {
            //自己给自己的消息不做消息推送
            return;
        }
        //保存用户消息
        $usermsg = new Usermsg();
        $usermsg->userFrom = $userFrom;
        $usermsg->userID = $userTo;
        $usermsg->type = $msgType;
        //$usermsg->content = $user->userName . '请求加好友';
        if (isset($other) && $other != "")
        {
            $usermsg->content = $other;
        }
        $usermsg->status = MESSAGE_STATUS_UNREAD;
        $usermsg->createtime = date('Y-m-d H:i:s');
        $usermsg->relateID = $relateID;
        $usermsg->yaoheID = $yaoheid;
        $usermsg->insert();

        busUlitity::applePushByUserid($userFrom, $userTo, $msgType, $other, $data, $usermsg->primaryKey);
    }

}
