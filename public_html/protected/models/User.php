<?php

/**
 * This is the model class for table "tdpuser".
 *
 * The followings are the available columns in table 'tdpuser':
 * @property integer $user_id
 * @property string $user_name
 * @property string $password
 * @property string $user_type
 * @property integer $is_active
 * @property string $mail_provider
 * @property string $mail_email
 * @property string $mail_ps
 * @property integer $create_by
 * @property string $create_dt
 * @property integer $update_by
 * @property string $update_dt
 */
class User extends ActiveRecord
{
	public $oldpass = '';
	public $newpass = '';
	public $reenterpass = '';
	public $updateSuccessfull = 0;
	public $mailpass='';
	
	public function __construct($scenario = 'insert')
	{
		parent::__construct($scenario);
		$this->logRecord=true;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'tdpuser';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_name, newpass, reenterpass, user_type, is_active', 'required', 'on'=>'insertadm,insert'),
			array('user_name','unique','on'=>'insertadm,insert'),
			array('user_name','length','min'=>3,'max'=>20,'on'=>'insertadm,insert'),
			array('user_type, is_active', 'required', 'on'=>'updateadm,insert'),
			array('user_type','match','pattern'=>'/^(USER|SUPER|ADMIN){1}$/','on'=>'insertadm,updateadm'),
			array('is_active', 'numerical', 'integerOnly'=>true, 'on'=>'insertadm,updateadm'),
			array('user_type', 'length', 'max'=>10, 'on'=>'insertadm,updateadm'),
			array('newpass','length','min'=>6, 'max'=>20,'on'=>'insertadm,changepassword,insert,changepassadm'),
			array('newpass', 'compare', 'on'=>'insertadm,changepassword,changepassadm,insert', 'compareAttribute'=>'reenterpass', 'allowEmpty'=>true),
			array('newpass,reenterpass','required','on'=>'changepassadm'),
			array('oldpass, newpass, reenterpass','required','on'=>'changepassword'),
			array('mail_email', 'length', 'max'=>100, 'on'=>'insertadm,updateadm'),
			array('mail_provider', 'length', 'max'=>15, 'on'=>'insertadm,updateadm'),
			array('mail_email','email', 'on'=>'insertadm,updateadm'),
			array('mailpass', 'length', 'max'=>50, 'on'=>'insertadm,updateadm'),
			array('mailpass','checkpassemail', 'on'=>'insertadm,updateadm'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, user_name, password, user_type, is_active, create_by, create_dt, update_by, update_dt', 'safe', 'on'=>'search'),
		);
	}

	public function checkpassemail($attribute,$params)
	{
		if( !empty($this->mailpass) ) {
			$this->mail_ps = Encryption::securityManagerEncrypt($this->mailpass);
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'employee' => array(self::BELONGS_TO, 'Employee', 'user_id'),
			'usergroupdetail' => array(self::HAS_MANY, 'Usergroupdetail', 'user_id','together'=>true,'order'=>'t.user_name ASC')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'ID User',
			'user_name' => 'Nama User',
			'password' => 'Password',
			'user_type' => 'Tipe User',
			'is_active' => 'Aktif',
			'mail_provider' => 'Email Provider',
			'mail_email' => 'Email',
			'mailpass' => 'Email Pass.',
			'create_by' => 'Dibuat Oleh',
			'create_dt' => 'Dibuat Tanggal',
			'update_by' => 'Diubah Oleh',
			'update_dt' => 'Diubah Tanggal',
			'oldpass' => 'Password Lama',
			'newpass' => 'Password Baru',
			'reenterpass' => 'Konfirmasi Password Baru',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('user_type',$this->user_type,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('create_by',$this->create_by);
		$criteria->compare('create_dt',$this->create_dt,true);
		$criteria->compare('update_by',$this->update_by);
		$criteria->compare('update_dt',$this->update_dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}