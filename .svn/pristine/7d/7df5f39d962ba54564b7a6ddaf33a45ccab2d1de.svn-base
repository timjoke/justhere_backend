<?php

/**
 * This is the model class for table "t_locationlog".
 *
 * The followings are the available columns in table 't_locationlog':
 * @property integer $locID
 * @property integer $userID
 * @property string $longitude
 * @property string $latitude
 * @property string $address
 * @property string $createtime
 * @property string $createtime1
 */
class LocationLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_locationlog';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userID', 'numerical', 'integerOnly'=>true),
			array('longitude, latitude', 'length', 'max'=>11),
			array('createtime, createtime1', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('locID, userID, longitude, latitude,address, createtime, createtime1', 'safe', 'on'=>'search'),
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
			'locID' => 'Loc',
			'userID' => 'User',
			'longitude' => 'Longitude',
			'latitude' => 'Latitude',
			'createtime' => 'Createtime',
			'createtime1' => 'createtime1',
                        'address'=> 'address'
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

		$criteria->compare('locID',$this->locID);
		$criteria->compare('userID',$this->userID);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('latitude',$this->latitude,true);
                $criteria->compare('address',$this->address,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('createtime1',$this->createtime1,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LocationLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
