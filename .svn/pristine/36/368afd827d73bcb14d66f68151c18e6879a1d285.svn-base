<?php

/**
 * This is the model class for table "t_friends".
 *
 * The followings are the available columns in table 't_friends':
 * @property integer $friendID
 * @property integer $userFrom
 * @property integer $userTo
 * @property string $friendName
 * @property integer $status
 * @property integer $invoker
 */
class Friends extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_friends';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('friendID', 'required'),
			array('friendID, userFrom, userTo,invoker', 'numerical', 'integerOnly'=>true),
			array('friendName', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('friendID, userFrom, userTo, friendName,status,invoker', 'safe', 'on'=>'search'),
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
			'friendID' => 'Friend',
			'userFrom' => 'User From',
			'userTo' => 'User To',
			'friendName' => 'Friend Name',
                        'status' =>'Status',
                        'invoker' => 'Invoker'
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

		$criteria->compare('friendID',$this->friendID);
		$criteria->compare('userFrom',$this->userFrom);
		$criteria->compare('userTo',$this->userTo);
		$criteria->compare('friendName',$this->friendName,true);
                $criteria->compare('status',$this->status);
                $criteria->compare('invoker', $this->invoker);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Friends the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
