<?php

/**
 * This is the model class for table "t_usercomment".
 *
 * The followings are the available columns in table 't_usercomment':
 * @property integer $commentID
 * @property integer $yaoheID
 * @property integer $userFromID
 * @property integer $commentedID
 * @property string $content
 * @property string $createtime
 */
class UserComment extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_usercomment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('commentID', 'required'),
            array('commentID, yaoheID, userFromID, commentedID', 'numerical', 'integerOnly' => true),
            array('content, createtime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('commentID, yaoheID, userFromID, commentedID, content, createtime', 'safe', 'on' => 'search'),
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
            'commentID' => 'Comment',
            'yaoheID' => 'Yaohe',
            'userFromID' => 'User From',
            'commentedID' => 'Commented ID',
            'content' => 'Content',
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

        $criteria->compare('commentID', $this->commentID);
        $criteria->compare('yaoheID', $this->yaoheID);
        $criteria->compare('userFromID', $this->userFromID);
        $criteria->compare('commentedID', $this->commentedID);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('createtime', $this->createtime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserComment the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 查询yaoheid对应的用户信息
     * @param type $yaoheid
     * @return type
     */
    public function getCommentsByYaoheid($yaoheid,$userid)
    {
        $sql = 'SELECT uc.*,ui.email,ui.telphone,ui.usertype,ui.userName,ui.nickName,ui.userHead,ui.sex,
                (
                    SELECT friendName from t_friends
                    WHERE t_friends.userFrom=:curr_userid
                    AND t_friends.userTo=uc.userFromID LIMIT 1 
                ) as friendName 
                FROM t_usercomment as uc
                INNER JOIN t_userinfo as ui ON uc.userFromID = ui.userID 
                WHERE uc.yaoheID =:yaoheid and uc.commentID>493';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':yaoheid', $yaoheid);
        $cmd->bindParam(':curr_userid', $userid);
        $reader = $cmd->query();
        $ary = $reader->readAll();
        //var_dump($ary);
        foreach ($ary as &$value)
        {
            $value['content'] = busUlitity::hex2bin($value['content']);
//            try
//            {
//                $len = strlen($value['content']);
//                if ($len % 2 != 0)
//                {
//                    $value['content'] = hex2bin($value['content']);
//                }
//            } catch (Exception $e)
//            {
//                Yii::log($e->getMessage());
//            }
        }

        return $ary;
    }

}
