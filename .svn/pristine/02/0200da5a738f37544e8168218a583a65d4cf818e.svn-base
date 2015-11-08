<?php

/**
 * This is the model class for table "t_apicount".
 *
 * The followings are the available columns in table 't_apicount':
 * @property integer $countID
 * @property integer $method
 * @property integer $userID
 * @property string $createtime
 * @property integer $todayCount
 */
class Apicount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_apicount';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('countID', 'required'),
			array('countID, method, userID, todayCount', 'numerical', 'integerOnly'=>true),
			array('createtime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('countID, method, userID, createtime, todayCount', 'safe', 'on'=>'search'),
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
			'countID' => 'Count',
			'method' => 'Method',
			'userID' => 'User',
			'createtime' => 'Createtime',
			'todayCount' => 'Today Count',
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

		$criteria->compare('countID',$this->countID);
		$criteria->compare('method',$this->method);
		$criteria->compare('userID',$this->userID);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('todayCount',$this->todayCount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Apicount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
