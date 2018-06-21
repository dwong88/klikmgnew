<?php

/**
 * This is the model class for table "tghsearchlocation".
 *
 * The followings are the available columns in table 'tghsearchlocation':
 * @property integer $location_type
 * @property string $location_code
 * @property string $location_name
 * @property string $market_name
 * @property string $update_dt
 */
class Searchlocation extends ActiveRecord
{
    public static $location_type = array('1'=>'Near By','2'=>'Hotel','3'=>'City','4'=>'Country');

    const LOCATION_TYPE_NEARBY = 1;
    const LOCATION_TYPE_HOTEL = 2;
    const LOCATION_TYPE_CITY = 3;
    const LOCATION_TYPE_COUNTRY = 4;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
    {
		return 'tghsearchlocation';
	}

  public function __construct($scenario = 'insert')
    {
        parent::__construct($scenario);
        $this->logRecord=true;
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('location_type, location_code, market_name, update_dt', 'required'),
			array('location_type', 'numerical', 'integerOnly'=>true),
			array('location_code', 'length', 'max'=>50),
			array('location_name, market_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('location_type, location_code, location_name, market_name, update_dt', 'safe', 'on'=>'search'),
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
			'location_type' => 'Location Type',
			'location_code' => 'Location Code',
			'location_name' => 'Location Name',
			'market_name' => 'Market Name',
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

		$criteria->compare('location_type',$this->location_type);
		$criteria->compare('location_code',$this->location_code,true);
		$criteria->compare('location_name',$this->location_name,true);
		$criteria->compare('market_name',$this->market_name,true);
		$criteria->compare('update_dt',$this->update_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Searchlocation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
