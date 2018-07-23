<?php
/**
 * Created by PhpStorm.
 * User: wiyantotan
 * Date: 27/06/18
 * Time: 16:34
 */

class SearchHotelForm extends CFormModel
{
    public $nationalities;
    public $currency;
    public $destination;
    public $checkin_dt;
    public $duration;
    public $tamu;
    public $kamar;
    public $locationCode;
    public $locationType;
    public $locationCountry;
    public $locationCity;
    public $locationName;
    public $displayResult = false;

    public function rules()
    {
        return array(
            array('checkin_dt', 'application.components.validator.DatePickerSwitcherValidator'),
            array('nationalities, currency, destination, checkin_dt, duration, tamu, kamar', 'required'),
            array('tamu, kamar', 'numerical', 'integerOnly'=>true, 'min'=>1),
            array('tamu','gtkamar'),
            array('destination','cekdest'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'checkin_dt' => 'Check In',
            'tamu' => 'No of Guest(s)',
            'kamar' => 'No of Room(s)'
        );
    }

    public function gtkamar($attribute,$params)
    {
        if(!$this->hasErrors()) {
            if($this->tamu < $this->kamar) {
                $this->addError('tamu', 'Jumlah tamu harus lebih besar atau sama dengan jumlah kamar');
            }
        }
    }

    public function cekdest($attribute,$params)
    {
        if(!$this->hasErrors()) {
            $arrLoc = explode(":",  $this->destination);
            $location_type = $arrLoc[0];
            $location_code = $arrLoc[1];

            $qCek = DAO::queryRowSql('SELECT location_name 
                                    FROM tghsearchlocation 
                                    WHERE location_type = :typ 
                                    AND location_code = :cod'
                                    , array(':typ'=>$location_type, ':cod'=>$location_code));

            if($qCek === false) {
                $this->addError('destination', 'Silahkan pilih destinasi yang benar');
            } else {
                $this->locationName = $qCek['location_name'];
                $qLocation = false;
                $tp = intval($location_type);
                switch ($tp) {
                    case Searchlocation::LOCATION_TYPE_CITY:
                        $qLocation = DAO::queryRowSql('SELECT country_cd, city_cd
                                                        FROM tghcitymg
                                                        WHERE city_cd = :ccd '
                            , array(':ccd'=>$location_code));
                        break;
                    case Searchlocation::LOCATION_TYPE_HOTEL:
                        $qLocation = DAO::queryRowSql('SELECT country_cd, city_cd
                                                        FROM tghproperty
                                                        WHERE property_cd = :pcd '
                            , array(':pcd'=>$location_code));
                        break;
                }


                if($qLocation === false) {
                    $this->addError('destination', 'Destinasi tidak terdaftar');
                } else {
                    $this->locationCode = $location_code;
                    $this->locationType = $location_type;
                    $this->locationCity = $qLocation['city_cd'];
                    $this->locationCountry = $qLocation['country_cd'];
                }
            }
        }
    }
}