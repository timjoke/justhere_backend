<?php

/**
 * This is the model class for table "t_yaoheinfo".
 *
 * The followings are the available columns in table 't_yaoheinfo':
 * @property string $yaoheID
 * @property integer $userID
 * @property string $context
 * @property string $contact_tel
 * @property string $publish_date
 * @property integer $status
 * @property string $longitude
 * @property string $latitude
 * @property string $address
 * @property integer $radius
 * @property integer $yaohe_type
 * @property string $fileinfo_id
 */
class Yaoheinfo extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_yaoheinfo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('yaoheID', 'required'),
            array('userID, status, radius, yaohe_type', 'numerical', 'integerOnly' => true),
            array('yaoheID', 'length', 'max' => 36),
            array('context', 'length', 'max' => 256),
            array('address', 'length', 'max' => 50),
            array('contact_tel', 'length', 'max' => 20),
            array('longitude, latitude', 'length', 'max' => 11),
            array('fileinfo_id', 'length', 'max' => 10),
            array('publish_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('yaoheID, userID, context, contact_tel, publish_date, status, longitude, latitude,address, radius, yaohe_type, fileinfo_id', 'safe', 'on' => 'search'),
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
            'yaoheID' => 'Yaohe',
            'userID' => 'User',
            'context' => 'Context',
            'contact_tel' => 'Contact Tel',
            'publish_date' => 'Publish Date',
            'status' => 'Status',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'address' => 'Address',
            'radius' => 'Radius',
            'yaohe_type' => 'Yaohe Type',
            'fileinfo_id' => 'Fileinfo',
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

        $criteria->compare('yaoheID', $this->yaoheID, true);
        $criteria->compare('userID', $this->userID);
        $criteria->compare('context', $this->context, true);
        $criteria->compare('contact_tel', $this->contact_tel, true);
        $criteria->compare('publish_date', $this->publish_date, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('longitude', $this->longitude, true);
        $criteria->compare('latitude', $this->latitude, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('radius', $this->radius);
        $criteria->compare('yaohe_type', $this->yaohe_type);
        $criteria->compare('fileinfo_id', $this->fileinfo_id, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Yaoheinfo the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 获取一定范围内的吆喝信息
     * @param type $lng
     * @param type $lat
     * @param type $radius
     * @param type $id_list
     * @param type $last_time
     * @return array
     */
    public function getNearByYaoheInfo($lng, $lat, $radius, $id_list = array(), $last_time, $userID)
    {
        //$last_time = "2014-01-01";
//        $sql = 'SELECT DISTINCT yaohe.yaoheID, yaohe.*,file.filepath 
//                FROM t_yaoheinfo as yaohe 
//                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID
//                LEFT JOIN t_yaohetouser as ytu2 ON ytu2.yaoheID=yaohe.yaoheID 
//                WHERE (fnGetDistance(yaohe.latitude,yaohe.longitude,:lat,:lng)<:radius
//                AND yaohe.userID<>:userid and yaohe.publish_date>:last_time and ytu2.userTo<>:userid and yaohe.status=' . YAOHE_STATUS_ABLE.')';

        $sql = 'select yaohe.*,file.filepath,
                (
                   select replyStatus from t_yaohetouser as ytu where ytu.yaoheID=yaohe.yaoheID and ytu.userTo=:userid
                ) as replyStatus 
                FROM t_yaoheinfo as yaohe 
                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID 
                WHERE (fnGetDistance(yaohe.latitude,yaohe.longitude,:lat,:lng))<:radius 
                AND yaohe.userID<>:userid 
                AND yaohe.userID not in(select userTo from t_friends where userFrom=:userid) 
                AND yaohe.publish_date>=:last_time 
                AND yaohe.status=' . YAOHE_STATUS_ABLE;

        if (isset($id_list) && count($id_list) > 0)
        {
            $sql .= ' AND yaohe.yaoheID NOT IN(' . implode(',', $id_list) . ')';
        }
        $sql.= ' ORDER BY yaohe.publish_date DESC ';
        $conn = Yii::app()->db;
        $cmd = $conn->createCommand($sql);
        $cmd->bindParam(':lng', $lng);
        $cmd->bindParam(':lat', $lat);
        $cmd->bindParam(':radius', $radius);
        $cmd->bindParam(':last_time', $last_time);
        $cmd->bindParam(':userid', $userID);
        $reader = $cmd->query();
        $yaohes = $reader->readAll();

        if (isset($yaohes))
        {
            foreach ($yaohes as &$yaohe)
            {
                $yaohe['context'] = busUlitity::hex2bin($yaohe['context']);
                $yaohe['userFrom'] = Userinfo::model()->getUsersByUserid($yaohe['userID'], $userID);
                $yaohe['usersTo'] = Userinfo::model()->getUsersByYaoheid($yaohe['yaoheID'], $userID);
                $yaohe['comments'] = UserComment::model()->getCommentsByYaoheid($yaohe['yaoheID'], $userID);
                //$yaohe['goods'] = Good::model()->getGoodsByYaoheid($yaohe['yaoheID']);
                $yaohe['goodCount'] = Good::model()->count('yaoheID = :yaoheID and status=1', array(':yaoheID' => $yaohe['yaoheID']));
                $goodBySelf = Good::model()->findByAttributes(array(), 'yaoheID = :yaoheID and status=1 and userFrom=:userFrom', array(':yaoheID' => $yaohe['yaoheID'], ':userFrom' => $userID));
                $yaohe['goodBySelf'] = isset($goodBySelf) ? 1 : 0;
            }
        }
        return $yaohes;
    }

    /**
     * 获取系统信息
     * @return array
     */
    public function getSystemYaoheInfo($userID)
    {
        //$last_time = "2014-01-01";
        $sql = 'SELECT DISTINCT yaohe.yaoheID, yaohe.*,file.filepath 
                FROM t_yaoheinfo as yaohe 
                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID
                WHERE yaohe.userID=1 and yaohe.status=' . YAOHE_STATUS_ABLE;
        $sql.= ' ORDER BY yaohe.publish_date DESC ';
        $conn = Yii::app()->db;
        $cmd = $conn->createCommand($sql);
        $reader = $cmd->query();
        $yaohes = $reader->readAll();

        if (isset($yaohes))
        {
            foreach ($yaohes as &$yaohe)
            {
                $yaohe['context'] = busUlitity::hex2bin($yaohe['context']);
                $yaohe['userFrom'] = Userinfo::model()->getUsersByUserid($yaohe['userID'], $userID);
                $yaohe['usersTo'] = Userinfo::model()->getUsersByYaoheid($yaohe['yaoheID'], $userID);
                $yaohe['comments'] = UserComment::model()->getCommentsByYaoheid($yaohe['yaoheID'], $userID);
                //$yaohe['goods'] = Good::model()->getGoodsByYaoheid($yaohe['yaoheID']);
                $yaohe['goodCount'] = Good::model()->count('yaoheID = :yaoheID and status=1', array(':yaoheID' => $yaohe['yaoheID']));
                $goodBySelf = Good::model()->findByAttributes(array(), 'yaoheID = :yaoheID and status=1 and userFrom=:userFrom', array(':yaoheID' => $yaohe['yaoheID'], ':userFrom' => $userID));
                $yaohe['goodBySelf'] = isset($goodBySelf) ? 1 : 0;
            }
        }
        return $yaohes;
    }

    /**
     * 根据id获取吆喝信息
     * @param type $yaoheID
     * @return array
     */
    public function getYaoheInfoByID($yaoheID, $userID)
    {
        $sql = 'SELECT yaohe.*,file.filepath, 
                (
                    SELECT replyStatus FROM t_yaohetouser as ytu
                    WHERE ytu.yaoheID = yaohe.yaoheID AND ytu.userTo=:userid
                ) as replyStatus 
                FROM t_yaoheinfo as yaohe
                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID
                WHERE yaohe.yaoheID=:yaoheID ';
        $sql.= ' ORDER BY yaohe.publish_date DESC ';
        $conn = Yii::app()->db;
        $cmd = $conn->createCommand($sql);
        $cmd->bindParam(':yaoheID', $yaoheID);
        $cmd->bindParam(':userid', $userID);
        $reader = $cmd->query();
        $yaohes = $reader->readAll();

        if (isset($yaohes) && count($yaohes) > 0)
        {
            foreach ($yaohes as &$yaohe)
            {
                $yaohe['context'] = busUlitity::hex2bin($yaohe['context']);
                $yaohe['userFrom'] = Userinfo::model()->getUsersByUserid($yaohe['userID'], $userID);
                $yaohe['usersTo'] = Userinfo::model()->getUsersByYaoheid($yaohe['yaoheID'], $userID);
                $yaohe['comments'] = UserComment::model()->getCommentsByYaoheid($yaohe['yaoheID'],$userID);
                $yaohe['goods'] = Good::model()->getGoodsByYaoheid($yaohe['yaoheID']);
                $goodBySelf = Good::model()->findByAttributes(array(), 'yaoheID = :yaoheID and status=1 and userFrom=:userFrom', array(':yaoheID' => $yaohe['yaoheID'], ':userFrom' => $userID));
                $yaohe['goodBySelf'] = isset($goodBySelf) ? 1 : 0;
            }
            return $yaohes[0];
        }
        return null;
    }

    /**
     * 获取用户的吆喝信息
     * @param type $userID
     * @return array
     */
    public function getYaoheInfoByUserID($userID)
    {
        $sql = 'SELECT yaohe.*,file.filepath, 
                (
                    SELECT replyStatus FROM t_yaohetouser as ytu
                    WHERE ytu.yaoheID = yaohe.yaoheID AND ytu.userTo=:userID
                ) as replyStatus 
                FROM t_yaoheinfo as yaohe 
                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID
                WHERE yaohe.userID=:userID and yaohe.status=' . YAOHE_STATUS_ABLE;
        $sql.= ' ORDER BY yaohe.publish_date DESC ';
        $conn = Yii::app()->db;
        $cmd = $conn->createCommand($sql);
        $cmd->bindParam(':userID', $userID);
        $reader = $cmd->query();
        $yaohes = $reader->readAll();

        if (isset($yaohes) && count($yaohes) > 0)
        {
            foreach ($yaohes as &$yaohe)
            {
                $yaohe['context'] = busUlitity::hex2bin($yaohe['context']);
                $yaohe['userFrom'] = Userinfo::model()->getUsersByUserid($yaohe['userID'], $userID);
                $yaohe['usersTo'] = Userinfo::model()->getUsersByYaoheid($yaohe['yaoheID'], $userID);
                $yaohe['comments'] = UserComment::model()->getCommentsByYaoheid($yaohe['yaoheID'], $userID);
                $yaohe['goodCount'] = Good::model()->count('yaoheID = :yaoheID and status=1', array(':yaoheID' => $yaohe['yaoheID']));
                $goodBySelf = Good::model()->findByAttributes(array(), 'yaoheID = :yaoheID and status=1 and userFrom=:userFrom', array(':yaoheID' => $yaohe['yaoheID'], ':userFrom' => $userID));
                $yaohe['goodBySelf'] = isset($goodBySelf) ? 1 : 0;
            }
        }
        return $yaohes;
    }

    /**
     * 获取留给指定用户的吆喝信息
     * @param type $userID
     * @param type $find_status //信息发现状态
     *              0：所有
     *              1：未发现
     *              2：已发现
     * @return array
     */
    public function getYaoheInfoByToUserID($userID, $find_status)
    {
        $sql = 'SELECT distinct yaohe.yaoheID,ytu.replyStatus,yaohe.*,file.filepath 
                From t_yaohetouser as ytu
                LEFT JOIN t_yaoheinfo as yaohe ON ytu.yaoheID=yaohe.yaoheID
                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID
                WHERE ytu.userTo=:userID and yaohe.status=' . YAOHE_STATUS_ABLE;
        if (isset($find_status))
        {
            $find_status = intval($find_status);
            if ($find_status <= 2 && $find_status > 0)
            {
                $sql.= ' AND ytu.replyStatus=' . $find_status;
            }
        }
        $sql.= ' ORDER BY yaohe.publish_date DESC ';
        $conn = Yii::app()->db;
        $cmd = $conn->createCommand($sql);
        $cmd->bindParam(':userID', $userID);
        $reader = $cmd->query();
        $yaohes = $reader->readAll();

        if (isset($yaohes) && count($yaohes) > 0)
        {
            foreach ($yaohes as &$yaohe)
            {
                $yaohe['context'] = busUlitity::hex2bin($yaohe['context']);
                $yaohe['userFrom'] = Userinfo::model()->getUsersByUserid($yaohe['userID'], $userID);
                $yaohe['usersTo'] = Userinfo::model()->getUsersByYaoheid($yaohe['yaoheID'], $userID);
                $yaohe['comments'] = UserComment::model()->getCommentsByYaoheid($yaohe['yaoheID'], $userID);
                $yaohe['goodCount'] = Good::model()->count('yaoheID = :yaoheID and status=1', array(':yaoheID' => $yaohe['yaoheID']));
                $goodBySelf = Good::model()->findByAttributes(array(), 'yaoheID = :yaoheID and status=1 and userFrom=:userFrom', array(':yaoheID' => $yaohe['yaoheID'], ':userFrom' => $userID));
                $yaohe['goodBySelf'] = isset($goodBySelf) ? 1 : 0;
            }
        }
        return $yaohes;
    }

    /**
     * 获取好友留下的吆喝信息
     * @param type $userID
     * @return array
     */
    public function getYaoheInfoByFriend($userID)
    {
        $sql = 'SELECT yaohe.*,file.filepath, 
                (
                    SELECT replyStatus FROM t_yaohetouser as ytu
                    WHERE ytu.yaoheID = yaohe.yaoheID AND ytu.userTo=:userID
                ) as replyStatus 
                from t_friends as f 
                LEFT JOIN t_yaoheinfo as yaohe ON f.userTo=yaohe.userID
                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID
                WHERE f.userFrom=:userID and f.status=2 and yaohe.status=' . YAOHE_STATUS_ABLE;
        $sql.= ' ORDER BY yaohe.publish_date DESC ';
        $conn = Yii::app()->db;
        $cmd = $conn->createCommand($sql);
        $cmd->bindParam(':userID', $userID);
        //$cmd->bindParam(':friend_status', FRIEND_STATUS_ACCEPT);
        $reader = $cmd->query();
        $yaohes = $reader->readAll();

        if (isset($yaohes) && count($yaohes) > 0)
        {
            foreach ($yaohes as &$yaohe)
            {
                $yaohe['context'] = busUlitity::hex2bin($yaohe['context']);
                $yaohe['userFrom'] = Userinfo::model()->getUsersByUserid($yaohe['userID'], $userID);
                $yaohe['usersTo'] = Userinfo::model()->getUsersByYaoheid($yaohe['yaoheID'], $userID);
                $yaohe['comments'] = UserComment::model()->getCommentsByYaoheid($yaohe['yaoheID'], $userID);
                $yaohe['goodCount'] = Good::model()->count('yaoheID = :yaoheID and status=1', array(':yaoheID' => $yaohe['yaoheID']));
                $goodBySelf = Good::model()->findByAttributes(array(), 'yaoheID = :yaoheID and status=1 and userFrom=:userFrom', array(':yaoheID' => $yaohe['yaoheID'], ':userFrom' => $userID));
                $yaohe['goodBySelf'] = isset($goodBySelf) ? 1 : 0;
            }
        }
        return $yaohes;
    }

    /**
     * 获取与我相关的吆喝信息
     * @param type $lng
     * @param type $lat
     * @param type $radius
     * @param type $id_list
     * @param type $last_time
     * @return array
     */
    public function getSelfYaoheInfo($userID, $last_time, $update_time, $id_list = array(), $page = 1)
    {
//        $sql = 'select yaohe.*,file.filepath,
//                (
//                   select replyStatus from t_yaohetouser as ytu where ytu.yaoheID=yaohe.yaoheID and ytu.userTo=:userid
//                ) as replyStatus 
//                FROM t_yaoheinfo as yaohe            
//                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID 
//                WHERE 
//                (
//                    yaohe.userID=:userid 
//                    OR yaohe.userID in(select userTo from t_friends where userFrom=:userid and status=2)
//                ) 
//                AND yaohe.publish_date<=:update_time 
//                AND yaohe.status=' . YAOHE_STATUS_ABLE;
//        $sql = 'SELECT distinct yaohe.yaoheID,ytu.replyStatus,yaohe.*,file.filepath 
//                From t_yaohetouser as ytu
//                LEFT JOIN t_yaoheinfo as yaohe ON ytu.yaoheID=yaohe.yaoheID
//                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID
//                WHERE (ytu.userTo=:userid or yaohe.userID=:userid) 
//                
//                AND yaohe.publish_date<=:update_time 
//                AND yaohe.status=' . YAOHE_STATUS_ABLE;

        $sql = 'SELECT distinct yaohe.yaoheID,yaohe.*,file.filepath 
                From t_yaoheinfo as yaohe
                LEFT JOIN t_yaohetouser as ytu ON ytu.yaoheID=yaohe.yaoheID 
                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID
                WHERE 
                (
                    yaohe.userID=:userid 
                    or ytu.userTo=:userid 
                    or 
                    (   
                        yaohe.userID in(select userTo from t_friends where userFrom=:userid and status=2) 
                        and ytu.replyStatus is NULL
                    )
                ) 
                AND yaohe.publish_date<=:update_time 
                AND yaohe.status=' . YAOHE_STATUS_ABLE;

        //AND yaohe.publish_date>=:last_time 

//        if (isset($id_list) && count($id_list) > 0)
//        {
//            $sql .= ' AND yaohe.yaoheID NOT IN(' . implode(',', $id_list) . ')';
//        }
        $pagesize = 10;
        $page_index = ($page - 1) * $pagesize;
        $sql.= ' ORDER BY yaohe.publish_date DESC LIMIT :pageindex,:pagesize ';
        $conn = Yii::app()->db;
        $cmd = $conn->createCommand($sql);
        //$cmd->bindParam(':last_time', $last_time);
        $cmd->bindParam(':update_time', $update_time);
        $cmd->bindParam(':userid', $userID);
        $cmd->bindParam(':pageindex', $page_index);
        $cmd->bindParam(':pagesize', $pagesize);
        //$cmd->bindParam(':friend_status', FRIEND_STATUS_ACCEPT);
        $reader = $cmd->query();
        $yaohes = $reader->readAll();

        if (isset($yaohes))
        {
            foreach ($yaohes as &$yaohe)
            {
                $yaohe['replyStatus'] = YaoheToUser::model()->getReplyStatus($userID,$yaohe['yaoheID']);
                $yaohe['context'] = busUlitity::hex2bin($yaohe['context']);
                $yaohe['userFrom'] = Userinfo::model()->getUsersByUserid($yaohe['userID'], $userID);
                $yaohe['usersTo'] = Userinfo::model()->getUsersByYaoheid($yaohe['yaoheID'], $userID);
                $yaohe['comments'] = UserComment::model()->getCommentsByYaoheid($yaohe['yaoheID'], $userID);
                //$yaohe['goods'] = Good::model()->getGoodsByYaoheid($yaohe['yaoheID']);
                $yaohe['goodCount'] = Good::model()->count('yaoheID = :yaoheID and status=1', array(':yaoheID' => $yaohe['yaoheID']));
                $goodBySelf = Good::model()->findByAttributes(array(), 'yaoheID = :yaoheID and status=1 and userFrom=:userFrom', array(':yaoheID' => $yaohe['yaoheID'], ':userFrom' => $userID));
                $yaohe['goodBySelf'] = isset($goodBySelf) ? 1 : 0;
            }
        }
        return $yaohes;
    }

    /**
     * 获取一定范围内的吆喝信息
     * @param type $lng
     * @param type $lat
     * @param type $radius
     * @param type $id_list
     * @param type $last_time
     * @return array
     */
    public function getNearYaoheInfo($lng, $lat, $radius, $id_list = array(), $last_time, $userID, $page = 1)
    {
        //$last_time = "2014-01-01";
//        $sql = 'SELECT DISTINCT yaohe.yaoheID, yaohe.*,file.filepath 
//                FROM t_yaoheinfo as yaohe 
//                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID
//                LEFT JOIN t_yaohetouser as ytu2 ON ytu2.yaoheID=yaohe.yaoheID 
//                WHERE (fnGetDistance(yaohe.latitude,yaohe.longitude,:lat,:lng)<:radius
//                AND yaohe.userID<>:userid and yaohe.publish_date>:last_time and ytu2.userTo<>:userid and yaohe.status=' . YAOHE_STATUS_ABLE.')';

//        $sql = 'select yaohe.*,file.filepath from t_yaoheinfo as yaohe 
//                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID
//                WHERE (fnGetDistance(yaohe.latitude,yaohe.longitude,:lat,:lng))<:radius 
//                AND yaohe.userID<>:userid 
//                AND yaohe.userID not in(select userTo from t_friends where userFrom=:userid and status=2) 
//                AND yaohe.publish_date<=:last_time 
//                AND yaohe.status=' . YAOHE_STATUS_ABLE;
        
        $sql = 'select distinct yaohe.*,file.filepath from t_yaoheinfo as yaohe 
                LEFT JOIN t_fileinfo as file ON yaohe.fileinfo_id=file.fileID 
                LEFT JOIN t_yaohetouser as ytu ON ytu.yaoheID=yaohe.yaoheID 
                WHERE (fnGetDistance(yaohe.latitude,yaohe.longitude,:lat,:lng))<:radius 
                AND ytu.replyStatus is NULL 
                AND yaohe.publish_date<=:last_time 
                AND yaohe.status=' . YAOHE_STATUS_ABLE;

//        if (isset($id_list) && count($id_list) > 0 && is_array($id_list))
//        {
//            $sql .= ' AND yaohe.yaoheID NOT IN(' . implode(',', $id_list) . ')';
//        }
        $pagesize = 10;
        $page_index = ($page - 1) * $pagesize;
        $sql.= ' ORDER BY yaohe.publish_date DESC LIMIT :pageindex,:pagesize ';
        $conn = Yii::app()->db;
        $cmd = $conn->createCommand($sql);
        $cmd->bindParam(':lng', $lng);
        $cmd->bindParam(':lat', $lat);
        $cmd->bindParam(':radius', $radius);
        $cmd->bindParam(':last_time', $last_time);
        //$cmd->bindParam(':userid', $userID);
        $cmd->bindParam(':pageindex', $page_index);
        $cmd->bindParam(':pagesize', $pagesize);
        $reader = $cmd->query();
        $yaohes = $reader->readAll();

        if (isset($yaohes))
        {
            foreach ($yaohes as &$yaohe)
            {
                $yaohe['replyStatus'] = YaoheToUser::model()->getReplyStatus($userID,$yaohe['yaoheID']);
                $yaohe['context'] = busUlitity::hex2bin($yaohe['context']);
                $yaohe['userFrom'] = Userinfo::model()->getUsersByUserid($yaohe['userID'], $userID);
                $yaohe['usersTo'] = Userinfo::model()->getUsersByYaoheid($yaohe['yaoheID'], $userID);
                $yaohe['comments'] = UserComment::model()->getCommentsByYaoheid($yaohe['yaoheID'], $userID);
                //$yaohe['goods'] = Good::model()->getGoodsByYaoheid($yaohe['yaoheID']);
                $yaohe['goodCount'] = Good::model()->count('yaoheID = :yaoheID and status=1', array(':yaoheID' => $yaohe['yaoheID']));
                $goodBySelf = Good::model()->findByAttributes(array(), 'yaoheID = :yaoheID and status=1 and userFrom=:userFrom', array(':yaoheID' => $yaohe['yaoheID'], ':userFrom' => $userID));
                $yaohe['goodBySelf'] = isset($goodBySelf) ? 1 : 0;
            }
        }
        return $yaohes;
    }

}
