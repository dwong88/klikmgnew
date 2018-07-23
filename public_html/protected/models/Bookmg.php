<?php

/**
 * This is the model class for table "tghbookmg".
 *
 * The followings are the available columns in table 'tghbookmg':
 * @property string $booking_id
 * @property integer $user_id
 * @property string $resno
 * @property string $osrefno
 * @property string $hbookid
 * @property string $voucherno
 * @property string $voucherdt
 * @property string $status
 * @property string $hotelid
 * @property string $fromdt
 * @property string $todt
 * @property string $catgid
 * @property string $catgname
 * @property string $bftype
 * @property string $serviceno
 * @property string $roomtype
 * @property string $seqno
 * @property double $totalprice
 * @property string $guestname
 * @property string $update_dt
 * @property string $nightprice
 */
class Bookmg extends CActiveRecord
{
    public $property_name;

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
			array('user_id, resno, osrefno, hbookid, voucherno, voucherdt, status, hotelid, fromdt, todt, catgname, bftype, serviceno, roomtype, seqno, totalprice, update_dt', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('totalprice, nightprice', 'numerical'),
			array('resno, osrefno, hbookid, voucherno, hotelid, catgid, catgname, serviceno, roomtype', 'length', 'max'=>100),
			array('status', 'length', 'max'=>10),
			array('bftype', 'length', 'max'=>50),
			array('seqno', 'length', 'max'=>3),
			array('guestname', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('booking_id, user_id, resno, osrefno, hbookid, voucherno, voucherdt, status, hotelid, fromdt, todt, catgid, catgname, bftype, serviceno, roomtype, seqno, totalprice, guestname, update_dt', 'safe', 'on'=>'search'),
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
			'resno' => 'Res No',
			'osrefno' => 'Osref No',
			'hbookid' => 'Hbook',
			'voucherno' => 'Voucher No',
			'voucherdt' => 'Voucher Dt',
			'status' => 'status',
			'hotelid' => 'Hotel',
			'fromdt' => 'From Dt',
			'todt' => 'To Dt',
			'catgid' => 'Catg',
			'catgname' => 'Catg Name',
			'bftype' => 'bftype',
			'serviceno' => 'Service No',
			'roomtype' => 'Room Type',
			'seqno' => 'Seq No',
			'totalprice' => 'Total Price',
      'nightprice' => 'Night Price',
			'guestname' => 'Guest Name',
			'update_dt' => 'Booking time',
            'property_name'=> 'Hotel',
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
        $criteria->alias = 'tghbookmg';
        $criteria->select = "tghproperty.property_name,
                            tghbookmg.`booking_id`,
                            tghbookmg.`user_id`,
                            tghbookmg.`resno`,
                            tghbookmg.`osrefno`,
                            tghbookmg.`hbookid`,
                            tghbookmg.`voucherno`,
                            tghbookmg.`voucherdt`,
                            tghbookmg.`status`,
                            tghbookmg.`hotelid`,
                            tghbookmg.`fromdt`,
                            tghbookmg.`todt`,
                            tghbookmg.`catgid`,
                            tghbookmg.`catgname`,
                            tghbookmg.`bftype`,
                            tghbookmg.`serviceno`,
                            tghbookmg.`roomtype`,
                            tghbookmg.`seqno`,
                            tghbookmg.`totalprice`,
                            tghbookmg.`nightprice`,
                            tghbookmg.`guestname`,
                            tghbookmg.`update_dt`";

        $criteria->join = "JOIN tghproperty ON tghbookmg.`hotelid` = tghproperty.property_cd";

        $criteria->compare('tghbookmg.booking_id',$this->booking_id,true);
        $criteria->compare('tghbookmg.user_id',$this->user_id);
        $criteria->compare('tghbookmg.resno',$this->resno,true);
        $criteria->compare('tghbookmg.osrefno',$this->osrefno,true);
        $criteria->compare('tghbookmg.hbookid',$this->hbookid,true);
        $criteria->compare('tghbookmg.voucherno',$this->voucherno,true);
        $criteria->compare('tghbookmg.voucherdt',$this->voucherdt,true);
        $criteria->compare('tghbookmg.status',$this->status,true);
        $criteria->compare('tghbookmg.hotelid',$this->hotelid,true);
        $criteria->compare('tghbookmg.fromdt',$this->fromdt,true);
        $criteria->compare('tghbookmg.todt',$this->todt,true);
        $criteria->compare('tghbookmg.catgid',$this->catgid,true);
        $criteria->compare('tghbookmg.catgname',$this->catgname,true);
        $criteria->compare('tghbookmg.bftype',$this->bftype,true);
        $criteria->compare('tghbookmg.serviceno',$this->serviceno,true);
        $criteria->compare('tghbookmg.roomtype',$this->roomtype,true);
        $criteria->compare('tghbookmg.seqno',$this->seqno,true);
        $criteria->compare('tghbookmg.totalprice',$this->totalprice);
        $criteria->compare('tghbookmg.nightprice',$this->nightprice);
        $criteria->compare('tghbookmg.guestname',$this->guestname,true);
        $criteria->compare('tghbookmg.update_dt',$this->update_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array('defaultOrder'=>'tghbookmg.update_dt DESC')
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
