<?php

/**
 * This is the model class for table "t_telcontact".
 *
 * The followings are the available columns in table 't_telcontact':
 * @property integer $contact_id
 * @property integer $user_contact_id
 * @property string $contact_name
 * @property string $contact_tel
 * @property string $contact_email
 * @property integer $contact_status
 * @property integer $contact_user_id
 */
class Telcontact extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_telcontact';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_contact_id, contact_status, contact_user_id', 'numerical', 'integerOnly'=>true),
			array('contact_name', 'length', 'max'=>30),
			array('contact_tel', 'length', 'max'=>12),
			array('contact_email', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('contact_id, user_contact_id, contact_name, contact_tel, contact_email, contact_status, contact_user_id', 'safe', 'on'=>'search'),
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
			'contact_id' => 'Contact',
			'user_contact_id' => 'User Contact',
			'contact_name' => 'Contact Name',
			'contact_tel' => 'Contact Tel',
			'contact_email' => 'Contact Email',
			'contact_status' => 'Contact Status',
			'contact_user_id' => 'Contact User',
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

		$criteria->compare('contact_id',$this->contact_id);
		$criteria->compare('user_contact_id',$this->user_contact_id);
		$criteria->compare('contact_name',$this->contact_name,true);
		$criteria->compare('contact_tel',$this->contact_tel,true);
		$criteria->compare('contact_email',$this->contact_email,true);
		$criteria->compare('contact_status',$this->contact_status);
		$criteria->compare('contact_user_id',$this->contact_user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Telcontact the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getContactByUserid($userid)
        {
            $sql = 'SELECT contact_id,contact_name as name, '
                    . 'contact_tel as tel,contact_email as email, '
                    . 'contact_status as status,contact_user_id as userid '
                    . ' FROM t_telcontact where user_contact_id=:userid';
            $cmd = Yii::app()->db->createCommand($sql);
            $cmd->bindParam(':userid', $userid);
            $reader = $cmd->query();
            $arr = $reader->readAll();
            return $arr;
        }
}
