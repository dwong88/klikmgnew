<?php
/**
 * Created by PhpStorm.
 * User: wiyantotan
 * Date: 30/05/18
 * Time: 19:07
 */
header('Access-Control-Allow-Origin: *');
class HoteldataController extends Controller
{
  public $layout='//layouts/column1';
    public function actionGethoteldetail($hotelCode)
    {
      //print_r($_POST);
      //$hotelCode ='';
      //if(isset($_POST['hotelCode']))
        $myFacility='';
      if(isset($_GET['hotelCode']))
      {
        //$hotelCode = $_POST['hotelCode'];
        $hotelCode = $_GET['hotelCode'];
        $xmlRequest = $this->renderPartial('req_gethoteldetail'
              , array(
                  'hotelCode'=>$hotelCode
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_HOTEL_DETAIL, $xmlRequest);
          $FacilityList='';
          //echo $jsonResult;
          $hotelList = json_decode($jsonResult,TRUE)['GetHotelDetail_Response'];
          /*echo "<pre>";
          print_r($hotelList);
          echo "</pre>";*/
          if(isset($hotelList['Facility'])){
              $FacilityList = json_decode($jsonResult,TRUE)['GetHotelDetail_Response']['Facility'];
              //print_r($FacilityList);
          }


          $hotelsdata = DAO::queryAllSql("SELECT property_cd
                                                FROM tghsearchprice group BY property_cd  ");

          //print_r($hotelsdata);
          #buat test besok
          /*foreach($hotelsdata as $datahotel){
                $datahotel['property_cd'];
              $xmlRequest = $this->renderPartial('req_gethoteldetail'
                  , array(
                      'hotelCode'=>$datahotel
                  ), true);
              $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_HOTEL_DETAIL, $xmlRequest);
              $FacilityList='';
              //echo $jsonResult;
              $temp_fc=array();
              //print_r($FacilityList);
              if($FacilityList!=NULL){
                  foreach ($FacilityList as $listFac) {
                      $temp_fc[] = $listFac['@attributes']['name'];
                  }
                  echo $temp_fc;
              }
          }*/

          $temp_fc=array();
          //print_r($FacilityList);
          if($FacilityList!=NULL){
            foreach ($FacilityList as $listFac) {
              $temp_fc[] = $listFac['@attributes']['name'];
            }
          }

          $myJSON = json_encode($hotelList);
          $myFacility = json_encode($temp_fc);
          //print_r($myFacility);
          //$roomcateg_id = $listHotel['RoomCateg']['@attributes']['Code'];
          //echo $myJSON;
          //Yii::app()->end();
      }
      $this->render('form_gethoteldetail',array(
              'hotelCode'=>$hotelCode,
              'myJSON'=>$myJSON,
              'myFacility'=>$myFacility
      ));
    }

    public function actionViewhoteldetail($hotelCode,$checkIn,$checkOut,$AdultNum,$NumRooms)
    {
      //print_r($_GET);
      //$hotelCode ='';
      //if(isset($_POST['hotelCode']))
      if(isset($_GET['hotelCode']))
      {
        //$hotelCode = $_POST['hotelCode'];
        $hotelCode = $_GET['hotelCode'];

        /*$hotels = DAO::queryAllSql("SELECT country_cd, city_cd
                                                FROM tghproperty WHERE property_cd='".$hotelCode."'");*/


/*        echo ("SELECT Tghproperty.country_cd as country_cd,Tghproperty.city_cd as city_cd,Tghproperty.property_name as name,tghsearchprice.price,
            Tghproperty.gmaps_latitude,Tghproperty.gmaps_longitude,Tghproperty.addressline1 as displayAddress,Tghproperty.addressline1 as address,
            tghsearchprice.roomcateg_name as RoomType
                                                FROM tghsearchprice
                                    INNER JOIN tghproperty
                                                ON  Tghproperty.property_cd = tghsearchprice.property_cd
                                                WHERE tghsearchprice.property_cd='".$hotelCode."'
                                                AND tghsearchprice.check_in = '".$checkIn."'
                                                AND tghsearchprice.check_out = '".$checkOut."'
                                                GROUP BY tghsearchprice.roomcateg_name
                                                ");
*/
        $hotels = DAO::queryAllSql("SELECT Tghproperty.country_cd as country_cd,Tghproperty.city_cd as city_cd,Tghproperty.property_name as name,tghsearchprice.price,
            Tghproperty.gmaps_latitude,Tghproperty.gmaps_longitude,Tghproperty.addressline1 as displayAddress,Tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
            tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType
                                                FROM tghsearchprice
                                    INNER JOIN tghproperty
                                                ON  Tghproperty.property_cd = tghsearchprice.property_cd
                                                WHERE tghsearchprice.property_cd ='".$hotelCode."'
                                                AND tghsearchprice.check_in = '".$checkIn."'
                                                AND tghsearchprice.check_out = '".$checkOut."'
                                                GROUP BY tghsearchprice.roomcateg_name
                                                ");

        $countselect = count($hotels);
        for($c=0;$c<$countselect;$c++)
        {
          $destCountry = $hotels[$c]['country_cd'];
          $city = $hotels[$c]['city_cd'];
          $property_name = $hotels[$c]['name'];
        }


        $flagAvail =1;
        //Yii::app()->end();
        if($NumRooms<=1){

          $arrRooms[] = array('numAdults'=>$NumRooms);
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
                  $arrRooms[] = array('numAdults'=>$avgAdultNumInRoom);
                  //print_r($arrRooms);

                  //Yii::app()->end();
              }
          }
        $xmlRequest = $this->renderPartial('req_searchhotelionic'
              , array(
                  'destCountry'=>$destCountry,
                  'city'=>$city,
                  'hotelCode'=>$hotelCode,
                  'hotelCode'=>$hotelCode,
                  'checkIn'=>$checkIn,
                  'checkOut'=>$checkOut,
                  'AdultNum'=>$AdultNum,
                  'NumRooms'=>$NumRooms,
                  'arrRooms'=>$arrRooms,
                  'flagAvail'=>$flagAvail
              ), true);
          //$jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_HOTEL_DETAIL, $xmlRequest);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
          $hotelList='';
          if(isset($jsonResult['SearchHotel_Response']['Hotel'])){
              $hotelList = json_decode($jsonResult,TRUE)['SearchHotel_Response']['Hotel']['@attributes'];
          }

          $myJSON = json_encode($hotelList);
          /*$FacilityList = json_decode($jsonResult,TRUE)['GetHotelDetail_Response']['Facility'];
          //print_r($hotelList);
          if($FacilityList!=NULL){
            foreach ($FacilityList as $listFac) {
              $temp_fc[] = $listFac['@attributes']['name'];
            }
          }

          $myJSON = json_encode($hotelList);
          $myFacility = json_encode($temp_fc);*/
          //$roomcateg_id = $listHotel['RoomCateg']['@attributes']['Code'];
          //echo $myJSON;
          //Yii::app()->end();
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
              echo $arrRooms[] = array('numAdults'=>$NumRooms);
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
                 $AdultNums = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                 $ChildNums = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                 $promo_name = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                 $promo_value = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                 $promo_code= $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];

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
                       $AdultNums = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                       $ChildNums = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                       $promo_name = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                       $promo_value = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                       $promo_code = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];

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

                             $AdultNums = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                             $ChildNums = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                             $promo_name = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                             $promo_value = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                             $promo_code = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];

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

    public function actionSearchhotel()
    {
        //
        //print_r($_POST);
        $destCity='';
        $hotels='';
        $AdultNum='';
        $destCity='';
        $check_in='';
        $check_out='';
        $NumRooms='';
        $countselecthotels='';
        $hotelCode='';
        $guest='';

        //echo "string";
        //print_r($_REQUEST);
        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
        if(isset($_POST['city'])) {
            //$destCountry=$_POST['destCountry'];
            /*$destinasi= (explode(",",$_POST['city']));
            $destCity=explode(" ",strtolower($destinasi[0]));*/

            $destCity = $_POST['city'];
            //$hotelCode=$_POST['hotelCode'];
            //$rommCatCode=$_POST['rommCatCode'];
            $duration = $_POST['duration'];
            $tempcheckIn = $_POST['checkin'];
            //$checkOut=$_POST['checkOut'];

            $checkIn = substr($tempcheckIn, 0, 10);
            //$newDate = date("Y-m-d", strtotime($originalDate));

            $tambahhari = '+' . $duration . 'day';
            $newdate = strtotime($tambahhari, strtotime($checkIn));
            $checkOut = date('Y-m-d', $newdate);

            //echo $checkOut;

            //$AdultNum=$_POST['AdultNum'];
            if(isset($_POST['guest'])){
                $AdultNum = $_POST['guest'];
            }

            //$ChildNum=$_POST['ChildNum'];
            //$NumRooms=$_POST['NumRooms'];
            $NumRooms = $_POST['room'];

            if ($NumRooms == NULL || $NumRooms == 0) {
                $NumRooms = 1;
            }
            if ($AdultNum == NULL || $AdultNum == 0) {
                $AdultNum = 1;
            }

            $jumlah_tamu = $AdultNum;
            $check_in = $checkIn;
            $check_out = $checkOut;
            $now = date('Y-m-d H:i:s');
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
                                                  WHERE location_name = '" . $destCity . "'");
            $countsearch = count($searchloc);
            for ($cs = 0; $cs < $countsearch; $cs++) {
                $location_type = $searchloc[$cs]['location_type'];
                $location_code = $searchloc[$cs]['location_code'];

                #query kondisi dari search location_type
                if ($location_type == 2) {
                    $country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                        FROM tghproperty
                                                        WHERE property_name = '" . $destCity . "'");
                }

                if ($location_type == 3) {
                    $country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                        FROM tghcitymg
                                                        WHERE city_cd ='" . $location_code . "'");
                }
                DAO::executeSql("INSERT INTO tghsearchsession (location_code,location_type,check_in,check_out,jumlah_kamar,jumlah_tamu, update_dt)VALUES('$location_code','$location_type','$check_in','$check_out','$NumRooms','$jumlah_tamu','$now')");

            }
            //  Yii::app()->end();
            $countselect = count($country);
            for ($c = 0; $c < $countselect; $c++) {
                $destCountry = $country[$c]['country_cd'];
                $city = $country[$c]['city_cd'];
            }
            //Yii::app()->end();
            if ($NumRooms <= 1) {

                $arrRooms[] = array('numAdults' => $NumRooms);
                //print_r($arrRooms);
            } else {
                $avgAdultNumInRoom = ceil($AdultNum / $NumRooms);
                $arrRooms = array();
                for ($i = 0; $i < $NumRooms; $i++) {
                    /*$sisaGuest = 0;
                    if($i == ($NumRooms-1) ) {
                      $sisaGuest = $AdultNum - ($avgAdultNumInRoom * $NumRooms);
                    }*/
                    $arrRooms[] = array('numAdults' => $avgAdultNumInRoom);
                    //print_r($arrRooms);

                    //Yii::app()->end();
                }
            }
            $xmlRequest = $this->renderPartial('req_searchhotelionic'
                , array(
                    'destCountry' => $destCountry,
                    'city' => $city,
                    'hotelCode' => $hotelCode,
                    //'rommCatCode'=>$rommCatCode,
                    'checkIn' => $checkIn,
                    'checkOut' => $checkOut,
                    'AdultNum' => $AdultNum,
                    'NumRooms' => $NumRooms,
                    'arrRooms' => $arrRooms
                ), true);

            $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
            //echo $jsonResult;
            if(isset($jsonResult['SearchHotel_Response']['Hotel'])){
                $hotelList = json_decode($jsonResult, TRUE)['SearchHotel_Response']['Hotel'];
            }

            //print_r($hotelList);

            //Yii::app()->end();
            #looping json
            if(isset($hotelList)){
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
                if ($listHotel['RoomCateg']['@attributes'] != Null) {
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
                    $AdultNums = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                    $ChildNums = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                    $promo_name = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                    $promo_value = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                    $promo_code = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];

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
                            $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice'];
                            $roomtype_name = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TypeName'];
                            $roomtype_numrooms = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['NumRooms'];
                            $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['TotalPrice'];
                            $roomtype_avrNightPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['avrNightPrice'];
                            $roomtype_RTGrossPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['RTGrossPrice'];
                            $roomtype_RTCommPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['RTCommPrice'];
                            $roomtype_RTNetPrice = $listHotel['RoomCateg'][$r]['RoomType']['@attributes']['RTNetPrice'];
                            $AdultNums = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                            $ChildNums = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                            $promo_name = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                            $promo_value = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                            $promo_code = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];

                            /*echo "debug david"."<br>";
                            echo $roomtype_numrooms."<br>";
                            echo $NumRooms."<br>";*/
                            if ($roomtype_numrooms >= $NumRooms && $avail == 1) {

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
                        } else {
                            for ($rt = 0; $rt < $RTs; $rt++) {
                                $roomtype_name = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['TypeName'];
                                $roomtype_numrooms = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['NumRooms'];
                                $roomtype_totalprice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['TotalPrice'];
                                $roomtype_avrNightPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['avrNightPrice'];
                                $roomtype_RTGrossPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['RTGrossPrice'];
                                $roomtype_RTCommPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['RTCommPrice'];
                                $roomtype_RTNetPrice = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['@attributes']['RTNetPrice'];

                                $AdultNums = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                                $ChildNums = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                                $promo_name = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                                $promo_value = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                                $promo_code = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];

                                #insert sql
                                if ($roomtype_numrooms >= $NumRooms) {
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
        }
          #select data searchsession
          if($location_type==2){
      		$hotels = DAO::queryAllSql("SELECT roomcateg_name as name,price
      																						FROM tghsearchprice
      																						WHERE property_cd = '".$location_code."'
      																						AND check_in = '".$checkIn."'
      																						AND check_out = '".$checkOut."'");
      		}

      		if($location_type==3){
      			/*echo ("SELECT Tghproperty.property_name as name,tghsearchprice.price
      																							FROM tghsearchprice
      																	INNER JOIN tghproperty
      							  															ON  Tghproperty.property_cd = tghsearchprice.property_cd
      																							WHERE tghsearchprice.property_cd like '".$destCitycode."%'
      																							AND tghsearchprice.check_in = '".$checkIn."'
      																							AND tghsearchprice.check_out = '".$checkOut."'
      																							GROUP BY Tghproperty.property_name
      																							");*/
      		$hotels = DAO::queryAllSql("SELECT Tghproperty.property_cd as hotelCode,Tghproperty.property_name as name,tghsearchprice.price,
      				Tghproperty.gmaps_latitude,Tghproperty.gmaps_longitude,Tghproperty.addressline1 as displayAddress,Tghproperty.addressline1 as address
      																						FROM tghsearchprice
      																INNER JOIN tghproperty
      						  															ON  Tghproperty.property_cd = tghsearchprice.property_cd
      																						WHERE tghsearchprice.property_cd like '".$location_code."%'
      																						AND tghsearchprice.check_in = '".$checkIn."'
      																						AND tghsearchprice.check_out = '".$checkOut."'
      																						GROUP BY Tghproperty.property_name
      																						");
      		}

      		$countselecthotels = count($hotels);
        }
        $this->render('form_searchhotel',array(
                'hotelCode'=>$hotelCode,
                'hotels'=>$hotels,
                'AdultNum'=>$AdultNum,
                'destCity'=>$destCity,
                'check_in'=>$check_in,
                'check_out'=>$check_out,
                'NumRooms'=>$NumRooms,
                'countselecthotels'=>$countselecthotels
        ));
    }

    public function actionSearchsessionionic()
    {
        //
        print_r($_POST);


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
          if($NumRooms<=1){

            $arrRooms[] = array('numAdults'=>$NumRooms);
            print_r($arrRooms);
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

          $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
          //echo $jsonResult;
          $hotelList = json_decode($jsonResult,TRUE)['SearchHotel_Response']['Hotel'];
          print_r($hotelList);

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
                 $AdultNums = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                 $ChildNums = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                 $promo_name = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                 $promo_value = $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                 $promo_code= $listHotel['RoomCateg']['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];

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
                       $AdultNums = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                       $ChildNums = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                       $promo_name = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                       $promo_value = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                       $promo_code = $listHotel['RoomCateg'][$r]['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];

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

                             $AdultNums = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                             $ChildNums = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                             $promo_name = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                             $promo_value = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                             $promo_code = $listHotel['RoomCateg'][$r]['RoomType'][$rt]['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];

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
          print_r($hotelList);
          echo $ResNo=$hotelList['ResNo'];
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
              (ResNo,HBookId,VoucherNo,VoucherDt,Status,HotelId,FromDt,ToDt,CatgId,CatgName,BFType,ServiceNo,RoomType,SeqNo,TotalPrice,GuestName, update_dt)
              VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now')");
          }
      //}
      $this->render('form_bookHotel',array(
              'hotelCode'=>$hotelCode
      ));
    }

    public function actionBookHoteltest($hotelCode,$checkIn,$checkOut,$AdultNum,$NumRooms,$CatgId)
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


            $hotels = DAO::queryAllSql("SELECT Tghproperty.country_cd as country_cd,Tghproperty.city_cd as city_cd,Tghproperty.property_name as name,tghsearchprice.price,
                Tghproperty.gmaps_latitude,Tghproperty.gmaps_longitude,Tghproperty.addressline1 as displayAddress,Tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
                tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType
                                                    FROM tghsearchprice
                                        INNER JOIN tghproperty
                                                    ON  Tghproperty.property_cd = tghsearchprice.property_cd
                                                    WHERE tghsearchprice.property_cd ='".$hotelCode."'
                                                    AND tghsearchprice.check_in = '".$checkIn."'
                                                    AND tghsearchprice.check_out = '".$checkOut."'
                                                    AND tghsearchprice.roomcateg_id ='".$CatgId."'
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
              $price=$hotels[$c]['price'];
            }

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
                //echo $xmlRequest;
              $jsonResult = ApiRequestor::post(ApiRequestor::URL_BOOK_HOTEL, $xmlRequest);
              //echo $jsonResult;
              $hotelList = json_decode($jsonResult,TRUE)['BookHotel_Response'];
              /*echo "<pre>";
              print_r($hotelList);
              echo "</pre>";*/
              $ResNo=$hotelList['ResNo'];
              if(isset($hotelList['ResNo'])) {
                  if ($hotelList['ResNo'] != null) {
                      $ResNo = $hotelList['ResNo'];
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
                      //$GuestName = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['PaxInformation']['GuestName'];
                      $user_id = $_SESSION['_idghoursmghol__states']['employee_cd'];
                      $OSRefNo = $random_number;
                      $GuestName='admin';
                      $now = date('Y-m-d H:i:s');
/*echo ("INSERT INTO tghbookmg
                (ResNo,HBookId,VoucherNo,VoucherDt,Status,HotelId,FromDt,ToDt,CatgId,CatgName,BFType,ServiceNo,RoomType,SeqNo,TotalPrice,GuestName, update_dt,user_id,OSRefNo)
                VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo')");
*/
                          DAO::executeSql("INSERT INTO tghbookmg
                    (ResNo,HBookId,VoucherNo,VoucherDt,Status,HotelId,FromDt,ToDt,CatgId,CatgName,BFType,ServiceNo,RoomType,SeqNo,TotalPrice,GuestName, update_dt,user_id,OSRefNo)
                    VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo')");

                      Yii::app()->user->setFlash('success', "Book Hotel Successfully");
                      $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                      //Yii::app()->end();
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

        //print_r($model);
      //if(isset($_POST['ResNo']))
        if(isset($id))
      {
        $ResNo=$model->ResNo;
        $OSRefNo=$model->OSRefNo;
        $HBookId=$model->HBookId;
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
          $ResNo=$model->ResNo;
        //$ResNo=$_POST['ResNo'];
        //mg/hoteldata/AcceptBooking&ResNo=WWMGMA180637503
          $xmlRequest = $this->renderPartial('req_acceptbooking'
              , array(
                  'ResNo'=>$ResNo
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_ACCEPT_BOOKING, $xmlRequest);
          //echo $jsonResult;
      }
      $this->render('form_acceptbooking',array(
              'ResNo'=>$ResNo,
              'jsonResult'=>$jsonResult
      ));
    }

    public function actionGetcancelpolicy()
    {
        //$model=Bookmg::model()->findByPk($id);
        $ResNo='';
        $OSRefNo='';
        $HBookId='';
      if(isset($_POST['ResNo']))
      {
          $ResNo=$_POST['ResNo'];
          $OSRefNo=$_POST['OSRefNo'];
          $HBookId=$_POST['HBookId'];
          $xmlRequest = $this->renderPartial('req_getcancelpolicy'
              , array(
                  'ResNo'=>$ResNo,
                  'OSRefNo'=>$OSRefNo,
                  'HBookId'=>$HBookId
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_CANCEL_POLICY, $xmlRequest);
          echo $jsonResult;
      }
      $this->render('form_getcancelpolicy',array(
              'ResNo'=>$ResNo,

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
            $ResNo=$model->ResNo;
            $OSRefNo=$model->OSRefNo;
            $HBookId=$model->HBookId;
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
                    $this->loadModel($id)->delete();
                    Yii::app()->user->setFlash('success', "Cancel Book Hotel Successfully");
                    $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                }
                else{
                    //echo $hotelList['CancelResult'];
                    Yii::app()->user->setFlash('failed', "Cancel Book Hotel Failed");
                    $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                }
            }
            else{
                //echo $hotelList['CancelResult'];
                Yii::app()->user->setFlash('Failed', "Cancel Book Hotel Failed");
                $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
            }


      }
      $this->render('form_cancelrsvn',array(
              'ResNo'=>$ResNo
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

