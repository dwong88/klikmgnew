<?php

/**
 * This is the model class for table "tghsearchsession".
 *
 * The followings are the available columns in table 'tghsearchsession':
 * @property string $session_id
 * @property string $nationalities
 * @property string $currency
 * @property string $location_code
 * @property integer $location_type
 * @property string $check_in
 * @property string $check_out
 * @property integer $jumlah_kamar
 * @property integer $jumlah_tamu
 * @property string $update_dt
 */
class Searchsession extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tghsearchsession';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('currency, location_code, location_type, check_in, check_out, jumlah_kamar, jumlah_tamu', 'required'),
			array('location_type, jumlah_kamar, jumlah_tamu', 'numerical', 'integerOnly'=>true),
			array('nationalities', 'length', 'max'=>20),
			array('currency', 'length', 'max'=>5),
			array('location_code', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('session_id, nationalities, currency, location_code, location_type, check_in, check_out, jumlah_kamar, jumlah_tamu, update_dt', 'safe', 'on'=>'search'),
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
			'session_id' => 'Session',
			'nationalities' => 'Nationalities',
			'currency' => 'Currency',
			'location_code' => 'Location Code',
			'location_type' => 'Location Type',
			'check_in' => 'Check In',
			'check_out' => 'Check Out',
			'jumlah_kamar' => 'Jumlah Kamar',
			'jumlah_tamu' => 'Jumlah Tamu',
			'update_dt' => 'Update Dt',
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

		$criteria->compare('session_id',$this->session_id,true);
		$criteria->compare('nationalities',$this->nationalities,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('location_code',$this->location_code,true);
		$criteria->compare('location_type',$this->location_type);
		$criteria->compare('check_in',$this->check_in,true);
		$criteria->compare('check_out',$this->check_out,true);
		$criteria->compare('jumlah_kamar',$this->jumlah_kamar);
		$criteria->compare('jumlah_tamu',$this->jumlah_tamu);
		$criteria->compare('update_dt',$this->update_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Searchsession the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
