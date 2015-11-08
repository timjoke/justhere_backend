<?php

/**
 * This is the model class for table "t_report".
 *
 * The followings are the available columns in table 't_report':
 * @property integer $reportID
 * @property integer $userID
 * @property integer $yaoheID
 * @property string $reason
 * @property string $reportTime
 * @property integer $reportStatus
 */
class Report extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_report';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reportID', 'required'),
			array('reportID, userID, yaoheID, reportStatus', 'numerical', 'integerOnly'=>true),
			array('reason', 'length', 'max'=>200),
			array('reportTime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('reportID, userID, yaoheID, reason, reportTime, reportStatus', 'safe', 'on'=>'search'),
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
			'reportID' => 'Report',
			'userID' => 'User',
			'yaoheID' => 'Yaohe',
			'reason' => 'Reason',
			'reportTime' => 'Report Time',
			'reportStatus' => 'Report Status',
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

		$criteria=new CDbCriteria;

		$criteria->compare('reportID',$this->reportID);
		$criteria->compare('userID',$this->userID);
		$criteria->compare('yaoheID',$this->yaoheID);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('reportTime',$this->reportTime,true);
		$criteria->compare('reportStatus',$this->reportStatus);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Report the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
