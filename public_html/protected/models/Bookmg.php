<?php

/**
 * This is the model class for table "tghbookmg".
 *
 * The followings are the available columns in table 'tghbookmg':
 * @property string $booking_id
 * @property integer $user_id
 * @property string $ResNo
 * @property string $OSRefNo
 * @property string $HBookId
 * @property string $VoucherNo
 * @property string $VoucherDt
 * @property string $Status
 * @property string $HotelId
 * @property string $FromDt
 * @property string $ToDt
 * @property string $CatgId
 * @property string $CatgName
 * @property string $BFType
 * @property string $ServiceNo
 * @property string $RoomType
 * @property string $SeqNo
 * @property double $TotalPrice
 * @property string $GuestName
 * @property string $update_dt
 */
class Bookmg extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tghbookmg';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, ResNo, OSRefNo, HBookId, VoucherNo, VoucherDt, Status, HotelId, FromDt, ToDt, CatgName, BFType, ServiceNo, RoomType, SeqNo, TotalPrice, update_dt', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('TotalPrice', 'numerical'),
			array('ResNo, OSRefNo, HBookId, VoucherNo, HotelId, CatgId, CatgName, ServiceNo, RoomType', 'length', 'max'=>100),
			array('Status', 'length', 'max'=>10),
			array('BFType', 'length', 'max'=>50),
			array('SeqNo', 'length', 'max'=>3),
			array('GuestName', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('booking_id, user_id, ResNo, OSRefNo, HBookId, VoucherNo, VoucherDt, Status, HotelId, FromDt, ToDt, CatgId, CatgName, BFType, ServiceNo, RoomType, SeqNo, TotalPrice, GuestName, update_dt', 'safe', 'on'=>'search'),
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
			'booking_id' => 'Booking',
			'user_id' => 'User',
			'ResNo' => 'Res No',
			'OSRefNo' => 'Osref No',
			'HBookId' => 'Hbook',
			'VoucherNo' => 'Voucher No',
			'VoucherDt' => 'Voucher Dt',
			'Status' => 'Status',
			'HotelId' => 'Hotel',
			'FromDt' => 'From Dt',
			'ToDt' => 'To Dt',
			'CatgId' => 'Catg',
			'CatgName' => 'Catg Name',
			'BFType' => 'Bftype',
			'ServiceNo' => 'Service No',
			'RoomType' => 'Room Type',
			'SeqNo' => 'Seq No',
			'TotalPrice' => 'Total Price',
			'GuestName' => 'Guest Name',
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

		$criteria->compare('booking_id',$this->booking_id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('ResNo',$this->ResNo,true);
		$criteria->compare('OSRefNo',$this->OSRefNo,true);
		$criteria->compare('HBookId',$this->HBookId,true);
		$criteria->compare('VoucherNo',$this->VoucherNo,true);
		$criteria->compare('VoucherDt',$this->VoucherDt,true);
		$criteria->compare('Status',$this->Status,true);
		$criteria->compare('HotelId',$this->HotelId,true);
		$criteria->compare('FromDt',$this->FromDt,true);
		$criteria->compare('ToDt',$this->ToDt,true);
		$criteria->compare('CatgId',$this->CatgId,true);
		$criteria->compare('CatgName',$this->CatgName,true);
		$criteria->compare('BFType',$this->BFType,true);
		$criteria->compare('ServiceNo',$this->ServiceNo,true);
		$criteria->compare('RoomType',$this->RoomType,true);
		$criteria->compare('SeqNo',$this->SeqNo,true);
		$criteria->compare('TotalPrice',$this->TotalPrice);
		$criteria->compare('GuestName',$this->GuestName,true);
		$criteria->compare('update_dt',$this->update_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Bookmg the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
