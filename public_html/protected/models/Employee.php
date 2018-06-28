<?php

/**
 * This is the model class for table "tdpemployee".
 *
 * The followings are the available columns in table 'tdpemployee':
 * @property string $employee_cd
 * @property integer $user_id
 * @property string $employee_name
 * @property string $employee_type
 * @property integer $company_id
 * @property integer $create_by
 * @property string $create_dt
 * @property integer $update_by
 * @property string $update_dt
 */
class Employee extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Employee the static model class
	 */
	public function __construct($scenario = 'insert')
	{
		parent::__construct($scenario);
		$this->logRecord=true;
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tdpemployee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_cd, user_id, employee_name, employee_type, company_id', 'required'),
			array('user_id, company_id, create_by, update_by', 'numerical', 'integerOnly'=>true),
			array('employee_cd, employee_type', 'length', 'max'=>50),
			array('email', 'length', 'max'=>200),
			array('email', 'email'),
			array('employee_name', 'length', 'max'=>255),
			array('update_dt', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('employee_cd, user_id, employee_name, employee_type, email, company_id, create_by, create_dt, update_by, update_dt', 'safe', 'on'=>'search'),
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
			'employee_cd' => 'Employee Cd',
			'user_id' => 'User',
			'employee_name' => 'Employee Name',
			'employee_type' => 'Employee Type',
			'company_id' => 'Company',
			'email' => 'Email',
			'create_by' => 'Create By',
			'create_dt' => 'Create Dt',
			'update_by' => 'Update By',
			'update_dt' => 'Update Dt',
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

		$criteria->compare('employee_cd',$this->employee_cd,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('employee_name',$this->employee_name,true);
		$criteria->compare('employee_type',$this->employee_type,true);
		$criteria->compare('email',$this->email);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('create_by',$this->create_by);
		$criteria->compare('create_dt',$this->create_dt,true);
		$criteria->compare('update_by',$this->update_by);
		$criteria->compare('update_dt',$this->update_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}