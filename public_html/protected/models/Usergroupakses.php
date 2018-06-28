<?php

/**
 * This is the model class for table "tdpusergroupakses".
 *
 * The followings are the available columns in table 'tdpusergroupakses':
 * @property integer $usergroupakses_id
 * @property integer $usergroup_id
 * @property integer $menuaction_id
 */
class Usergroupakses extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Usergroupakses the static model class
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
		return 'tdpusergroupakses';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('usergroup_id, menuaction_id', 'required'),
			array('usergroup_id, menuaction_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('usergroupakses_id, usergroup_id, menuaction_id', 'safe', 'on'=>'search'),
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
			'menuaction' => array(self::BELONGS_TO, 'Menuaction', array('menuaction_id'=>'menuaction_id'),'joinType'=>'INNER JOIN'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'usergroupakses_id' => 'Usergroupakses',
			'usergroup_id' => 'Usergroup',
			'menuaction_id' => 'Menuaction',
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

		$criteria->compare('usergroupakses_id',$this->usergroupakses_id);
		$criteria->compare('usergroup_id',$this->usergroup_id);
		$criteria->compare('menuaction_id',$this->menuaction_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}