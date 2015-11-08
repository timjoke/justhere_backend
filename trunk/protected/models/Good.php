<?php

/**
 * This is the model class for table "t_good".
 *
 * The followings are the available columns in table 't_good':
 * @property integer $goodID
 * @property integer $yaoheID
 * @property integer $userFrom
 * @property integer $status
 */
class Good extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_good';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('yaoheID, userFrom,status', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('goodID, yaoheID, userFrom,status', 'safe', 'on' => 'search'),
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
            'goodID' => 'Good',
            'yaoheID' => 'Yaohe',
            'userFrom' => 'User From',
            'status'=> 'Status'
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

        $criteria->compare('goodID', $this->goodID);
        $criteria->compare('yaoheID', $this->yaoheID);
        $criteria->compare('userFrom', $this->userFrom);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Good the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 获取吆喝的赞用户
     * @param type $yaoheid
     * @return type
     */
    public function getGoodsByYaoheid($yaoheid)
    {
        $sql = 'SELECT good.*,ui.email,ui.telphone,ui.userName,ui.nickName,ui.userHead 
                FROM t_good as good
                INNER JOIN t_userinfo as ui ON good.userFrom = ui.userID 
                WHERE good.yaoheID =:yaoheid and good.status=1';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(':yaoheid', $yaoheid);
        $reader = $cmd->query();
        $ary = $reader->readAll();

        return $ary;
    }
}
