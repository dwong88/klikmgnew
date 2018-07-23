<?php
/**
 * Created by PhpStorm.
 * User: wiyantotan
 * Date: 30/05/18
 * Time: 19:07
 */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class HoteldataController extends Controller
{
    public $layout='//layouts/column1';
    public function actionGethoteldetail($hotelCode)
    {
        $myFacility='';
      if(isset($_GET['hotelCode']))
      {
        $hotelCode = $_GET['hotelCode'];
        $hotelsdata = DAO::queryAllSql("SELECT property_cd
                                              FROM tghsearchprice group BY property_cd  ");
        /*foreach ($hotelsdata as $listdata) {
           $hotelCode = $listdata['property_cd'];
        */
        $xmlRequest = $this->renderPartial('req_gethoteldetail'
              , array(
                  'hotelCode'=>$hotelCode
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_HOTEL_DETAIL, $xmlRequest);
          $FacilityList='';
          $hotelList = json_decode($jsonResult,TRUE)['GetHotelDetail_Response'];
          if(isset($hotelList['Facility'])){
              $FacilityList = json_decode($jsonResult,TRUE)['GetHotelDetail_Response']['Facility'];
          }

          //print_r($FacilityList);


          /*$hotelsdata = DAO::queryAllSql("SELECT property_cd
                                                FROM tghsearchprice group BY property_cd  ");

          //print_r($hotelsdata);
          $feat='';
          $msfeat='';
          $feat = DAO::queryAllSql("SELECT name
                                                FROM tghtempfacility group BY name  ");
          //print_r($feat);

          $msfeat = DAO::queryAllSql("SELECT features_name,prop_features_id FROM tghmspropertyfeatures");
          //print_r($msfeat);
          if(!empty($FacilityList)){
            foreach ($FacilityList as $listfacility) {
                //echo $listfacility['@attributes']['name'].'<br>';
                foreach ($msfeat as $listmsfeat) {
                    if($listfacility['@attributes']['name']==$listmsfeat['features_name']){
                      //echo $listmsfeat['prop_features_id'].'<br>';
                      //echo ("INSERT INTO tghpropertyfeatures(property_id,prop_features_id) VALUES ('" . $hotelCode . "','" . $listmsfeat['prop_features_id'] . "')");
                      DAO::executeSql("INSERT INTO tghpropertyfeatures(property_id,prop_features_id) VALUES ('" . $hotelCode . "','" . $listmsfeat['prop_features_id'] . "')");
                    }

                }
            }
          }

          $now=date('Y-m-d H:i:s');

                  foreach ($feat as $listfeat) {
                      //echo $listfeat['name'];
                      //DAO::executeSql("INSERT INTO tghmspropertyfeatures(features_name,create_dt,create_by,update_dt,update_by) VALUES ('" . $listfeat['name'] . "','$now',1,'$now',1)");
                      /*if ($listfeat['name'] == $listmsfeat['features_name']) {
                          echo "string";
                      }
                      else{
                          //DAO::executeSql("INSERT INTO tghmspropertyfeatures(features_name,create_dt,create_by,update_dt,update_by) VALUES ('" . $listfeat['name'] . "','$now',1,'$now',1)");
                      }
                  }*/



          $temp_fc=array();
          if($FacilityList!=NULL){
            foreach ($FacilityList as $listFac) {
              $temp_fc[] = $listFac['@attributes']['name'];
            }
          }

          $myJSON = json_encode($hotelList);
          $myFacility = json_encode($temp_fc);
        //}
      }
      $this->render('form_gethoteldetail',array(
              'hotelCode'=>$hotelCode,
              'myJSON'=>$myJSON,
              'myFacility'=>$myFacility
      ));
    }

    public function actionViewhoteldetail($hotelCode,$checkIn,$checkOut,$AdultNum,$NumRooms, $national, $currency)
    {
      if(isset($_GET['hotelCode']))
      {
        //$hotelCode = $_POST['hotelCode'];
        $hotelCode = $_GET['hotelCode'];
        $flagAvail =1;
        //Yii::app()->end();

        if ($NumRooms > 1) {
            $avgAdultNumInRoom = ceil($AdultNum / $NumRooms);
            $arrRooms = array();
            for ($i = 0; $i < $NumRooms; $i++) {
                $arrRooms[] = array('numAdults' => $avgAdultNumInRoom);
                //echo "kamar isi";
                //echo $arrRooms[$i]['numAdults'];
            }
            $hitungkamar=count($arrRooms);
            for($kr=0;$kr<$hitungkamar;$kr++){
              if($arrRooms[$kr]['numAdults']==1){
                $RoomType = 'Single';
              }
              if($arrRooms[$kr]['numAdults']==2){
                $RoomType = 'Twin';
              }
              if($arrRooms[$kr]['numAdults']==3){
                $RoomType = 'Triple';
              }
              if($arrRooms[$kr]['numAdults']==4){
                $RoomType = 'Quad';
              }
              $hotels = DAO::queryAllSql("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.roomprice,
                  tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.roomtype as RoomType,
                  tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.bftype,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
                                                      FROM tghsearchprice
                                          INNER JOIN tghproperty
                                                      ON  tghproperty.property_cd = tghsearchprice.property_cd
                                                      WHERE tghsearchprice.property_cd ='".$hotelCode."'
                                                      AND tghsearchprice.check_in = '".$checkIn."'
                                                      AND tghsearchprice.check_out = '".$checkOut."'
                                                      AND tghsearchprice.roomtype = '".$RoomType."'
                                                      GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType,tghsearchprice.bftype
                                                      ");
            }

        }else{
              $arrRooms[] = array('numAdults' => $AdultNum);
              //echo "kamar isi";
              //echo $arrRooms[0]['numAdults'];
              if($arrRooms[0]['numAdults']==1){
                $RoomType = 'Single';
              }
              if($arrRooms[0]['numAdults']==2){
                $RoomType = 'Twin';
              }
              if($arrRooms[0]['numAdults']==3){
                $RoomType = 'Triple';
              }
              if($arrRooms[0]['numAdults']==4){
                $RoomType = 'Quad';
              }
              $hotels = DAO::queryAllSql("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.roomprice,
                  tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.roomtype as RoomType,
                  tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.bftype,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
                                                      FROM tghsearchprice
                                          INNER JOIN tghproperty
                                                      ON  tghproperty.property_cd = tghsearchprice.property_cd
                                                      WHERE tghsearchprice.property_cd ='".$hotelCode."'
                                                      AND tghsearchprice.check_in = '".$checkIn."'
                                                      AND tghsearchprice.check_out = '".$checkOut."'
                                                      AND tghsearchprice.roomtype = '".$RoomType."'
                                                      GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType,tghsearchprice.bftype
                                                      ");
        }
        if($NumRooms <= 1) {
            $arrRooms[] = array('numAdults' => $AdultNum);
              //echo "kamar isi";
              if($arrRooms[0]['numAdults']==1){
                $RoomType = 'Single';
              }
              if($arrRooms[0]['numAdults']==2){
                $RoomType = 'Twin';
              }
              if($arrRooms[0]['numAdults']==3){
                $RoomType = 'Triple';
              }
              if($arrRooms[0]['numAdults']==4){
                $RoomType = 'Quad';
              }
              //echo $arrRooms[0]['numAdults'];
            /*  echo ("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.roomprice,
                  tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
                  tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
                                                      FROM tghsearchprice
                                          INNER JOIN tghproperty
                                                      ON  tghproperty.property_cd = tghsearchprice.property_cd
                                                      WHERE tghsearchprice.property_cd ='".$hotelCode."'
                                                      AND tghsearchprice.check_in = '".$checkIn."'
                                                      AND tghsearchprice.check_out = '".$checkOut."'
                                                      GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
                                                      ");*/
              $hotels = DAO::queryAllSql("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.roomprice,
                  tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
                  tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.bftype,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
                                                      FROM tghsearchprice
                                          INNER JOIN tghproperty
                                                      ON  tghproperty.property_cd = tghsearchprice.property_cd
                                                      WHERE tghsearchprice.property_cd ='".$hotelCode."'
                                                      AND tghsearchprice.check_in = '".$checkIn."'
                                                      AND tghsearchprice.check_out = '".$checkOut."'
                                                      AND tghsearchprice.roomtype = '".$RoomType."'
                                                      GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType,tghsearchprice.bftype
                                                      ");
        }


        /*echo("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.roomprice,
            tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
            tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
                                                FROM tghsearchprice
                                    INNER JOIN tghproperty
                                                ON  tghproperty.property_cd = tghsearchprice.property_cd
                                                WHERE tghsearchprice.property_cd ='".$hotelCode."'
                                                AND tghsearchprice.check_in = '".$checkIn."'
                                                AND tghsearchprice.check_out = '".$checkOut."'
                                                GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
                                                ");
                                                */


        $countselect = count($hotels);
        echo $countselect;
        for($c=0;$c<$countselect;$c++)
        {
          $destCountry = $hotels[$c]['country_cd'];
          $city = $hotels[$c]['city_cd'];
          $property_name = $hotels[$c]['name'];
        }



        $xmlRequest = $this->renderPartial('req_searchhotelionic'
              , array(
                  'nationalities'=>$national,
                  'currency'=>$currency,
                  'destCountry'=>$destCountry,
                  'city'=>$city,
                  'hotelCode'=>$hotelCode,
                  'rommCatCode'=>'',
                  'checkIn'=>$checkIn,
                  'checkOut'=>$checkOut,
                  'AdultNum'=>$AdultNum,
                  'NumRooms'=>$NumRooms,
                  'arrRooms'=>$arrRooms,
                  'flagAvail'=>$flagAvail
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
          $hotelList='';
          if(isset($jsonResult['SearchHotel_Response']['Hotel'])){
              $hotelList = json_decode($jsonResult,TRUE)['SearchHotel_Response']['Hotel']['@attributes'];
          }

          $myJSON = json_encode($hotelList);
      }
      $this->render('view_hoteldetails',array(
              'hotelCode'=>$hotelCode,
              'hotels'=>$hotels,
              'myJSON'=>$myJSON,
              'checkIn'=>$checkIn,
              'checkOut'=>$checkOut
      ));
    }

    public function actionSearchhotelold()
    {
        //
        //print_r($_POST);
        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
        $hotelCode = '';
        if(isset($_POST['hotelCode']))
        {
          $destCountry=$_POST['destCountry'];
          $city=$_POST['city'];
          $hotelCode=$_POST['hotelCode'];
          $rommCatCode=$_POST['rommCatCode'];
          $checkIn=$_POST['checkIn'];
          $checkOut=$_POST['checkOut'];
          $AdultNum=$_POST['AdultNum'];
          $ChildNum=$_POST['ChildNum'];
          $NumRooms=$_POST['NumRooms'];
          $flagAvail="True";
          if($NumRooms== NULL || $NumRooms==0)
          {
            $NumRooms=1;
          }
          /*$avgAdultNumInRoom = floor($AdultNum/$NumRooms);
          $arrRooms = array();
          for($i=0; $i<$NumRooms; $i++) {
              $sisaGuest = 0;
                if($i == ($NumRooms-1) ) {
                  $sisaGuest = $AdultNum - ($avgAdultNumInRoom * $NumRooms);
                }
              $arrRooms[] = array('numAdults'=>$avgAdultNumInRoom+$sisaGuest);

          }*/
          $xmlRequest = $this->renderPartial('req_searchhotel'
              , array(
                  'destCountry'=>$destCountry,
                  'city'=>$city,
                  'hotelCode'=>$hotelCode,
                  'rommCatCode'=>$rommCatCode,
                  'checkIn'=>$checkIn,
                  'checkOut'=>$checkOut,
                  'AdultNum'=>$AdultNum,
                  'ChildNum'=>$ChildNum,
                  'NumRooms'=>$NumRooms,
                  'arrRooms'=>$arrRooms
              ), true);

          $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
          echo $jsonResult;
          Yii::app()->end();
        }
        $this->render('form_searchhotel',array(
                'hotelCode'=>$hotelCode
        ));
    }

    public function actionSearchsessionold()
    {
        //
        //print_r($_POST);
        //echo "string";
        //print_r($_REQUEST);
        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
        if(isset($_POST['city']))
        {
          //$destCountry=$_POST['destCountry'];
          /*$destinasi= (explode(",",$_POST['city']));
          $destCity=explode(" ",strtolower($destinasi[0]));*/

          $destCity=$_POST['city'];
          //$hotelCode=$_POST['hotelCode'];
          //$rommCatCode=$_POST['rommCatCode'];
          $duration=$_POST['duration'];
          $tempcheckIn=$_POST['checkin'];
          //$checkOut=$_POST['checkOut'];

          $checkIn = substr($tempcheckIn,0,10);
          //$newDate = date("Y-m-d", strtotime($originalDate));

          $tambahhari = '+'.$duration. 'day';
          $newdate = strtotime ( $tambahhari , strtotime ( $checkIn ) ) ;
          $checkOut = date ( 'Y-m-d' , $newdate );

          //echo $checkOut;

          //$AdultNum=$_POST['AdultNum'];
          $AdultNum=$_POST['guest'];
          $ChildNum=$_POST['ChildNum'];
          //$NumRooms=$_POST['NumRooms'];
          $NumRooms=$_POST['room'];

          if($NumRooms== NULL || $NumRooms==0)
          {
            $NumRooms=1;
          }
          if($AdultNum== NULL || $AdultNum==0)
          {
            $AdultNum=1;
          }

          $jumlah_tamu=$AdultNum;
          $check_in=$checkIn;
          $check_out=$checkOut;
          $now=date('Y-m-d H:i:s');
          $location_type = Searchlocation::LOCATION_TYPE_HOTEL;

          //Yii::app()->end();

          /*$country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                  FROM tghcitymg
                                                  WHERE city_name ='".$destCity."'");*/

          #query get location_type dari search
          /*echo ("SELECT location_type, location_code
                                                  FROM tghsearchlocation
                                                  WHERE location_name like '".$destCity[0]."%'");*/
          $searchloc = DAO::queryAllSql("SELECT location_type, location_code
                                                  FROM tghsearchlocation
                                                  WHERE location_name = '".$destCity."'");
          $countsearch= count($searchloc);
          for($cs=0;$cs<$countsearch;$cs++)
          {
            $location_type = $searchloc[$cs]['location_type'];
            $location_code = $searchloc[$cs]['location_code'];

              #query kondisi dari search location_type
              if($location_type==2){
                $country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                        FROM tghproperty
                                                        WHERE property_name = '".$destCity."'");
              }

              if($location_type==3){
                $country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                        FROM tghcitymg
                                                        WHERE city_cd ='".$location_code."'");
              }
              DAO::executeSql("INSERT INTO tghsearchsession (location_code,location_type,check_in,check_out,jumlah_kamar,jumlah_tamu, update_dt)VALUES('$location_code','$location_type','$check_in','$check_out','$NumRooms','$jumlah_tamu','$now')");

          }
        //  Yii::app()->end();
          $countselect = count($country);
          for($c=0;$c<$countselect;$c++)
          {
            $destCountry = $country[$c]['country_cd'];
            $city = $country[$c]['city_cd'];
          }
          //Yii::app()->end();
            if($NumRooms<=1){
              echo "string david trs";
              echo $arrRooms[] = array('numAdults'=>$AdultNum);
            }
            else{
              $avgAdultNumInRoom = ceil($AdultNum/$NumRooms);
              $arrRooms = array();
              for($i=0; $i<$NumRooms; $i++) {
                    /*$sisaGuest = 0;
                    if($i == ($NumRooms-1) ) {
                      $sisaGuest = $AdultNum - ($avgAdultNumInRoom * $NumRooms);
                    }*/
                  $arrRooms[] = array('numAdults'=>$avgAdultNumInRoom);
                  //print_r($arrRooms);

                  //Yii::app()->end();
              }
            }

          $xmlRequest = $this->renderPartial('req_searchhotel'
              , array(
                  'destCountry'=>$destCountry,
                  'city'=>$city,
                  'hotelCode'=>$hotelCode,
                  'rommCatCode'=>$rommCatCode,
                  'checkIn'=>$checkIn,
                  'checkOut'=>$checkOut,
                  'AdultNum'=>$AdultNum,
                  'ChildNum'=>$ChildNum,
                  'NumRooms'=>$NumRooms,
                  'arrRooms'=>$arrRooms,
                  'flagAvail'=>$flagAvail
              ), true);

          $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
          //echo $jsonResult;
          $hotelList = json_decode($jsonResult,TRUE)['SearchHotel_Response']['Hotel'];
          //print_r($hotelList);

          //Yii::app()->end();
          #looping json
          foreach ($hotelList as $listHotel) {
              $HotelId = $listHotel['@attributes']['HotelId'];
              $HotelName = $listHotel['@attributes']['HotelName'];
              //$MarketName = $listHotel['@attributes']['MarketName'];
              $location_code = $city;
              $currency = $listHotel['@attributes']['Currency'];
              $rating = $listHotel['@attributes']['rating'];
              //$avail = $listHotel['@attributes']['avail'];
              $avail = ($listHotel['@attributes']['avail'] == 'True' ? 1 : 0);

              if(!is_array($listHotel['RoomCateg'])){
                $listHotel['RoomCateg']=array($listHotel['RoomCateg']);
              }


              #jika array roomcateg tidak lebih dari 1
              if($listHotel['RoomCateg']['@attributes']!=Null)
              {
                 $roomcateg_id = $listHotel['RoomCateg']['@attributes']['Code'];
                 $roomcateg_name = $listHotel['RoomCateg']['@attributes']['Name'];
                 $roomcateg_net_price = $listHotel['RoomCateg']['@attributes']['NetPrice'];
                 $roomcateg_gross_price = $listHotel['RoomCateg']['@attributes']['GrossPrice'];
                 $roomcateg_comm_price = $listHotel['RoomCateg']['@attributes']['CommPrice'];
                 $roomcateg_price = $listHotel['RoomCateg']['@attributes']['Price'];
                 $roomcateg_BFType = $listHotel['RoomCateg']['@attributes']['BFType'];
                 $roomtype_name = $listHotel['RoomCateg']['RoomType']['@attributes']['TypeName'];
                 $roomtype_numrooms = $listHotel['RoomCateg']['RoomType']['@attributes']['NumRooms'];
                 $roomtype_totalprice = $listHotel['RoomCateg']['RoomType']['@attributes']['TotalPrice'];
                 $roomtype_avrNightPrice = $listHotel['RoomCateg']['RoomType']['@attributes']['avrNightPrice'];
                 $roomtype_RTGrossPrice = $listHotel['RoomCateg']['RoomType']['@attributes']['RTGrossPrice'];
                 $roomtype_RTCommPrice = $listHotel['RoomCateg']['RoomType']['@attributes']['RTCommPrice'];
                 $roomtype_RTNetPrice = $listHotel['RoomCateg']['RoomType']['@attributes']['RTNetPrice'];
                 if(!empty($listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq'])){
                   $AdultNums = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                   $ChildNums = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                   $RoomPrice = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'];
                 }
                 if(!empty($listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion'])){
                   $promo_name = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                   $promo_value = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                   $promo_code= $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];
                 }
              }
              else
              {
                  $sr=sizeof($listHotel['RoomCateg'])."-";
                  for($r=0;$r<$sr;$r++)
                  {
                     $roomcateg_id = $listHotel['RoomCateg'][$r]['@attributes']['Code'];
                     $roomcateg_name = $listHotel['RoomCateg'][$r]['@attributes']['Name'];
                     $roomcateg_net_price = $listHotel['RoomCateg'][$r]['@attributes']['NetPrice'];
                     $roomcateg_gross_price = $listHotel['RoomCateg'][$r]['@attributes']['GrossPrice'];
                     $roomcateg_comm_price = $listHotel['RoomCateg'][$r]['@attributes']['CommPrice'];
                     $roomcateg_price = $listHotel['RoomCateg'][$r]['@attributes']['Price'];
                     $roomcateg_BFType = $listHotel['RoomCateg'][$r]['@attributes']['BFType'];
                     $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice'];
                     $RTs=sizeof($listHotel['RoomCateg'][$r]['RoomType']);

                     if($listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice']!=Null)
                     {
                       $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice'];
                       $roomtype_name = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TypeName'];
                       $roomtype_numrooms = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['NumRooms'];
                       $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice'];
                       $roomtype_avrNightPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['avrNightPrice'];
                       $roomtype_RTGrossPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['RTGrossPrice'];
                       $roomtype_RTCommPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['RTCommPrice'];
                       $roomtype_RTNetPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['RTNetPrice'];
                       if(!empty($listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq'])){
                         $AdultNums = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                         $ChildNums = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                         $RoomPrice = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'];
                       }
                       if(!empty($listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion'])){
                         $promo_name = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                         $promo_value = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                         $promo_code = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];
                       }

                       /*echo "debug david"."<br>";
                       echo $roomtype_numrooms."<br>";
                       echo $NumRooms."<br>";*/
                       if($roomtype_numrooms>=$NumRooms && $avail==1){

                         /*echo ("INSERT INTO tghsearchprice
                           (session_id,property_cd,property_name,avail,
                           check_in,check_out,roomcateg_id,roomcateg_name,
                           net_price,gross_price,comm_price,price,BFType,
                           RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                           RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code,update_dt
                           )
                           VALUES
                           ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                           ,'$HotelId','$HotelName','$avail'
                           ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                           ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                           ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                           ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code','$now'
                         );");
                         */
                         DAO::executeSql("INSERT INTO tghsearchprice
                           (session_id,property_cd,property_name,avail,
                           check_in,check_out,roomcateg_id,roomcateg_name,
                           net_price,gross_price,comm_price,price,BFType,
                           RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                           RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                           )
                           VALUES
                           ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                           ,'$HotelId','$HotelName','$avail'
                           ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                           ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                           ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                           ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                         );");
                       }
                     }
                     else
                     {
                         for($rt=0;$rt<$RTs;$rt++)
                         {
                             $roomtype_name = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['TypeName'];
                             $roomtype_numrooms = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['NumRooms'];
                             $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['TotalPrice'];
                             $roomtype_avrNightPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['avrNightPrice'];
                             $roomtype_RTGrossPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['RTGrossPrice'];
                             $roomtype_RTCommPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['RTCommPrice'];
                             $roomtype_RTNetPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['RTNetPrice'];
                             if(!empty($listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq'])){
                               $AdultNums = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                               $ChildNums = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                             }
                             if(!empty($listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion'])){
                               $promo_name = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                               $promo_value = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                               $promo_code = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];
                             }

                             #insert sql
                             if($roomtype_numrooms>=$NumRooms){
                               /*echo ("INSERT INTO tghsearchprice
                                 (session_id,property_cd,property_name,avail,
                                 check_in,check_out,roomcateg_id,roomcateg_name,
                                 net_price,gross_price,comm_price,price,BFType,
                                 RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                 RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code,update_dt
                                 )
                                 VALUES
                                 ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                                 ,'$HotelId','$HotelName','$avail'
                                 ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                 ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                 ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                 ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code','$now'
                               );");*/
                               echo ("INSERT INTO tghsearchprice
                                 (session_id,property_cd,property_name,avail,
                                 check_in,check_out,roomcateg_id,roomcateg_name,
                                 net_price,gross_price,comm_price,price,BFType,
                                 RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                 RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code,update_dt
                                 )
                                 VALUES
                                 ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                                 ,'$HotelId','$HotelName','$avail'
                                 ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                 ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                 ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                 ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code','$now'
                               );");
                               DAO::executeSql("INSERT INTO tghsearchprice
                                 (session_id,property_cd,property_name,avail,
                                 check_in,check_out,roomcateg_id,roomcateg_name,
                                 net_price,gross_price,comm_price,price,BFType,
                                 RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                 RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code,update_dt
                                 )
                                 VALUES
                                 ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                                 ,'$HotelId','$HotelName','$avail'
                                 ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                 ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                 ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                 ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code','$now'
                               );");
                             }
                         }
                     }
                  }
              }
          }

          #select data searchsession
        }
        /*$this->render('form_searchsession',array(
                'hotelCode'=>$hotelCode
        ));*/
    }

    public function actionSearchhotel()
    {
        $mSearch = new SearchHotelForm();
//        $mSearch->checkin_dt = date('Y-m-d')
        $mSearch->checkin_dt = '2018-11-14';

        $hotels='';
        $check_in='';
        $check_out='';
        $countselecthotels='';
        $hotelCode='';

        if(isset($_POST['SearchHotelForm'])) {
            $mSearch->attributes = $_POST['SearchHotelForm'];
            if($mSearch->validate()) {
                $check_in = $mSearch->checkin_dt;
                $tambahhari = '+' . $mSearch->duration . 'day';
                $newdate = strtotime($tambahhari, strtotime($check_in));
                $check_out = date('Y-m-d', $newdate);

                $now = date('Y-m-d H:i:s');

                $qCekSession = DAO::queryRowSql('SELECT update_dt, session_id
                                                FROM tghsearchsession
                                                WHERE nationalities = :nat
                                                AND currency = :cur
                                                AND location_code = :lcd
                                                AND location_type = :lty
                                                AND check_in = :cin
                                                AND check_out = :cot
                                                AND jumlah_kamar = :kmr
                                                AND jumlah_tamu = :tmu'
                                        , array(':nat'=>$mSearch->nationalities, ':cur'=>$mSearch->currency, ':lcd'=>$mSearch->locationCode
                                            , ':lty'=>$mSearch->locationType, ':cin'=>$check_in, ':cot'=>$check_out, ':kmr'=>$mSearch->kamar, ':tmu'=>$mSearch->tamu));

                $sessionId = 0;
                // Untuk sementara di buat agar request terus.
//                if($qCekSession !== false) {
//                    $nowDate = date_create();
//                    $sessionDate = date_create($qCekSession['update_dt']);
//                    $diff = date_diff( $sessionDate, $nowDate );
//                    if(abs($diff->i) <= 30) {
//                        $sessionId = $qCekSession['session_id'];
//                    }
//                }

                if($sessionId == 0){
                    $arrRooms = array(
                        array('numAdults' => $mSearch->kamar)
                    );
                    if ($mSearch->kamar > 1) {
                        $avgAdultNumInRoom = ceil($mSearch->tamu / $mSearch->kamar);
                        $arrRooms = array();
                        for ($i = 0; $i < $mSearch->kamar; $i++) {
                            $arrRooms[] = array('numAdults' => $avgAdultNumInRoom);
                            //echo "kamar isi";
                            //echo $arrRooms[$i]['numAdults'];
                        }
                    }else{
                          $arrRooms[] = array('numAdults' => $mSearch->tamu);
                          //echo "kamar isi";
                          //  echo $arrRooms[0]['numAdults'];
                    }
                    if($mSearch->kamar == 1) {
                        $arrRooms[] = array('numAdults' => $mSearch->tamu);
                          //echo "kamar isi";
                          //echo $arrRooms[0]['numAdults'];
                    }

                    $xmlRequest = $this->renderPartial('req_searchhotelionic'
                        , array(
                            'nationalities' => $mSearch->nationalities,
                            'currency' => $mSearch->currency,
                            'destCountry' => $mSearch->locationCountry,
                            'city' => $mSearch->locationCity,
                            'hotelCode' => (($mSearch->locationType == Searchlocation::LOCATION_TYPE_HOTEL)? $mSearch->locationCode : ''),
                            'rommCatCode' => '',
                            'checkIn' => $check_in,
                            'checkOut' => $check_out,
                            'AdultNum' => $mSearch->tamu,
                            'NumRooms' => $mSearch->kamar,
                            'arrRooms' => $arrRooms
                        ), true);

                    $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
                    $hotelResponse = json_decode($jsonResult,TRUE);
                    //echo "string12";
                    //echo $mSearch->tamu;
                    //echo $xmlRequest;
                    echo '<pre>';
                    //var_dump($hotelResponse);
                    print_r($hotelResponse);
                    echo '</pre>';
                    //Yii::app()->end();
                    $hotelList = array();
                    if(isset($hotelResponse['SearchHotel_Response']['Hotel'])){
                        if(isset($hotelResponse['SearchHotel_Response']['Hotel']['@attributes'])) {
                            $hotelList = array(
                                $hotelResponse['SearchHotel_Response']['Hotel']
                            );
                        } else {
                            $hotelList = $hotelResponse['SearchHotel_Response']['Hotel'];
                        }
                    }

                    // @todo request ke mg dan insert ke tghsearchprice
                    $mSession = new Searchsession();
                    $mSession->nationalities = $mSearch->nationalities;
                    $mSession->currency = $mSearch->currency;
                    $mSession->location_code = $mSearch->locationCode;
                    $mSession->location_type = $mSearch->locationType;
                    $mSession->check_in = $check_in;
                    $mSession->check_out = $check_out;
                    $mSession->jumlah_kamar = $mSearch->kamar;
                    $mSession->jumlah_tamu = $mSearch->tamu;
                    $mSession->update_dt = $now;
                    $mSession->save(false);

                    $sessionId = $mSession->session_id;

                    foreach ($hotelList as $listHotel) {
//                        var_dump($listHotel);
//                        break;
                        $HotelId = $listHotel['@attributes']['HotelId'];
                        $HotelName = $listHotel['@attributes']['HotelName'];
                        //$MarketName = $listHotel['@attributes']['MarketName'];
                        $location_code = $mSearch->locationCity;
                        $currency = $listHotel['@attributes']['Currency'];
                        $rating = $listHotel['@attributes']['Rating'];
                        //$avail = $listHotel['@attributes']['avail'];
                        $avail = ($listHotel['@attributes']['avail'] == 'True' ? 1 : 0);

                        #jika array roomcateg tidak lebih dari 1
                        if (isset($listHotel['RoomCateg']['@attributes'])) {
                            $roomcateg_id = $listHotel['RoomCateg']['@attributes']['Code'];
                            $roomcateg_name = $listHotel['RoomCateg']['@attributes']['Name'];
                            $roomcateg_net_price = $listHotel['RoomCateg']['@attributes']['NetPrice'];
                            $roomcateg_gross_price = $listHotel['RoomCateg']['@attributes']['GrossPrice'];
                            $roomcateg_comm_price = $listHotel['RoomCateg']['@attributes']['CommPrice'];
                            $roomcateg_price = $listHotel['RoomCateg']['@attributes']['Price'];
                            $roomcateg_BFType = $listHotel['RoomCateg']['@attributes']['BFType'];
                            $roomtype_name = $listHotel['RoomCateg']['RoomType']['@attributes']['TypeName'];
                            $roomtype_numrooms = $listHotel['RoomCateg']['RoomType']['@attributes']['NumRooms'];
                            $roomtype_totalprice = $listHotel['RoomCateg']['RoomType']['@attributes']['TotalPrice'];
                            $roomtype_avrNightPrice = $listHotel['RoomCateg']['RoomType']['@attributes']['avrNightPrice'];
                            $roomtype_RTGrossPrice = $listHotel['RoomCateg']['RoomType']['@attributes']['RTGrossPrice'];
                            $roomtype_RTCommPrice = $listHotel['RoomCateg']['RoomType']['@attributes']['RTCommPrice'];
                            $roomtype_RTNetPrice = $listHotel['RoomCateg']['RoomType']['@attributes']['RTNetPrice'];
                            //$RoomPrice=0;
                            if(!empty($listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq'])){
                              if(!empty($listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes'])){
                                $AdultNums = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                                $ChildNums = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                              }
                            }

                            if(!empty($listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion'])){
                              $promo_name = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                              $promo_value = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                              $promo_code = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];
                            }
                            else {
                              $promo_name ='';
                              $promo_value='';
                              $promo_code='';
                            }
                            //echo "string rommrate";
                            if(!empty($listHotel['RoomCateg']['RoomType']['Rate'][0]['RoomRate'])){
                              $cekrt = sizeof($listHotel['RoomCateg']['RoomType']['Rate'][0]['RoomRate']);
                            }
                            else{
                              $cekrt = sizeof($listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']);
                            }
                            //$cekrt = sizeof($listHotel['RoomCateg']['RoomType']['Rate'][0]['RoomRate']);
                            if(!empty($listHotel['RoomCateg']['RoomType']['Rate'][0]['RoomRate'])){
                              if($mSearch->kamar>1 ||$mSearch->tamu>1 || $mSearch->duration>1){

                                //echo sizeof($hotelList);
                                $rtc = sizeof($listHotel['RoomCateg']['RoomType']['Rate']);
                                for ($rt = 0; $rt < $rtc; $rt++) {
                                    if(!isset($listHotel['RoomCateg']['RoomType']['Rate'][$rt]['RoomRate']['RoomSeq'])) {
                                        $listHotel['RoomCateg']['RoomType']['Rate'][$rt]['RoomRate']['RoomSeq'] = array('@attributes'=>$listHotel['RoomCateg']['RoomType']['Rate'][$rt]['RoomRate'][0]['@attributes']);
                                    }
                                    $rpc = sizeof($listHotel['RoomCateg']['RoomType']['Rate'][$rt]['RoomRate']['RoomSeq']);
                                    if($rpc>1){
                                      for($rp=0;$rp<$rpc;$rp++){
                                          $RoomPrice = $listHotel['RoomCateg']['RoomType']['Rate'][$rt]['RoomRate']['RoomSeq'][$rp]['@attributes']['RoomPrice'];
                                          DAO::executeSql("INSERT INTO tghsearchprice
                                             (session_id,property_cd,property_name,avail,
                                             check_in,check_out,roomcateg_id,roomcateg_name,
                                             net_price,gross_price,comm_price,price,BFType,
                                             RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                             RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                                             )
                                             VALUES
                                             ({$mSession->session_id}
                                             ,'$HotelId','$HotelName','$avail'
                                             ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                             ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                             ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                             ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                                           );");
                                      }
                                    }
                                    else{

                                      $RoomPrice = $listHotel['RoomCateg']['RoomType']['Rate'][$rt]['RoomRate']['RoomSeq']['@attributes']['RoomPrice'];
                                      DAO::executeSql("INSERT INTO tghsearchprice
                                         (session_id,property_cd,property_name,avail,
                                         check_in,check_out,roomcateg_id,roomcateg_name,
                                         net_price,gross_price,comm_price,price,BFType,
                                         RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                         RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                                         )
                                         VALUES
                                         ({$mSession->session_id}
                                         ,'$HotelId','$HotelName','$avail'
                                         ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                         ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                         ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                         ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                                       );");
                                    }
                                  }
                                }
                              }
                              else{
                                //echo "string kamar2z";
                                //echo $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'];
                                if(!empty($listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'])){
                                  $RoomPrice = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'];
                                  DAO::executeSql("INSERT INTO tghsearchprice
                                     (session_id,property_cd,property_name,avail,
                                     check_in,check_out,roomcateg_id,roomcateg_name,
                                     net_price,gross_price,comm_price,price,BFType,
                                     RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                     RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                                     )
                                     VALUES
                                     ({$mSession->session_id}
                                     ,'$HotelId','$HotelName','$avail'
                                     ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                     ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                     ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                     ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                                   );");
                                }
                              }

                        } else {

                            $sr = sizeof($listHotel['RoomCateg']) . "-";

                            for ($r = 0; $r < $sr; $r++) {
                                $roomcateg_id = $listHotel['RoomCateg'][$r]['@attributes']['Code'];
                                $roomcateg_name = $listHotel['RoomCateg'][$r]['@attributes']['Name'];
                                $roomcateg_net_price = $listHotel['RoomCateg'][$r]['@attributes']['NetPrice'];
                                $roomcateg_gross_price = $listHotel['RoomCateg'][$r]['@attributes']['GrossPrice'];
                                $roomcateg_comm_price = $listHotel['RoomCateg'][$r]['@attributes']['CommPrice'];
                                $roomcateg_price = $listHotel['RoomCateg'][$r]['@attributes']['Price'];
                                $roomcateg_BFType = $listHotel['RoomCateg'][$r]['@attributes']['BFType'];
                                $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice'];
                                $RTs = sizeof($listHotel['RoomCateg'][$r]['RoomType']);

                                if ($listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice'] != Null) {
                                    //echo "wong";
                                    $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice'];
                                    $roomtype_name = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TypeName'];
                                    $roomtype_numrooms = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['NumRooms'];
                                    $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice'];
                                    $roomtype_avrNightPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['avrNightPrice'];
                                    $roomtype_RTGrossPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['RTGrossPrice'];
                                    $roomtype_RTCommPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['RTCommPrice'];
                                    $roomtype_RTNetPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['RTNetPrice'];
                                    if(!empty($listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq'])){
                                      if(!empty($listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes'])){
                                        $AdultNums = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                                        $ChildNums = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];

                                      }
                                    }

                                    if(!empty($listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion'])){
                                      $promo_name = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                                      $promo_value = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                                      $promo_code = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];
                                    }
                                    else {
                                      $promo_name ='';
                                      $promo_value='';
                                      $promo_code='';
                                    }
                                    if ($roomtype_numrooms >= $mSearch->kamar && $avail == 1) {
                                      //echo "masuk lah";
                                        /*echo ("INSERT INTO tghsearchprice
                                          (session_id,property_cd,property_name,avail,
                                          check_in,check_out,roomcateg_id,roomcateg_name,
                                          net_price,gross_price,comm_price,price,BFType,
                                          RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                          RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code,update_dt
                                          )
                                          VALUES
                                          ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                                          ,'$HotelId','$HotelName','$avail'
                                          ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                          ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                          ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                          ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code','$now'
                                        );");
                                        */
                                        if(!empty($listHotel['RoomCateg'][$r]['RoomType']['Rate'][0]['RoomRate'])){

                                          if($mSearch->kamar>1 ||$mSearch->tamu>1 || $mSearch->duration>1){
                                            //echo "string kamar";
                                            //echo sizeof($hotelList);
                                            $rtc = sizeof($listHotel['RoomCateg'][$r]['RoomType']['Rate']);
                                            for ($rt = 0; $rt < $rtc; $rt++) {
                                                if(!isset($listHotel['RoomCateg'][$r]['RoomType']['Rate'][$rt]['RoomRate']['RoomSeq'])) {
                                                    $listHotel['RoomCateg'][$r]['RoomType']['Rate'][$rt]['RoomRate']['RoomSeq'] = array('@attributes'=>$listHotel['RoomCateg'][$r]['RoomType']['Rate'][$rt]['RoomRate'][0]['@attributes']);
                                                }
                                                $rpc = sizeof($listHotel['RoomCateg'][$r]['RoomType']['Rate'][$rt]['RoomRate']['RoomSeq']);
                                                if($rpc>1){
                                                  for($rp=0;$rp<$rpc;$rp++){
                                                      $RoomPrice = $listHotel['RoomCateg'][$r]['RoomType']['Rate'][$rt]['RoomRate']['RoomSeq'][$rp]['@attributes']['RoomPrice'];
                                                      DAO::executeSql("INSERT INTO tghsearchprice
                                                         (session_id,property_cd,property_name,avail,
                                                         check_in,check_out,roomcateg_id,roomcateg_name,
                                                         net_price,gross_price,comm_price,price,BFType,
                                                         RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                                         RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                                                         )
                                                         VALUES
                                                         ({$mSession->session_id}
                                                         ,'$HotelId','$HotelName','$avail'
                                                         ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                                         ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                                         ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                                         ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                                                       );");
                                                  }
                                                }
                                                else{
                                                  $RoomPrice = $listHotel['RoomCateg'][$r]['RoomType']['Rate'][$rt]['RoomRate']['RoomSeq']['@attributes']['RoomPrice'];
                                                  DAO::executeSql("INSERT INTO tghsearchprice
                                                     (session_id,property_cd,property_name,avail,
                                                     check_in,check_out,roomcateg_id,roomcateg_name,
                                                     net_price,gross_price,comm_price,price,BFType,
                                                     RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                                     RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                                                     )
                                                     VALUES
                                                     ({$mSession->session_id}
                                                     ,'$HotelId','$HotelName','$avail'
                                                     ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                                     ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                                     ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                                     ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                                                   );");
                                                }
                                              }
                                            }
                                          }
                                          else{

                                            //echo $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'];
                                            //echo $listHotel['RoomCateg'][0]['RoomType']['Rate']['RoomRate']['RoomSeq'];
                                            if(!empty($listHotel['RoomCateg'][0]['RoomType']['Rate']['RoomRate']['RoomSeq']))
                                            {
                                                $cekrs=sizeof($listHotel['RoomCateg'][0]['RoomType']['Rate']['RoomRate']['RoomSeq']);
                                            }
                                            else{
                                                $cekrs = null;
                                            }

                                            if($cekrs>1){
                                              //echo "come on";
                                              for($looprs=0;$looprs<$cekrs;$looprs++){
                                                echo "come on1x";
                                                //echo $cekrs;
                                                //Yii::app()->end();
                                                $RoomPrice = $listHotel['RoomCateg'][0]['RoomType']['Rate']['RoomRate']['RoomSeq'][$looprs]['@attributes']['RoomPrice'];
                                                DAO::executeSql("INSERT INTO tghsearchprice
                                                   (session_id,property_cd,property_name,avail,
                                                   check_in,check_out,roomcateg_id,roomcateg_name,
                                                   net_price,gross_price,comm_price,price,BFType,
                                                   RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                                   RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                                                   )
                                                   VALUES
                                                   ({$mSession->session_id}
                                                   ,'$HotelId','$HotelName','$avail'
                                                   ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                                   ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                                   ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                                   ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                                                 );");
                                              }
                                            }
                                            else {
                                              echo "masuk1";
                                              $cseq= count($listHotel['RoomCateg']);
                                              for($sq=0;$sq<$cseq;$sq++){
                                                $RoomPrice = $listHotel['RoomCateg'][0]['RoomType']['Rate']['RoomRate']['RoomSeq'][$sq]['@attributes']['RoomPrice'];
                                                DAO::executeSql("INSERT INTO tghsearchprice
                                                   (session_id,property_cd,property_name,avail,
                                                   check_in,check_out,roomcateg_id,roomcateg_name,
                                                   net_price,gross_price,comm_price,price,BFType,
                                                   RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                                   RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                                                   )
                                                   VALUES
                                                   ({$mSession->session_id}
                                                   ,'$HotelId','$HotelName','$avail'
                                                   ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                                   ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                                   ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                                   ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                                                 );");
                                              }
                                              /*$RoomPrice = $listHotel['RoomCateg'][0]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'];
                                              DAO::executeSql("INSERT INTO tghsearchprice
                                                 (session_id,property_cd,property_name,avail,
                                                 check_in,check_out,roomcateg_id,roomcateg_name,
                                                 net_price,gross_price,comm_price,price,BFType,
                                                 RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                                 RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                                                 )
                                                 VALUES
                                                 ({$mSession->session_id}
                                                 ,'$HotelId','$HotelName','$avail'
                                                 ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                                 ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                                 ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                                 ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                                               );");*/
                                            }
                                          }
                                    }
                                } else {
                                    for ($rt = 0; $rt < $RTs; $rt++) {
                                        $roomtype_name = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['TypeName'];
                                        $roomtype_numrooms = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['NumRooms'];
                                        $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['TotalPrice'];
                                        $roomtype_avrNightPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['avrNightPrice'];
                                        $roomtype_RTGrossPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['RTGrossPrice'];
                                        $roomtype_RTCommPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['RTCommPrice'];
                                        $roomtype_RTNetPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['RTNetPrice'];
                                        if(!empty($listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq'])){
                                          $AdultNums = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                                          $ChildNums = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                                        }
                                        if(!empty($listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion'])){
                                          $promo_name = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                                          $promo_value = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                                          $promo_code = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];
                                        }

                                        #insert sql
                                        if ($roomtype_numrooms >= $mSearch->kamar) {
                                            /*echo ("INSERT INTO tghsearchprice
                                              (session_id,property_cd,property_name,avail,
                                              check_in,check_out,roomcateg_id,roomcateg_name,
                                              net_price,gross_price,comm_price,price,BFType,
                                              RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                              RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code,update_dt
                                              )
                                              VALUES
                                              ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                                              ,'$HotelId','$HotelName','$avail'
                                              ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                              ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                              ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                              ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code','$now'
                                            );");*/
                                            //mulai

                                            //if(!empty($listHotel['RoomCateg'][$r]['RoomType']['Rate'][0]['RoomRate'])){
                                                //echo "string kamarcateg1";
                                              if($mSearch->kamar>1 ||$mSearch->tamu>1 || $mSearch->duration>1){
                                                //echo "string kamarcateg2";
                                                $rtcr = sizeof($listHotel['RoomCateg'][$r]['RoomType']['Rate']);
                                                for ($rtr = 0; $rtr < $rtcr; $rtr++) {
                                                  //echo "stringx";
                                                    $rpc = sizeof($listHotel['RoomCateg'][$r]['RoomType']['Rate'][$rtr]['RoomRate']['RoomSeq']);
                                                    for($rpr=0;$rpr<$rpcr;$rpr++){
                                                        $RoomPrice = $listHotel['RoomCateg'][$r]['RoomType']['Rate'][$rtr]['RoomRate']['RoomSeq'][$rpr]['@attributes']['RoomPrice'];
                                                        DAO::executeSql("INSERT INTO tghsearchprice
                                                           (session_id,property_cd,property_name,avail,
                                                           check_in,check_out,roomcateg_id,roomcateg_name,
                                                           net_price,gross_price,comm_price,price,BFType,
                                                           RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                                           RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                                                           )
                                                           VALUES
                                                           ({$mSession->session_id}
                                                           ,'$HotelId','$HotelName','$avail'
                                                           ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                                           ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                                           ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                                           ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                                                         );");
                                                    }
                                                  }
                                                }
                                                else{

                                                  $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'];
                                                  if(!empty($listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'])){
                                                    $RoomPrice = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'];
                                                    DAO::executeSql("INSERT INTO tghsearchprice
                                                       (session_id,property_cd,property_name,avail,
                                                       check_in,check_out,roomcateg_id,roomcateg_name,
                                                       net_price,gross_price,comm_price,price,BFType,
                                                       RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                                       RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                                                       )
                                                       VALUES
                                                       ({$mSession->session_id}
                                                       ,'$HotelId','$HotelName','$avail'
                                                       ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                                       ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                                       ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                                       ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                                                     );");
                                                  }
                                                }

                                        }
                                    }
                                }
                            }
                        }
                    }
                }
//                echo '</pre>';
//                Yii::app()->end();
                $hotels = DAO::queryAllSql("SELECT tghproperty.property_cd as hotelCode,tghproperty.property_name as name,tghsearchprice.price,tghsearchprice.roomprice,
      				                      tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address
                                          FROM tghsearchprice
      									  INNER JOIN tghproperty ON  tghproperty.property_cd = tghsearchprice.property_cd
      									  WHERE tghsearchprice.session_id = :sid
      									  GROUP BY tghproperty.property_name", array(':sid'=>$sessionId));

                $mSearch->displayResult = true;
                $countselecthotels = count($hotels);
            }
        }

        $this->render('form_searchhotel',array(
                'mSearch'=>$mSearch,
                'hotelCode'=>$hotelCode,
                'hotels'=>$hotels,
                'check_in'=>$check_in,
                'check_out'=>$check_out,
                'countselecthotels'=>$countselecthotels
        ));
    }

    public function actionSearchsessionionic()
    {
        //
        //print_r($_GET);
        //print_r($_POST);


        //echo "string";
        //print_r($_REQUEST);
        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
        if(isset($_POST['city']))
        {
          //$destCountry=$_POST['destCountry'];
          /*$destinasi= (explode(",",$_POST['city']));
          $destCity=explode(" ",strtolower($destinasi[0]));*/

          $destCity=$_POST['city'];
          //$hotelCode=$_POST['hotelCode'];
          //$rommCatCode=$_POST['rommCatCode'];
          $duration=$_POST['duration'];
          $tempcheckIn=$_POST['checkin'];
          //$checkOut=$_POST['checkOut'];

          $checkIn = substr($tempcheckIn,0,10);
          //$newDate = date("Y-m-d", strtotime($originalDate));

          $tambahhari = '+'.$duration. 'day';
          $newdate = strtotime ( $tambahhari , strtotime ( $checkIn ) ) ;
          $checkOut = date ( 'Y-m-d' , $newdate );

          //echo $checkOut;

          //$AdultNum=$_POST['AdultNum'];
          $AdultNum=$_POST['guest'];
          $ChildNum=$_POST['ChildNum'];
          //$NumRooms=$_POST['NumRooms'];
          $NumRooms=$_POST['room'];

          if($NumRooms== NULL || $NumRooms==0)
          {
            $NumRooms=1;
          }
          if($AdultNum== NULL || $AdultNum==0)
          {
            $AdultNum=1;
          }

          $jumlah_tamu=$AdultNum;
          $check_in=$checkIn;
          $check_out=$checkOut;
          $now=date('Y-m-d H:i:s');
          $location_type = Searchlocation::LOCATION_TYPE_HOTEL;

          Yii::app()->end();

          /*$country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                  FROM tghcitymg
                                                  WHERE city_name ='".$destCity."'");*/

          #query get location_type dari search
          /*echo ("SELECT location_type, location_code
                                                  FROM tghsearchlocation
                                                  WHERE location_name like '".$destCity[0]."%'");*/
          $searchloc = DAO::queryAllSql("SELECT location_type, location_code
                                                  FROM tghsearchlocation
                                                  WHERE location_name = '".$destCity."'");
          $countsearch= count($searchloc);
          for($cs=0;$cs<$countsearch;$cs++)
          {
            $location_type = $searchloc[$cs]['location_type'];
            $location_code = $searchloc[$cs]['location_code'];

              #query kondisi dari search location_type
              if($location_type==2){
                $country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                        FROM tghproperty
                                                        WHERE property_name = '".$destCity."'");
              }

              if($location_type==3){
                $country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                        FROM tghcitymg
                                                        WHERE city_cd ='".$location_code."'");
              }
              DAO::executeSql("INSERT INTO tghsearchsession (location_code,location_type,check_in,check_out,jumlah_kamar,jumlah_tamu, update_dt)VALUES('$location_code','$location_type','$check_in','$check_out','$NumRooms','$jumlah_tamu','$now')");

          }
        //  Yii::app()->end();
          $countselect = count($country);
          for($c=0;$c<$countselect;$c++)
          {
            $destCountry = $country[$c]['country_cd'];
            $city = $country[$c]['city_cd'];
          }
          //Yii::app()->end();
          if($mSearch->kamar<=1){
            $arrRooms[] = array('numAdults'=>$mSearch->tamu);
            //print_r($arrRooms);
          }
          else{
                $avgAdultNumInRoom = ceil($AdultNum/$NumRooms);
                $arrRooms = array();
                for($i=0; $i<$NumRooms; $i++) {
                      /*$sisaGuest = 0;
                      if($i == ($NumRooms-1) ) {
                        $sisaGuest = $AdultNum - ($avgAdultNumInRoom * $NumRooms);
                      }*/
                    //echo "kamar isi";
                     $arrRooms[] = array('numAdults'=>$avgAdultNumInRoom);
                    //print_r($arrRooms);

                    //Yii::app()->end();
                }
            }
          $xmlRequest = $this->renderPartial('req_searchhotelionic'
              , array(
                  'destCountry'=>$destCountry,
                  'city'=>$city,
                  'destCity'=>$destCity,
                  //'hotelCode'=>$hotelCode,
                  'checkIn'=>$checkIn,
                  'checkOut'=>$checkOut,
                  'AdultNum'=>$AdultNum,
                  'ChildNum'=>$ChildNum,
                  'NumRooms'=>$NumRooms,
                  'arrRooms'=>$arrRooms
              ), true);
            //echo $xmlRequest;
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
          //echo $jsonResult;
          $hotelList = json_decode($jsonResult,TRUE)['SearchHotel_Response']['Hotel'];
          //print_r($hotelList);

          //Yii::app()->end();
          #looping json
          foreach ($hotelList as $listHotel) {
              $HotelId = $listHotel['@attributes']['HotelId'];
              $HotelName = $listHotel['@attributes']['HotelName'];
              //$MarketName = $listHotel['@attributes']['MarketName'];
              $location_code = $city;
              $currency = $listHotel['@attributes']['Currency'];
              $rating = $listHotel['@attributes']['rating'];
              //$avail = $listHotel['@attributes']['avail'];
              $avail = ($listHotel['@attributes']['avail'] == 'True' ? 1 : 0);

              #jika array roomcateg tidak lebih dari 1
              if($listHotel['RoomCateg']['@attributes']!=Null)
              {
                 $roomcateg_id = $listHotel['RoomCateg']['@attributes']['Code'];
                 $roomcateg_name = $listHotel['RoomCateg']['@attributes']['Name'];
                 $roomcateg_net_price = $listHotel['RoomCateg']['@attributes']['NetPrice'];
                 $roomcateg_gross_price = $listHotel['RoomCateg']['@attributes']['GrossPrice'];
                 $roomcateg_comm_price = $listHotel['RoomCateg']['@attributes']['CommPrice'];
                 $roomcateg_price = $listHotel['RoomCateg']['@attributes']['Price'];
                 $roomcateg_BFType = $listHotel['RoomCateg']['@attributes']['BFType'];
                 $roomtype_name = $listHotel['RoomCateg']['RoomType']['@attributes']['TypeName'];
                 $roomtype_numrooms = $listHotel['RoomCateg']['RoomType']['@attributes']['NumRooms'];
                 $roomtype_totalprice = $listHotel['RoomCateg']['RoomType']['@attributes']['TotalPrice'];
                 $roomtype_avrNightPrice = $listHotel['RoomCateg']['RoomType']['@attributes']['avrNightPrice'];
                 $roomtype_RTGrossPrice = $listHotel['RoomCateg']['RoomType']['@attributes']['RTGrossPrice'];
                 $roomtype_RTCommPrice = $listHotel['RoomCateg']['RoomType']['@attributes']['RTCommPrice'];
                 $roomtype_RTNetPrice = $listHotel['RoomCateg']['RoomType']['@attributes']['RTNetPrice'];
                 if(!empty($listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq'])){
                   $AdultNums = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                   $ChildNums = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                   $RoomPrice = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'];
                 }
                 if(!empty($listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion'])){
                   $promo_name = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                   $promo_value = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                   $promo_code= $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];
                 }

                 DAO::executeSql("INSERT INTO tghsearchprice
                   (session_id,property_cd,property_name,avail,
                   check_in,check_out,roomcateg_id,roomcateg_name,
                   net_price,gross_price,comm_price,price,BFType,
                   RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                   RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                   )
                   VALUES
                   ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                   ,'$HotelId','$HotelName','$avail'
                   ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                   ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                   ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                   ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                 );");
              }
              else
              {
                  $sr=sizeof($listHotel['RoomCateg'])."-";
                  for($r=0;$r<$sr;$r++)
                  {
                     $roomcateg_id = $listHotel['RoomCateg'][$r]['@attributes']['Code'];
                     $roomcateg_name = $listHotel['RoomCateg'][$r]['@attributes']['Name'];
                     $roomcateg_net_price = $listHotel['RoomCateg'][$r]['@attributes']['NetPrice'];
                     $roomcateg_gross_price = $listHotel['RoomCateg'][$r]['@attributes']['GrossPrice'];
                     $roomcateg_comm_price = $listHotel['RoomCateg'][$r]['@attributes']['CommPrice'];
                     $roomcateg_price = $listHotel['RoomCateg'][$r]['@attributes']['Price'];
                     $roomcateg_BFType = $listHotel['RoomCateg'][$r]['@attributes']['BFType'];
                     $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice'];
                     $RTs=sizeof($listHotel['RoomCateg'][$r]['RoomType']);

                     if($listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice']!=Null)
                     {
                       $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice'];
                       $roomtype_name = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TypeName'];
                       $roomtype_numrooms = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['NumRooms'];
                       $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice'];
                       $roomtype_avrNightPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['avrNightPrice'];
                       $roomtype_RTGrossPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['RTGrossPrice'];
                       $roomtype_RTCommPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['RTCommPrice'];
                       $roomtype_RTNetPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['RTNetPrice'];
                       if(!empty($listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq'])){
                         $AdultNums = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                         $ChildNums = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                       if($mSearch->kamar>1||$mSearch->tamu>1 || $mSearch->duration>1 ){
                           if(!empty($listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq'][0]['@attributes']['RoomPrice'])){
                             echo $RoomPrice = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq'][0]['@attributes']['RoomPrice'];
                           }
                         }
                         else{
                           if(!empty($listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'])){
                             echo $RoomPrice = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['RoomPrice'];
                           }
                           else{
                             $RoomPrice = 0;
                           }
                         }
                       }
                       if(!empty($listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion'])){
                         $promo_name = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                         $promo_value = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                         $promo_code = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];
                       }

                       /*echo "debug david"."<br>";
                       echo $roomtype_numrooms."<br>";
                       echo $NumRooms."<br>";*/
                       if($roomtype_numrooms>=$NumRooms && $avail==1){

                         /*echo ("INSERT INTO tghsearchprice
                           (session_id,property_cd,property_name,avail,
                           check_in,check_out,roomcateg_id,roomcateg_name,
                           net_price,gross_price,comm_price,price,BFType,
                           RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                           RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code,update_dt
                           )
                           VALUES
                           ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                           ,'$HotelId','$HotelName','$avail'
                           ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                           ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                           ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                           ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code','$now'
                         );");
                         */
                         DAO::executeSql("INSERT INTO tghsearchprice
                           (session_id,property_cd,property_name,avail,
                           check_in,check_out,roomcateg_id,roomcateg_name,
                           net_price,gross_price,comm_price,price,BFType,
                           RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                           RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
                           )
                           VALUES
                           ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                           ,'$HotelId','$HotelName','$avail'
                           ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                           ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                           ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                           ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
                         );");
                       }
                     }
                     else
                     {
                         for($rt=0;$rt<$RTs;$rt++)
                         {
                             $roomtype_name = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['TypeName'];
                             $roomtype_numrooms = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['NumRooms'];
                             $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['TotalPrice'];
                             $roomtype_avrNightPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['avrNightPrice'];
                             $roomtype_RTGrossPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['RTGrossPrice'];
                             $roomtype_RTCommPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['RTCommPrice'];
                             $roomtype_RTNetPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['RTNetPrice'];
                             if(!empty($listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq'])){
                               $AdultNums = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                               $ChildNums = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                             }
                             if(!empty($listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion'])){
                               $promo_name = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                               $promo_value = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                               $promo_code = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];
                             }


                             #insert sql
                             if($roomtype_numrooms>=$NumRooms){
                               /*echo ("INSERT INTO tghsearchprice
                                 (session_id,property_cd,property_name,avail,
                                 check_in,check_out,roomcateg_id,roomcateg_name,
                                 net_price,gross_price,comm_price,price,BFType,
                                 RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                 RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code,update_dt
                                 )
                                 VALUES
                                 ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                                 ,'$HotelId','$HotelName','$avail'
                                 ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                 ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                 ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                 ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code','$now'
                               );");*/
                               DAO::executeSql("INSERT INTO tghsearchprice
                                 (session_id,property_cd,property_name,avail,
                                 check_in,check_out,roomcateg_id,roomcateg_name,
                                 net_price,gross_price,comm_price,price,BFType,
                                 RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                 RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code,update_dt
                                 )
                                 VALUES
                                 ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                                 ,'$HotelId','$HotelName','$avail'
                                 ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                 ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                 ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                 ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code','$now'
                               );");
                             }
                         }
                     }
                  }
              }
          }

          #select data searchsession
        }
        /*$this->render('form_searchsession',array(
                'hotelCode'=>$hotelCode
        ));*/
    }

    public function actionSavehotel($destCountry, $city, $hotelCode, $rommCatCode, $checkIn, $checkOut)
    {
        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
        $xmlRequest = $this->renderPartial('req_searchsession'
            , array(
                'destCountry'=>$destCountry,
                'city'=>$city,
                'hotelCode'=>$hotelCode,
                'rommCatCode'=>$rommCatCode,
                'checkIn'=>$checkIn,
                'checkOut'=>$checkOut,
            ), true);

        $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
        $hotelList = json_decode($jsonResult,TRUE)['SearchHotel_Response']['Hotel'];

        foreach ($hotelList as $listHotel) {
            echo $listHotel['@attributes']['HotelId'].'<br />';
            echo $listHotel['@attributes']['HotelName'].'<br />';
            echo $listHotel['@attributes']['MarketName'].'<br />';
            echo $listHotel['@attributes']['Latitude'].'<br />';
            echo $listHotel['@attributes']['Longitude'].'<br />';
            echo $listHotel['@attributes']['Rating'].'<br />';
            echo '<br /><br />';
        }

    }

    public function actionSavehoteldetail($hotelCode)
    {
          //print_r($_POST);

          $xmlRequest = $this->renderPartial('req_gethoteldetail'
              , array(
                  'hotelCode'=>$hotelCode
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_HOTEL_DETAIL, $xmlRequest);

          //$array = json_decode($jsonResult,TRUE);

          $hotelList = json_decode($jsonResult,TRUE)['GetHotelDetail_Response'];
          print_r($hotelList);
          /*foreach ($hotelList as $listHotel) {
            echo $listHotel['HotelId'].'<br />';
            echo $listHotel['HotelName'].'<br />';
            echo $listHotel['HotelRooms'].'<br />';
            echo $listHotel['Address1'].'<br />';
            echo $listHotel['Address2'].'<br />';
            echo $listHotel['Address3'].'<br />';
            echo $listHotel['Location'].'<br />';
            echo $listHotel['Telephone'].'<br />';
            echo $listHotel['Email'].'<br />';
            echo $listHotel['@attributes']['Rating'].'<br />';
              echo '<br /><br />';
          }*/
    }

    public function actionViewCancelPolicy($hotelCode,$checkIn,$checkOut)
    {
        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
        $AdultNum = '';
        $RoomType = '';
        $flagAvail = '';
        $hotelList='';
        $SeqNo='';
        $jsonResult='';

            if (isset($hotelCode)) {
                //$hotelCode=$_POST['hotelCode'];
                //$InternalCode=$_POST['internalCode'];
                $InternalCode = 'CL005';
                //$checkIn=$_POST['checkIn'];
                //$checkOut=$_POST['checkOut'];
                //$SeqNo=$_POST['SeqNo'];
                $SeqNo = 1;
                $AdultNum = 1;
                $RoomType = 'Single';
                $flagAvail = 1;

                $xmlRequest = $this->renderPartial('req_cancelpolicy'
                    , array(
                        'hotelCode' => $hotelCode,
                        'InternalCode' => $InternalCode,
                        'checkIn' => $checkIn,
                        'checkOut' => $checkOut,
                        'AdultNum' => $AdultNum,
                        'RoomType' => $RoomType,
                        //'SeqNo' => $SeqNo,
                        'flagAvail' => $flagAvail,
                    ), true);
                //echo $xmlRequest;
                $jsonResult = ApiRequestor::post(ApiRequestor::URL_VIEW_CANCEL_POLICY, $xmlRequest);
                //echo $jsonResult;
                if(isset($jsonResult['ViewCancelPolicy_Response'])){
                    $hotelList = json_decode($jsonResult, TRUE)['ViewCancelPolicy_Response']['Policies'];
                }

                //echo $myJSON = json_encode($hotelList);
            }

        $this->render('form_viewcancelpolicy',array(
                'hotelCode'=>$hotelCode,
                'hotelList'=>$hotelList,
                'jsonResult'=>$jsonResult
        ));
    }

    public function actionBookHotel($hotelCode,$CatgId)
    {

      $bookhotel = DAO::queryAllSql("SELECT *
                                              FROM tghsearchprice
                                              WHERE property_cd ='".$hotelCode."' AND roomcateg_id ='".$CatgId."'");

      $countselect = count($bookhotel);
      for($c=0;$c<=$countselect;$c++)
      {
        //$hotelCode=$_POST['hotelCode'];
        //$InternalCode=$_POST['internalCode'];
        $InternalCode='CL005';
        //$checkIn=$_POST['checkIn'];
        //$checkOut=$_POST['checkOut'];
        //$SeqNo=$_POST['SeqNo'];
        //$AdultNum=$_POST['AdultNum'];
        //$RoomType=$_POST['RoomType'];
        //$flagAvail=$_POST['flagAvail'];
         $flagAvail = 'Y';
        //$CatgId=$_POST['CatgId'];
        //$CatgName=$_POST['CatgName'];
        //$BFType=$_POST['BFType'];
        //$price=$_POST['price'];
         $SeqNo = $c+1;
         $checkIn = $bookhotel[$c]['check_in'];
         $checkOut = $bookhotel[$c]['check_out'];
         $RoomType = $bookhotel[$c]['RoomType'];
         //$CatgId[$c] = $bookhotel[$c]['roomcateg_id'];
         $AdultNum=2;
         $CatgName = $bookhotel[$c]['roomcateg_name'];
         $BFType = $bookhotel[$c]['BFType'];
         $checkIn = $bookhotel[$c]['check_in'];
         $checkOut = $bookhotel[$c]['check_out'];
         $price = $bookhotel[$c]['price'];
      }
      //if(isset($_POST['hotelCode']))
      //{
        //$hotelCode=$_POST['hotelCode'];
        //$InternalCode=$_POST['internalCode'];
        //$InternalCode='CL005';
        //$checkIn=$_POST['checkIn'];
        //$checkOut=$_POST['checkOut'];
        //$SeqNo=$_POST['SeqNo'];
        //$AdultNum=$_POST['AdultNum'];
        //$RoomType=$_POST['RoomType'];
        //$flagAvail=$_POST['flagAvail'];
        //$CatgId=$_POST['CatgId'];
        //$CatgName=$_POST['CatgName'];
        //$BFType=$_POST['BFType'];
        //$price=$_POST['price'];
        $digits_needed=13;
        $random_number=''; // set up a blank string
        $count=0;

        while ( $count < $digits_needed ) {
            $random_digit = mt_rand(0, 9);

            $random_number .= $random_digit;
            $count++;
        }
        //Yii::app()->end();
          //mg/hoteldata/BookHotel&hotelCode=WSASTHBKK000087&InternalCode=CL005&checkIn=2018-10-20&checkOut=2018-10-22&SeqNo=1&AdultNum=2&RoomType=Twin&flagAvail=True
          $xmlRequest = $this->renderPartial('req_bookhotel'
              , array(
                  'hotelCode'=>$hotelCode,
                  'InternalCode'=>$InternalCode,
                  'checkIn'=>$checkIn,
                  'checkOut'=>$checkOut,
                  'AdultNum'=>$AdultNum,
                  'RoomType'=>$RoomType,
                  'SeqNo'=>$SeqNo,
                  'flagAvail'=>$flagAvail,
                  'CatgId'=>$CatgId,
                  'CatgName'=>$CatgName,
                  'BFType'=>$BFType,
                  'price'=>$price,
                  'random_number'=>$random_number,
                  'countselect'=>$countselect,
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_BOOK_HOTEL, $xmlRequest);
          //echo $xmlRequest;
          //echo $jsonResult;
          $hotelList = json_decode($jsonResult,TRUE)['BookHotel_Response'];
          //print_r($hotelList);
          $ResNo=$hotelList['ResNo'];
          if($hotelList['ResNo']!= null)
          {
              $ResNo=$hotelList['ResNo'];
              $HBookId=$hotelList['HBookId']['@attributes']['Id'];
              $VoucherNo=$hotelList['HBookId']['@attributes']['VoucherNo'];
              $VoucherDt=$hotelList['HBookId']['@attributes']['VoucherDt'];
              $Status=$hotelList['HBookId']['@attributes']['Status'];
              $HotelId=$hotelList['HBookId']['HotelId'];
              $FromDt=$hotelList['HBookId']['Period']['@attributes']['FromDt'];
              $ToDt=$hotelList['HBookId']['Period']['@attributes']['ToDt'];
              $CatgId=$hotelList['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgId'];
              $CatgName=$hotelList['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgName'];
              $BFType=$hotelList['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['BFType'];
              $ServiceNo=$hotelList['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['ServiceNo'];
              $RoomType=$hotelList['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['RoomType'];
              $SeqNo=$hotelList['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['SeqNo'];
              $TotalPrice=$hotelList['TotalPrice'];
              $GuestName=$hotelList['GuestName'];
              $now=date('Y-m-d H:i:s');

              DAO::executeSql("INSERT INTO tghbookingmg
              (resno,hbookid,voucherno,voucherdt,status,hotelid,fromdt,todt,catgid,catgname,bftype,serviceno,roomtype,seqno,totalprice,guestname, update_dt)
              VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now')");
          }
      //}
      $this->render('form_bookHotel',array(
              'hotelCode'=>$hotelCode
      ));
    }

    public function actionBookHoteltest($hotelCode,$checkIn,$checkOut,$AdultNum,$NumRooms,$CatgId, $national, $currency,$RoomType, $bftype)
    {
      /*$hotelCode=$_POST['hotelCode'];
      $CatgId=$_POST['CatgId'];
      if(isset($_POST['hotelCode']))
      {
          $bookhotel = DAO::queryAllSql("SELECT *
                                                  FROM tghsearchprice
                                                  WHERE property_cd ='".$hotelCode."' AND roomcateg_id ='".$CatgId."'");

          $countselect = count($bookhotel);
          for($c=0;$c<=$countselect;$c++)
          {

            //$InternalCode=$_POST['internalCode'];
            $InternalCode='CL005';
            //$checkIn=$_POST['checkIn'];
            //$checkOut=$_POST['checkOut'];
            //$SeqNo=$_POST['SeqNo'];
            //$AdultNum=$_POST['AdultNum'];
            //$RoomType=$_POST['RoomType'];
            //$flagAvail=$_POST['flagAvail'];
             $flagAvail = 'Y';
            //$CatgId=$_POST['CatgId'];
            //$CatgName=$_POST['CatgName'];
            //$BFType=$_POST['BFType'];
            //$price=$_POST['price'];
             $SeqNo = $c+1;
              $checkIn = $bookhotel[$c]['check_in'];
             $checkOut = $bookhotel[$c]['check_out'];
             $RoomType = $bookhotel[$c]['RoomType'];
             //$CatgId[$c] = $bookhotel[$c]['roomcateg_id'];
             $AdultNum=2;
             $CatgName = $bookhotel[$c]['roomcateg_name'];
             $BFType = $bookhotel[$c]['BFType'];
             $checkIn = $bookhotel[$c]['check_in'];
             $checkOut = $bookhotel[$c]['check_out'];
             $price = $bookhotel[$c]['price'];
          }*/
          //if(isset($_POST['hotelCode']))
          $user_id = $_SESSION['_idghoursmghol__states']['employee_cd'];
          $GuestName='admin';
          $now = date('Y-m-d H:i:s');
          if(isset($hotelCode))
          {
            //$hotelCode=$_POST['hotelCode'];
            //$InternalCode=$_POST['internalCode'];
            $InternalCode='CL005';
            //$checkIn=$_POST['checkIn'];
            //$checkOut=$_POST['checkOut'];
            //$SeqNo=$_POST['SeqNo'];
            $SeqNo=1;
            //$AdultNum=$_POST['AdultNum'];
            //$RoomType=$_POST['RoomType'];
            //$flagAvail=$_POST['flagAvail'];
            $flagAvail = 'Y';
            //$CatgId=$_POST['CatgId'];
            //$CatgName=$_POST['CatgName'];
            //$BFType=$_POST['BFType'];
            //$price=$_POST['price'];

            $digits_needed=13;
            $random_number=''; // set up a blank string
            $count=0;

            while ( $count < $digits_needed ) {
                $random_digit = mt_rand(0, 9);

                $random_number .= $random_digit;
                $count++;
            }
            $OSRefNo = $random_number;
            #cek hotel dan guest
            if($NumRooms<=1){

                  $arrRooms[] = array('numAdults'=>$AdultNum);
                  if($arrRooms[0]['numAdults']==1){
                    $RoomType = 'Single';
                  }
                  if($arrRooms[0]['numAdults']==2){
                    $RoomType = 'Twin';
                  }
                  if($arrRooms[0]['numAdults']==3){
                    $RoomType = 'Triple';
                  }
                  if($arrRooms[0]['numAdults']==4){
                    $RoomType = 'Quad';
                  }
                  $hotels = DAO::queryAllSql("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.price,tghsearchprice.roomprice,
                      tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
                      tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.bftype
                                                          FROM tghsearchprice
                                              INNER JOIN tghproperty
                                                          ON  tghproperty.property_cd = tghsearchprice.property_cd
                                                          WHERE tghsearchprice.property_cd ='".$hotelCode."'
                                                          AND tghsearchprice.check_in = '".$checkIn."'
                                                          AND tghsearchprice.check_out = '".$checkOut."'
                                                          AND tghsearchprice.roomcateg_id ='".$CatgId."'
                                                          AND tghsearchprice.RoomType ='".$RoomType."'
                                                          AND tghsearchprice.bftype = '".$bftype."'
                                                          GROUP BY tghsearchprice.roomcateg_name
                                                          ");
                    $countselect = count($hotels);
                    if($countselect==0){
                      echo "error, please check roomtype and NumRooms";
                      Yii::app()->end();
                    }
                    for($c=0;$c<$countselect;$c++)
                    {
                      $destCountry = $hotels[$c]['country_cd'];
                      $city = $hotels[$c]['city_cd'];
                      $property_name = $hotels[$c]['name'];
                      $RoomType = $hotels[$c]['RoomType'];
                      $CatgName = $hotels[$c]['roomcateg_name'];
                      //$price=$hotels[$c]['price'];
                      $price=$hotels[$c]['roomprice'];
                    }
                  $xmlRequest = $this->renderPartial('req_bookhotel'
                      , array(
                          'nationalities'=>$national,
                          'currency'=>$currency,
                          'hotelCode'=>$hotelCode,
                          'InternalCode'=>$InternalCode,
                          'checkIn'=>$checkIn,
                          'checkOut'=>$checkOut,
                          'AdultNum'=>$AdultNum,
                          'NumRooms'=>$NumRooms,
                          'arrRooms'=>$arrRooms,
                          'RoomType'=>$RoomType,
                          'SeqNo'=>$SeqNo,
                          'flagAvail'=>$flagAvail,
                          'CatgId'=>$CatgId,
                          'CatgName'=>$CatgName,
                          'BFType'=>$bftype,
                          'price'=>$price,
                          'random_number'=>$random_number,
                          'countselect'=>$countselect,
                      ), true);
                    //echo $xmlRequest;
                  $jsonResult = ApiRequestor::post(ApiRequestor::URL_BOOK_HOTEL, $xmlRequest);
                  //echo $jsonResult;
                  $hotelList = json_decode($jsonResult,TRUE)['BookHotel_Response'];
              //}

            //Yii::app()->end();
              //mg/hoteldata/BookHotel&hotelCode=WSASTHBKK000087&InternalCode=CL005&checkIn=2018-10-20&checkOut=2018-10-22&SeqNo=1&AdultNum=2&RoomType=Twin&flagAvail=True

              //echo "<pre>";
              //print_r($hotelList);
              //echo "</pre>";
              $ResNo=$hotelList['ResNo'];
              if(isset($hotelList['ResNo'])) {
                  if ($hotelList['ResNo'] != null) {
                      $ResNo = $hotelList['ResNo'];
                      if(!empty($hotelList['CompleteService']['HBookId']['@attributes']['Id'])){
                        $HBookId = $hotelList['CompleteService']['HBookId']['@attributes']['Id'];
                        $VoucherNo = $hotelList['CompleteService']['HBookId']['@attributes']['VoucherNo'];
                        $VoucherDt = $hotelList['CompleteService']['HBookId']['@attributes']['VoucherDt'];
                        $Status = $hotelList['CompleteService']['HBookId']['@attributes']['Status'];
                        $HotelId = $hotelList['CompleteService']['HBookId']['HotelId'];
                        $FromDt = $hotelList['CompleteService']['HBookId']['Period']['@attributes']['FromDt'];
                        $ToDt = $hotelList['CompleteService']['HBookId']['Period']['@attributes']['ToDt'];
                        $CatgId = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgId'];
                        $CatgName = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgName'];
                        $BFType = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['BFType'];
                        $ServiceNo = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['ServiceNo'];
                        $RoomType = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['RoomType'];
                        $SeqNo = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['SeqNo'];
                        $TotalPrice = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['TotalPrice'];
                        if(!empty($hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['PriceInfomation']['NightPrice'][0]['Accom']['@attributes']['Price']))
                        {
                          $nightprice=$hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['PriceInfomation']['NightPrice'][0]['Accom']['@attributes']['Price'];
                        }
                        else{
                          $nightprice=$hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['PriceInfomation']['NightPrice']['Accom']['@attributes']['Price'];
                        }
                        //$nightprice = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['PriceInfomation']['NightPrice'][0]['Accom']['@attributes']['Price'];
                        DAO::executeSql("INSERT INTO tghbookmg
                  (resno,hbookid,voucherno,voucherdt,status,hotelid,fromdt,todt,catgid,catgname,bftype,serviceno,roomtype,seqno,totalprice,guestname, update_dt,user_id,osrefno,nightprice)
                  VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo','$nightprice')");

                    Yii::app()->user->setFlash('success', "Book Hotel Successfully");
                    $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                      }
                      else{
                          if($NumRooms>1){
                            for($bk=0;$bk<$NumRooms;$bk++){
                              if(!empty($hotelList['UnCompleteService']['HotelList'][$bk])){
                                $HBookId = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['@attributes']['Id'];
                                $VoucherNo = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['@attributes']['VoucherNo'];
                                $VoucherDt = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['@attributes']['VoucherDt'];
                                $Status = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['@attributes']['Status'];
                                $HotelId = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['HotelId'];
                                $FromDt = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['Period']['@attributes']['FromDt'];
                                $ToDt = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['Period']['@attributes']['ToDt'];
                                $CatgId = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgId'];
                                $CatgName = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgName'];
                                $BFType = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['BFType'];
                                $ServiceNo = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['ServiceNo'];
                                $RoomType = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['RoomType'];
                                $SeqNo = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['SeqNo'];
                                $TotalPrice = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['TotalPrice'];

                              }
                              else {
                                $HBookId = $hotelList['UnCompleteService']['HBookId']['@attributes']['Id'];
                                $VoucherNo = $hotelList['UnCompleteService']['HBookId']['@attributes']['VoucherNo'];
                                $VoucherDt = $hotelList['UnCompleteService']['HBookId']['@attributes']['VoucherDt'];
                                $Status = $hotelList['UnCompleteService']['HBookId']['@attributes']['Status'];
                                $HotelId = $hotelList['UnCompleteService']['HBookId']['HotelId'];
                                $FromDt = $hotelList['UnCompleteService']['HBookId']['Period']['@attributes']['FromDt'];
                                $ToDt = $hotelList['UnCompleteService']['HBookId']['Period']['@attributes']['ToDt'];
                                $CatgId = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgId'];
                                $CatgName = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgName'];
                                $BFType = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['BFType'];
                                $ServiceNo = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['ServiceNo'];
                                $RoomType = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['RoomType'];
                                $SeqNo = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['SeqNo'];
                                $TotalPrice = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['TotalPrice'];

                              }
                              DAO::executeSql("INSERT INTO tghbookmg
                        (resno,hbookid,voucherno,voucherdt,status,hotelid,fromdt,todt,catgid,catgname,bftype,serviceno,roomtype,seqno,totalprice,guestname, update_dt,user_id,osrefno)
                        VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo')");

                          Yii::app()->user->setFlash('success', "Book Hotel Successfully");
                          $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                            }
                          }else{
                            $HBookId = $hotelList['UnCompleteService']['HBookId']['@attributes']['Id'];
                            $VoucherNo = $hotelList['UnCompleteService']['HBookId']['@attributes']['VoucherNo'];
                            $VoucherDt = $hotelList['UnCompleteService']['HBookId']['@attributes']['VoucherDt'];
                            $Status = $hotelList['UnCompleteService']['HBookId']['@attributes']['Status'];
                            $HotelId = $hotelList['UnCompleteService']['HBookId']['HotelId'];
                            $FromDt = $hotelList['UnCompleteService']['HBookId']['Period']['@attributes']['FromDt'];
                            $ToDt = $hotelList['UnCompleteService']['HBookId']['Period']['@attributes']['ToDt'];
                            $CatgId = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgId'];
                            $CatgName = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgName'];
                            $BFType = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['BFType'];
                            $ServiceNo = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['ServiceNo'];
                            $RoomType = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['RoomType'];
                            $SeqNo = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['SeqNo'];
                            $TotalPrice = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['TotalPrice'];
                            DAO::executeSql("INSERT INTO tghbookmg
                      (resno,hbookid,voucherno,voucherdt,status,hotelid,fromdt,todt,catgid,catgname,bftype,serviceno,roomtype,seqno,totalprice,guestname, update_dt,user_id,osrefno)
                      VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo')");

                        Yii::app()->user->setFlash('success', "Book Hotel Successfully");
                        $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                          }
                        }
                      //$GuestName = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['PaxInformation']['GuestName'];

    /*echo ("INSERT INTO tghbookmg
                (ResNo,HBookId,VoucherNo,VoucherDt,Status,HotelId,FromDt,ToDt,CatgId,CatgName,BFType,ServiceNo,RoomType,SeqNo,TotalPrice,GuestName, update_dt,user_id,OSRefNo)
                VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo')");
    */

                      //Yii::app()->end();
                  }
              }
            }
            else{
                  $avgAdultNumInRoom = ceil($AdultNum/$NumRooms);
                  $arrRooms = array();
                  for($i=0; $i<$NumRooms; $i++) {
                        /*$sisaGuest = 0;
                        if($i == ($NumRooms-1) ) {
                          $sisaGuest = $AdultNum - ($avgAdultNumInRoom * $NumRooms);
                        }*/
                      $arrRooms[] = array('numAdults'=>$avgAdultNumInRoom);
                      //echo "<pre>";
                      //print_r($arrRooms);
                      //echo "</pre>";

                      //Yii::app()->end();
                  }

                  //echo count($arrRooms);
                  $hitungkamar=count($arrRooms);
                  for($kr=0;$kr<$hitungkamar;$kr++){
                    if($arrRooms[$kr]['numAdults']==1){
                      $RoomType = 'Single';
                    }
                    if($arrRooms[$kr]['numAdults']==2){
                      $RoomType = 'Twin';
                    }
                    if($arrRooms[$kr]['numAdults']==3){
                      $RoomType = 'Triple';
                    }
                    if($arrRooms[$kr]['numAdults']==4){
                      $RoomType = 'Quad';
                    }
                    /*echo ("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.price,tghsearchprice.roomprice,
                        tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
                        tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType
                                                            FROM tghsearchprice
                                                INNER JOIN tghproperty
                                                            ON  tghproperty.property_cd = tghsearchprice.property_cd
                                                            WHERE tghsearchprice.property_cd ='".$hotelCode."'
                                                            AND tghsearchprice.check_in = '".$checkIn."'
                                                            AND tghsearchprice.check_out = '".$checkOut."'
                                                            AND tghsearchprice.numrooms = '".$NumRooms."'
                                                            AND tghsearchprice.roomcateg_id ='".$CatgId."'
                                                            AND tghsearchprice.RoomType ='".$RoomType."'
                                                            GROUP BY tghsearchprice.roomcateg_name
                                                            ");*/
                    $hotels = DAO::queryAllSql("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.price,tghsearchprice.roomprice,
                        tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
                        tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType
                                                            FROM tghsearchprice
                                                INNER JOIN tghproperty
                                                            ON  tghproperty.property_cd = tghsearchprice.property_cd
                                                            WHERE tghsearchprice.property_cd ='".$hotelCode."'
                                                            AND tghsearchprice.check_in = '".$checkIn."'
                                                            AND tghsearchprice.check_out = '".$checkOut."'
                                                            AND tghsearchprice.numrooms = '".$NumRooms."'
                                                            AND tghsearchprice.roomcateg_id ='".$CatgId."'
                                                            AND tghsearchprice.RoomType ='".$RoomType."'
                                                            GROUP BY tghsearchprice.roomcateg_name
                                                            ");

                    $countselect = count($hotels);
                    for($c=0;$c<$countselect;$c++)
                    {
                      $destCountry = $hotels[$c]['country_cd'];
                      $city = $hotels[$c]['city_cd'];
                      $property_name = $hotels[$c]['name'];
                      $RoomType = $hotels[$c]['RoomType'];
                      $CatgName = $hotels[$c]['roomcateg_name'];
                      $BFType=$hotels[$c]['BFType'];
                      //$price=$hotels[$c]['price'];
                      $price=$hotels[$c]['roomprice'];
                    }
                  }
                  $xmlRequest = $this->renderPartial('req_bookhotel'
                      , array(
                          'nationalities'=>$national,
                          'currency'=>$currency,
                          'hotelCode'=>$hotelCode,
                          'InternalCode'=>$InternalCode,
                          'checkIn'=>$checkIn,
                          'checkOut'=>$checkOut,
                          'AdultNum'=>$AdultNum,
                          'NumRooms'=>$NumRooms,
                          'arrRooms'=>$arrRooms,
                          'RoomType'=>$RoomType,
                          'SeqNo'=>$SeqNo,
                          'flagAvail'=>$flagAvail,
                          'CatgId'=>$CatgId,
                          'CatgName'=>$CatgName,
                          'BFType'=>$BFType,
                          'price'=>$price,
                          'random_number'=>$random_number,
                          'countselect'=>$countselect,
                      ), true);
                    //echo $xmlRequest;
                  $jsonResult = ApiRequestor::post(ApiRequestor::URL_BOOK_HOTEL, $xmlRequest);
                  //echo $jsonResult;
                  $hotelList = json_decode($jsonResult,TRUE)['BookHotel_Response'];
              //}

            //Yii::app()->end();
              //mg/hoteldata/BookHotel&hotelCode=WSASTHBKK000087&InternalCode=CL005&checkIn=2018-10-20&checkOut=2018-10-22&SeqNo=1&AdultNum=2&RoomType=Twin&flagAvail=True

              //echo "<pre>";
              //print_r($hotelList);
              //echo "</pre>";
              $ResNo=$hotelList['ResNo'];
              if(isset($hotelList['ResNo'])) {
                  if ($hotelList['ResNo'] != null) {
                      $ResNo = $hotelList['ResNo'];
                      if(!empty($hotelList['CompleteService']['HBookId']['@attributes']['Id'])){
                        $HBookId = $hotelList['CompleteService']['HBookId']['@attributes']['Id'];
                        $VoucherNo = $hotelList['CompleteService']['HBookId']['@attributes']['VoucherNo'];
                        $VoucherDt = $hotelList['CompleteService']['HBookId']['@attributes']['VoucherDt'];
                        $Status = $hotelList['CompleteService']['HBookId']['@attributes']['Status'];
                        $HotelId = $hotelList['CompleteService']['HBookId']['HotelId'];
                        $FromDt = $hotelList['CompleteService']['HBookId']['Period']['@attributes']['FromDt'];
                        $ToDt = $hotelList['CompleteService']['HBookId']['Period']['@attributes']['ToDt'];
                        $CatgId = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgId'];
                        $CatgName = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgName'];
                        $BFType = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['BFType'];
                        //echo "wonbg";
                          $sn=sizeof($hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']);
                          //echo $sn;
                          if($sn>1){
                            for($so=0;$so<$sn;$so++){
                                $SeqNo = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom'][$so]['@attributes']['SeqNo'];
                                $TotalPrice = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom'][$so]['TotalPrice'];
                                $nightprice = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom'][$so]['PriceInfomation']['NightPrice'][$so]['Accom']['@attributes']['Price'];
                                $ServiceNo = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom'][$so]['@attributes']['ServiceNo'];
                                $RoomType = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom'][$so]['@attributes']['RoomType'];
                                DAO::executeSql("INSERT INTO tghbookmg
                          (resno,hbookid,voucherno,voucherdt,status,hotelid,fromdt,todt,catgid,catgname,bftype,serviceno,roomtype,seqno,totalprice,guestname, update_dt,user_id,osrefno,nightprice)
                          VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo','$nightprice')");
                            }
                            Yii::app()->user->setFlash('success', "Book Hotel Successfully");
                            $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                          }
                          else{
                            $SeqNo = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['SeqNo'];
                            $TotalPrice = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['TotalPrice'];
                            $nightprice = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['PriceInfomation']['NightPrice']['Accom']['@attributes']['Price'];
                            DAO::executeSql("INSERT INTO tghbookmg
                      (resno,hbookid,voucherno,voucherdt,status,hotelid,fromdt,todt,catgid,catgname,bftype,serviceno,roomtype,seqno,totalprice,guestname, update_dt,user_id,osrefno,nightprice)
                      VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo','$OSRefNo','$nightprice')");
                        Yii::app()->user->setFlash('success', "Book Hotel Successfully");
                        $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                          }






                      }
                      else{
                          if($NumRooms>1){
                            for($bk=0;$bk<$NumRooms;$bk++){
                              if(!empty($hotelList['UnCompleteService']['HotelList'][$bk])){
                                $HBookId = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['@attributes']['Id'];
                                $VoucherNo = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['@attributes']['VoucherNo'];
                                $VoucherDt = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['@attributes']['VoucherDt'];
                                $Status = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['@attributes']['Status'];
                                $HotelId = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['HotelId'];
                                $FromDt = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['Period']['@attributes']['FromDt'];
                                $ToDt = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['Period']['@attributes']['ToDt'];
                                $CatgId = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgId'];
                                $CatgName = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgName'];
                                $BFType = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['BFType'];
                                $ServiceNo = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['ServiceNo'];
                                $RoomType = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['RoomType'];
                                $SeqNo = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['SeqNo'];
                                $TotalPrice = $hotelList['UnCompleteService']['HotelList'][$bk]['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['TotalPrice'];

                              }
                              else {
                                $HBookId = $hotelList['UnCompleteService']['HBookId']['@attributes']['Id'];
                                $VoucherNo = $hotelList['UnCompleteService']['HBookId']['@attributes']['VoucherNo'];
                                $VoucherDt = $hotelList['UnCompleteService']['HBookId']['@attributes']['VoucherDt'];
                                $Status = $hotelList['UnCompleteService']['HBookId']['@attributes']['Status'];
                                $HotelId = $hotelList['UnCompleteService']['HBookId']['HotelId'];
                                $FromDt = $hotelList['UnCompleteService']['HBookId']['Period']['@attributes']['FromDt'];
                                $ToDt = $hotelList['UnCompleteService']['HBookId']['Period']['@attributes']['ToDt'];
                                $CatgId = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgId'];
                                $CatgName = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgName'];
                                $BFType = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['BFType'];
                                $ServiceNo = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['ServiceNo'];
                                $RoomType = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['RoomType'];
                                $SeqNo = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['SeqNo'];
                                $TotalPrice = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['TotalPrice'];

                              }
                              DAO::executeSql("INSERT INTO tghbookmg
                        (resno,hbookid,voucherno,voucherdt,status,hotelid,fromdt,todt,catgid,catgname,bftype,serviceno,roomtype,seqno,totalprice,guestname, update_dt,user_id,osrefno)
                        VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo')");

                          Yii::app()->user->setFlash('success', "Book Hotel Successfully");
                          $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                            }
                          }else{
                            $HBookId = $hotelList['UnCompleteService']['HBookId']['@attributes']['Id'];
                            $VoucherNo = $hotelList['UnCompleteService']['HBookId']['@attributes']['VoucherNo'];
                            $VoucherDt = $hotelList['UnCompleteService']['HBookId']['@attributes']['VoucherDt'];
                            $Status = $hotelList['UnCompleteService']['HBookId']['@attributes']['Status'];
                            $HotelId = $hotelList['UnCompleteService']['HBookId']['HotelId'];
                            $FromDt = $hotelList['UnCompleteService']['HBookId']['Period']['@attributes']['FromDt'];
                            $ToDt = $hotelList['UnCompleteService']['HBookId']['Period']['@attributes']['ToDt'];
                            $CatgId = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgId'];
                            $CatgName = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgName'];
                            $BFType = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['BFType'];
                            $ServiceNo = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['ServiceNo'];
                            $RoomType = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['RoomType'];
                            $SeqNo = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['SeqNo'];
                            $TotalPrice = $hotelList['UnCompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['TotalPrice'];
                            DAO::executeSql("INSERT INTO tghbookmg
                      (resno,hbookid,voucherno,voucherdt,status,hotelid,fromdt,todt,catgid,catgname,bftype,serviceno,roomtype,seqno,totalprice,guestname, update_dt,user_id,osrefno)
                      VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo')");

                        Yii::app()->user->setFlash('success', "Book Hotel Successfully");
                        $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                          }
                        }
                      //$GuestName = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['PaxInformation']['GuestName'];

/*echo ("INSERT INTO tghbookmg
                (ResNo,HBookId,VoucherNo,VoucherDt,Status,HotelId,FromDt,ToDt,CatgId,CatgName,BFType,ServiceNo,RoomType,SeqNo,TotalPrice,GuestName, update_dt,user_id,OSRefNo)
                VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo')");
*/

                      //Yii::app()->end();
                  }
              }
            }
          }
      $this->render('form_bookHotel',array(
              'hotelCode'=>$hotelCode,
              'jsonResult'=>$jsonResult
      ));
    }

    public function actionGetrsvninfo($id)
    {
        $model=Bookmg::model()->findByPk($id);
        $jsonResult='';
        //print_r($model);
      //if(isset($_POST['ResNo']))
        if(isset($id))
      {
        $ResNo=$model->resno;
        $OSRefNo=$model->osrefno;
        $HBookId=$model->hbookid;
          $xmlRequest = $this->renderPartial('req_getrsvninfo'
              , array(
                  'ResNo'=>$ResNo,
                  'OSRefNo'=>$OSRefNo,
                  'HBookId'=>$HBookId
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_RSVN_INFO, $xmlRequest);
          //echo $jsonResult;
      }
      $this->render('form_getrsvninfo',array(
              'ResNo'=>$ResNo,
              'jsonResult'=>$jsonResult
      ));
    }
    public function actionAcceptBooking($id)
    {
        $model=Bookmg::model()->findByPk($id);

        //if(isset($_POST['ResNo']))
        if(isset($id))
      {
          $ResNo=$model->resno;
        //$ResNo=$_POST['ResNo'];
        //mg/hoteldata/AcceptBooking&ResNo=WWMGMA180637503
          $xmlRequest = $this->renderPartial('req_acceptbooking'
              , array(
                  'ResNo'=>$ResNo
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_ACCEPT_BOOKING, $xmlRequest);
          DAO::executeSql("UPDATE tghbookmg SET status='CONF' WHERE booking_id='$id'");
          //echo $jsonResult;
      }
      $this->render('form_acceptbooking',array(
              'ResNo'=>$ResNo,
              'jsonResult'=>$jsonResult
      ));
    }

    public function actionGetcancelpolicy($id)
    {
        $model=Bookmg::model()->findByPk($id);
        $ResNo=$model->resno;
        $OSRefNo=$model->osrefno;
        $HBookId=$model->hbookid;
      /*if(isset($_POST['ResNo']))
      {
          $ResNo=$_POST['ResNo'];
          $OSRefNo=$_POST['OSRefNo'];
          $HBookId=$_POST['HBookId'];

      }*/

      $xmlRequest = $this->renderPartial('req_getcancelpolicy'
          , array(
              'ResNo'=>$ResNo,
              'OSRefNo'=>$OSRefNo,
              'HBookId'=>$HBookId
          ), true);
      $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_CANCEL_POLICY, $xmlRequest);
      //echo $jsonResult;
      $this->render('form_getcancelpolicy',array(
              'ResNo'=>$ResNo,
              'OSRefNo'=>$OSRefNo,
              'HBookId'=>$HBookId,
              'id'=>$id,
              'jsonResult'=>$jsonResult

      ));
    }

    public function actionAmendHotel($id=null)
    {
        $model=Bookmg::model()->findByPk($id);
        $ResNo='';
        $jsonResult='';
        //print_r($_POST);
      if(isset($_POST['ResNo']))
      {
          $hotelCode=$_POST['hotelCode'];
          $InternalCode=$_POST['internalCode'];
          $checkIn=$_POST['checkIn'];
          $checkOut=$_POST['checkOut'];
          //$SeqNo=$_POST['SeqNo'];
          $AdultNum=$_POST['AdultNum'];
          $RoomType=$_POST['RoomType'];
          $ResNo=$_POST['ResNo'];
          $HBookId=$_POST['HBookId'];
        //mg/hoteldata/Amendhotel&hotelCode=WSASTHBKK000087&InternalCode=CL005&checkIn=2018-12-20&checkOut=2018-12-22&AdultNum=2&RoomType=Twin&ResNo=WWMGMA180637503&HBookId=HBMA1806000254
          $xmlRequest = $this->renderPartial('req_amendhotel'
              , array(
                  'hotelCode'=>$hotelCode,
                  'InternalCode'=>$InternalCode,
                  'checkIn'=>$checkIn,
                  'checkOut'=>$checkOut,
                  'AdultNum'=>$AdultNum,
                  'RoomType'=>$RoomType,
                  'ResNo'=>$ResNo,
                  'HBookId'=>$HBookId
              ), true);
          //echo $xmlRequest;
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_AMEND_HOTEL, $xmlRequest);
          //echo $jsonResult;
      }
      $this->render('form_AmendHotel',array(
              'ResNo'=>$ResNo,
              'model'=>$model,
            'jsonResult'=>$jsonResult

      ));
    }

    public function actionCancelrsvn($id)
    {
        $model=Bookmg::model()->findByPk($id);
      //r=mg/hoteldata/Cancelrsvn&ResNo=WWMGMA180637503&OSRefNo=9923012912335&HBookId=HBMA1806000254
      //if(isset($_POST['ResNo']))
        if(isset($id))
        {
            $ResNo=$model->resno;
            $OSRefNo=$model->osrefno;
            $HBookId=$model->hbookid;
          $xmlRequest = $this->renderPartial('req_CancelReservation'
              , array(
                  'ResNo'=>$ResNo,
                  'OSRefNo'=>$OSRefNo,
                  'HBookId'=>$HBookId
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_CANCEL_RESERVATION, $xmlRequest);
          //echo $jsonResult;
          $hotelList = json_decode($jsonResult,TRUE)['CancelReservation_Response'];
                /*echo "<pre>";
                print_r($hotelList);
                echo "</pre>";*/
            $ResNo=$hotelList['ResNo'];
            if(isset($hotelList['CancelResult'])){
                //echo 'test';
                if($hotelList['CancelResult'] == "True")
                {

                    //echo 'hapus';
                    //$this->loadModel($id)->delete();
                   // echo ("UPDATE tghbookmg SET Status='CANCELCONF' WHERE booking_id='$id')");
                    DAO::executeSql("UPDATE tghbookmg SET Status='CANCELCONF' WHERE booking_id='$id'");
                    Yii::app()->user->setFlash('success', "Cancel Book Hotel Successfully");
                    $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                }
                else{
                    //echo $hotelList['CancelResult'];
                    Yii::app()->user->setFlash('error', "Cancel Book Hotel Failed");
                    $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                }
            }
            else{
                //echo $hotelList['CancelResult'];
                Yii::app()->user->setFlash('error', "Cancel Book Hotel Failed");
                $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
            }


      }
      $this->render('form_cancelrsvn',array(
              'ResNo'=>$ResNo
      ));
    }

    public function actionGethoteldetailfac()
    {
      $myFacility='';
        //$hotelCode = $_GET['hotelCode'];
        $hotelsdata = DAO::queryAllSql("SELECT property_cd
                                              FROM tghsearchprice group BY property_cd  ");
        foreach ($hotelsdata as $listdata) {
           $hotelCode = $listdata['property_cd'];

        $xmlRequest = $this->renderPartial('req_gethoteldetail'
              , array(
                  'hotelCode'=>$hotelCode
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_HOTEL_DETAIL, $xmlRequest);

          //echo $xmlRequest;


          $hotelResponse = json_decode($jsonResult,TRUE);

          echo "<pre>";
          print_r($hotelResponse);
          echo "</pre>";
          $hotelList = array();
          if(isset($hotelResponse['GetHotelDetail_Response']['Facility'])){
                $FacilityList = $hotelResponse['GetHotelDetail_Response']['Facility'];
          }
          else {
              $FacilityList = NULL;
          }
          //print_r($FacilityList);


          /*$hotelsdata = DAO::queryAllSql("SELECT property_cd
                                                FROM tghsearchprice group BY property_cd  ");

          //print_r($hotelsdata);
          $feat='';
          $msfeat='';
          $feat = DAO::queryAllSql("SELECT name
                                                FROM tghtempfacility group BY name  ");
          //print_r($feat);

          $msfeat = DAO::queryAllSql("SELECT features_name,prop_features_id FROM tghmspropertyfeatures");
          //print_r($msfeat);
          if(!empty($FacilityList)){
            foreach ($FacilityList as $listfacility) {
                //echo $listfacility['@attributes']['name'].'<br>';
                foreach ($msfeat as $listmsfeat) {
                    if($listfacility['@attributes']['name']==$listmsfeat['features_name']){
                      //echo $listmsfeat['prop_features_id'].'<br>';
                      //echo ("INSERT INTO tghpropertyfeatures(property_id,prop_features_id) VALUES ('" . $hotelCode . "','" . $listmsfeat['prop_features_id'] . "')");
                      DAO::executeSql("INSERT INTO tghpropertyfeatures(property_id,prop_features_id) VALUES ('" . $hotelCode . "','" . $listmsfeat['prop_features_id'] . "')");
                    }

                }
            }
          }

          $now=date('Y-m-d H:i:s');

                  foreach ($feat as $listfeat) {
                      //echo $listfeat['name'];
                      //DAO::executeSql("INSERT INTO tghmspropertyfeatures(features_name,create_dt,create_by,update_dt,update_by) VALUES ('" . $listfeat['name'] . "','$now',1,'$now',1)");
                      /*if ($listfeat['name'] == $listmsfeat['features_name']) {
                          echo "string";
                      }
                      else{
                          //DAO::executeSql("INSERT INTO tghmspropertyfeatures(features_name,create_dt,create_by,update_dt,update_by) VALUES ('" . $listfeat['name'] . "','$now',1,'$now',1)");
                      }
                  }*/


          $msfeat = DAO::queryAllSql("SELECT features_name,prop_features_id FROM tghmspropertyfeatures");
          $hotelfeat = DAO::queryAllSql("SELECT property_id,prop_features_id FROM tghpropertyfeatures");
          //print_r($msfeat);
          $temp_fc=array();
          if(count($hotelfeat)==NULL){
            if($FacilityList!=NULL){
              foreach ($FacilityList as $listFac) {
                $temp_fc[] = $listFac['@attributes']['name'];
                foreach ($msfeat as $listmsfeat) {
                  $listmsfeat['features_name'];
                  if ($listFac['@attributes']['name']==$listmsfeat['features_name']){
                        echo $listmsfeat['features_name'].'-';
                        echo $listmsfeat['prop_features_id'];
                        DAO::executeSql("INSERT INTO tghpropertyfeatures(property_id,prop_features_id) VALUES ('" . $hotelCode . "','" . $listmsfeat['prop_features_id'] . "')");
                  }
                }
              }
            }
          }

          else{
            $temp_fc[]='NONE';
          }

          $myJSON = json_encode($hotelList);
          $myFacility = json_encode($temp_fc);
        }

        Yii::app()->end();

      $this->render('form_gethoteldetail',array(
              'hotelCode'=>$hotelCode,
              'myJSON'=>$myJSON,
              'myFacility'=>$myFacility
      ));
    }


    public function loadModel($id)
    {
        $model=Bookmg::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}
