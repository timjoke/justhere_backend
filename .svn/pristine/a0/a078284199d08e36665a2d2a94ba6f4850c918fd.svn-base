<?php

/**
 * This is the model class for table "t_appversioninfo".
 *
 * The followings are the available columns in table 't_appversioninfo':
 * @property integer $verid
 * @property string $appversion
 * @property string $appfeatures
 * @property string $createtime
 * @property integer $baseNum
 * @property integer $slave1Num
 * @property string $packagename
 * @property integer $ostype
 */
class AppVersionInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_appversioninfo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('appversion', 'required'),
			array('baseNum, slave1Num, ostype', 'numerical', 'integerOnly'=>true),
			array('appversion', 'length', 'max'=>15),
			array('appfeatures, packagename', 'length', 'max'=>255),
			array('createtime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('verid, appversion, appfeatures, createtime, baseNum, slave1Num, packagename, ostype', 'safe', 'on'=>'search'),
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
			'verid' => 'Verid',
			'appversion' => 'Appversion',
			'appfeatures' => 'Appfeatures',
			'createtime' => 'Createtime',
			'baseNum' => 'Base Num',
			'slave1Num' => 'Slave1 Num',
			'packagename' => 'Packagename',
			'ostype' => 'Ostype',
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

		$criteria->compare('verid',$this->verid);
		$criteria->compare('appversion',$this->appversion,true);
		$criteria->compare('appfeatures',$this->appfeatures,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('baseNum',$this->baseNum);
		$criteria->compare('slave1Num',$this->slave1Num);
		$criteria->compare('packagename',$this->packagename,true);
		$criteria->compare('ostype',$this->ostype);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppVersionInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
