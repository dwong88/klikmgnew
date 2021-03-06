<?php

/**
 * This is the model class for table "tdpusergroupdetail".
 *
 * The followings are the available columns in table 'tdpusergroupdetail':
 * @property integer $usergroupl_id
 * @property integer $user_id
 * @property integer $usergroup_id
 */
class Usergroupdetail extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Usergroupdetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tdpusergroupdetail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, usergroup_id', 'required'),
			array('user_id, usergroup_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('usergroupl_id, user_id, usergroup_id', 'safe', 'on'=>'search'),
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
			'users' => array(self::BELONGS_TO, 'User', 'user_id','together'=>true)
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'usergroupl_id' => 'Usergroupl',
			'user_id' => 'User',
			'usergroup_id' => 'Usergroup',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('usergroupl_id',$this->usergroupl_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('usergroup_id',$this->usergroup_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}