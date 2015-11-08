<?php

/**
 * This is the model class for table "t_question".
 *
 * The followings are the available columns in table 't_question':
 * @property integer $questionID
 * @property string $questionDesc
 * @property integer $userID
 * @property string $deviceInfo
 * @property string $osInfo
 * @property string $appInfo
 * @property string $createtime
 */
class Question extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_question';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('questionID, userID', 'numerical', 'integerOnly' => true),
            array('questionDesc', 'length', 'max' => 800),
            array('deviceInfo', 'length', 'max' => 100),
            array('osInfo', 'length', 'max' => 20),
            array('appInfo', 'length', 'max' => 15),
            array('createtime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('questionID, questionDesc, userID, deviceInfo, osInfo, appInfo, createtime', 'safe', 'on' => 'search'),
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
            'questionID' => 'Question',
            'questionDesc' => 'Question Desc',
            'userID' => 'User',
            'deviceInfo' => 'Device Info',
            'osInfo' => 'Os Info',
            'appInfo' => 'App Info',
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

        $criteria->compare('questionID', $this->questionID);
        $criteria->compare('questionDesc', $this->questionDesc, true);
        $criteria->compare('userID', $this->userID);
        $criteria->compare('deviceInfo', $this->deviceInfo, true);
        $criteria->compare('osInfo', $this->osInfo, true);
        $criteria->compare('appInfo', $this->appInfo, true);
        $criteria->compare('createtime', $this->createtime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Question the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getAll($start_time)
    {
        if(!isset($start_time))
        {
            $start_time = "2014-10-01 00:00:00";
        }
        $sql = 'SELECT ques.*,uf.userName  
                FROM t_question as ques
                LEFT JOIN t_userinfo as uf ON uf.userID=ques.userID  
                WHERE ques.createtime>:createtime   
                ORDER BY ques.createtime DESC';

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindParam(":createtime", $start_time);
        $reader = $cmd->query();
        $ary = $reader->readAll();

        return $ary;
    }

}
