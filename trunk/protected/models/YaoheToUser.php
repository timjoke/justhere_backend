<?php

/**
 * This is the model class for table "t_yaohetouser".
 *
 * The followings are the available columns in table 't_yaohetouser':
 * @property integer $ID
 * @property integer $yaoheID
 * @property integer $userTo
 * @property integer $replyStatus
 */
class YaoheToUser extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_yaohetouser';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ID', 'required'),
            array('ID, yaoheID, userTo, replyStatus', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('ID, yaoheID, userTo, replyStatus', 'safe', 'on' => 'search'),
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
            'ID' => 'ID',
            'yaoheID' => 'Yaohe',
            'userTo' => 'User To',
            'replyStatus' => 'Reply Status',
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

        $criteria->compare('ID', $this->ID);
        $criteria->compare('yaoheID', $this->yaoheID);
        $criteria->compare('userTo', $this->userTo);
        $criteria->compare('replyStatus', $this->replyStatus);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return YaoheToUser the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     *  判断用户有没有评论过给该用户的信息
     * @param type $userid
     * @param type $yaoheid
     * @return boolean
     */
    public function isFirstCommentByUserid($userid, $yaoheid)
    {
        $sql = 'SELECT *,
            (
                SELECT uc.userFromID FROM t_usercomment as uc 
                WHERE ytu.yaoheID = uc.yaoheID AND uc.userFromID=:userid LIMIT 1
            ) as userFrom 
            FROM t_yaohetouser as ytu 
            WHERE ytu.yaoheID = :yaoheid and ytu.userTo = :userid and ytu.replyStatus = 1 ';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':userid', $userid);
        $cmd->bindParam(':yaoheid', $yaoheid);
        //$cmd->bindParam(':replyStatus', 1);
        $reader = $cmd->query();
        $ary = $reader->readAll();
        if (isset($ary) && count($ary) > 0)
        {
            return true;
        }
        return false;
    }
    
    /**
     *  查询吆喝信息的回复状态
     * @param type $userid
     * @param type $yaoheid
     * @return 
     */
    public function getReplyStatus($userid, $yaoheid)
    {
        $sql = 'SELECT replyStatus  
            FROM t_yaohetouser 
            WHERE yaoheID = :yaoheid and userTo = :userid';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':userid', $userid);
        $cmd->bindParam(':yaoheid', $yaoheid);
        //$cmd->bindParam(':replyStatus', 1);
        $reader = $cmd->query();
        $ary = $reader->readAll();
        if (isset($ary) && count($ary) > 0)
        {
            return $ary[0]["replyStatus"];
        }
        return "0";
    }

}
