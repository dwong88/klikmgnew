<?php
/**
 * Created by PhpStorm.
 * User: wiyantotan
 * Date: 30/05/18
 * Time: 19:07
 */

class HoteldataController extends Controller
{
    public function actionGethoteldetail()
    {
      //print_r($_POST);
      $hotelCode ='';
      if(isset($_POST['hotelCode']))
      {
        $hotelCode = $_POST['hotelCode'];
        $xmlRequest = $this->renderPartial('req_gethoteldetail'
              , array(
                  'hotelCode'=>$hotelCode
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_HOTEL_DETAIL, $xmlRequest);

  //        $array = json_decode($jsonResult,TRUE);

          echo $jsonResult;
      }
      $this->render('form_gethoteldetail',array(
              'hotelCode'=>$hotelCode
      ));
    }

    public function actionSearchhotel()
    {
        //
        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
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

          $avgAdultNumInRoom = floor($AdultNum/$NumRooms);
          $arrRooms = array();
          for($i=0; $i<$NumRooms; $i++) {
              $sisaGuest = 0;
                if($i == ($NumRooms-1) ) {
                  $sisaGuest = $AdultNum - ($avgAdultNumInRoom * $NumRooms);
                }
              $arrRooms[] = array('numAdults'=>$avgAdultNumInRoom+$sisaGuest);

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
                  'arrRooms'=>$arrRooms
              ), true);

          $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
          echo $jsonResult;
        }
        $this->render('form_searchhotel',array(
                'hotelCode'=>$hotelCode
        ));
    }

    public function actionSearchsession()
    {
        //
        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
        if(isset($_POST['city']))
        {
          //$destCountry=$_POST['destCountry'];
          $destCity=strtolower($_POST['city']);
          //$hotelCode=$_POST['hotelCode'];
          //$rommCatCode=$_POST['rommCatCode'];
          $checkIn=$_POST['checkIn'];
          $checkOut=$_POST['checkOut'];
          $AdultNum=$_POST['AdultNum'];
          $ChildNum=$_POST['ChildNum'];
          $NumRooms=$_POST['NumRooms'];

          if($NumRooms== NULL || $NumRooms==0)
          {
            $NumRooms=1;
          }
          if($AdultNum== NULL || $AdultNum==0)
          {
            $AdultNum=1;
          }
          //Yii::app()->end();

          if($location_type==2){
            $country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                    FROM tghcitymg
                                                    WHERE city_name ='".$destCity."'");
          }

          /*$country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                  FROM tghcitymg
                                                  WHERE city_name ='".$destCity."'");*/

          $searchloc = DAO::queryAllSql("SELECT location_type, location_code
                                                  FROM tghsearchlocation
                                                  WHERE location_name ='".$destCity."'");
          $countsearch= count($searchloc);
          for($cs=0;$cs<$countsearch;$cs++)
          {
            echo $location_type = $searchloc[$cs]['location_type'];
            echo $location_code = $searchloc[$cs]['location_code'];
          }

          if($location_type==3){
            $country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                    FROM tghcitymg
                                                    WHERE city_cd ='".$location_code."'");
            $countselect = count($country);
            for($c=0;$c<$countselect;$c++)
            {
              echo $destCountry = $country[$c]['country_cd'];
              $city = $country[$c]['city_cd'];
            }
          }

          if($location_type==3){
            $country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                    FROM tghcitymg
                                                    WHERE city_cd ='".$location_code."'");
            $countselect = count($country);
            for($c=0;$c<$countselect;$c++)
            {
              echo $destCountry = $country[$c]['country_cd'];
              $city = $country[$c]['city_cd'];
            }
          }

          Yii::app()->end();
          $countselect = count($country);
          for($c=0;$c<$countselect;$c++)
          {
            $destCountry = $country[$c]['country_cd'];
            $city = $country[$c]['city_cd'];
          }

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

          $jumlah_tamu=$AdultNum;
          $check_in=$checkIn;
          $check_out=$checkOut;
          $now=date('Y-m-d H:i:s');
          $location_type = Searchlocation::LOCATION_TYPE_HOTEL;
          DAO::executeSql("INSERT INTO tghsearchsession (location_code,location_type,check_in,check_out,jumlah_kamar,jumlah_tamu, update_dt)VALUES('$location_code','$location_type','$check_in','$check_out','$NumRooms','$jumlah_tamu','$now')");

          foreach ($hotelList as $listHotel) {
              $HotelId = $listHotel['@attributes']['HotelId'];
              //$HotelName = $listHotel['@attributes']['HotelName'];
              //$MarketName = $listHotel['@attributes']['MarketName'];
              $location_code = $city;
              $currency = $listHotel['@attributes']['Currency'];
              $rating = $listHotel['@attributes']['rating'];
              //$avail = $listHotel['@attributes']['avail'];
              $avail = ($listHotel['@attributes']['avail'] == 'True' ? 1 : 0);
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
                    //echo "debug";

                  $sr=sizeof($listHotel['RoomCateg'])."-";
                  for($r=0;$r<=$sr;$r++)
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
                         DAO::executeSql("INSERT INTO tghsearchprice
                           (session_id,property_cd,avail,
                           check_in,check_out,roomcateg_id,roomcateg_name,
                           net_price,gross_price,comm_price,price,BFType,
                           RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                           RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code
                           )
                           VALUES
                           ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                           ,'$HotelId','$avail'
                           ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                           ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                           ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                           ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code'
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
                             /*echo "debug david"."<br>";
                             echo $roomtype_numrooms."<br>";
                             echo $NumRooms."<br>";*/

                             if($roomtype_numrooms>=$NumRooms){
                               DAO::executeSql("INSERT INTO tghsearchprice
                                 (session_id,property_cd,avail,
                                 check_in,check_out,roomcateg_id,roomcateg_name,
                                 net_price,gross_price,comm_price,price,BFType,
                                 RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                 RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code
                                 )
                                 VALUES
                                 ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                                 ,'$HotelId','$avail'
                                 ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                 ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                 ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                 ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code'
                               );");
                             }

                         }
                     }
                  }
              }
          }

        }
        $this->render('form_searchsession',array(
                'hotelCode'=>$hotelCode
        ));
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

  //        $array = json_decode($jsonResult,TRUE);

          $hotelList = json_decode($jsonResult,TRUE)['GetHotelDetail_Response'];
          print_r($hotelList);
          foreach ($hotelList as $listHotel) {
            echo $listHotel['@attributes']['HotelId'].'<br />';
            echo $listHotel['@attributes']['HotelName'].'<br />';
            echo $listHotel['@attributes']['HotelRooms'].'<br />';
            echo $listHotel['@attributes']['Address1'].'<br />';
            echo $listHotel['@attributes']['Address2'].'<br />';
            echo $listHotel['@attributes']['Address3'].'<br />';
            echo $listHotel['@attributes']['Location'].'<br />';
            echo $listHotel['@attributes']['Telephone'].'<br />';
            echo $listHotel['@attributes']['Email'].'<br />';
            echo $listHotel['@attributes']['Rating'].'<br />';
              echo '<br /><br />';
          }
    }

        public function actionViewCancelPolicy()
        {
            // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07

            if(isset($_POST['hotelCode']))
            {
              $hotelCode=$_POST['hotelCode'];
              $InternalCode=$_POST['internalCode'];
              $checkIn=$_POST['checkIn'];
              $checkOut=$_POST['checkOut'];
              $SeqNo=$_POST['SeqNo'];
              $AdultNum=$_POST['AdultNum'];
              $RoomType=$_POST['RoomType'];
              $flagAvail=$_POST['flagAvail'];

                $xmlRequest = $this->renderPartial('req_cancelpolicy'
                    , array(
                        'hotelCode'=>$hotelCode,
                        'InternalCode'=>$InternalCode,
                        'checkIn'=>$checkIn,
                        'checkOut'=>$checkOut,
                        'AdultNum'=>$AdultNum,
                        'RoomType'=>$RoomType,
                        'SeqNo'=>$SeqNo,
                        'flagAvail'=>$flagAvail,
                    ), true);
                $jsonResult = ApiRequestor::post(ApiRequestor::URL_VIEW_CANCEL_POLICY, $xmlRequest);

        //        $array = json_decode($jsonResult,TRUE);

                echo $jsonResult;
            }
            $this->render('form_viewcancelpolicy',array(
                    'hotelCode'=>$hotelCode
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

      //        $array = json_decode($jsonResult,TRUE);

              echo $jsonResult;
          //}
          $this->render('form_bookHotel',array(
                  'hotelCode'=>$hotelCode
          ));
        }

        public function actionGetrsvninfo()
        {
          if(isset($_POST['ResNo']))
          {
            $ResNo=$_POST['ResNo'];
            $OSRefNo=$_POST['OSRefNo'];
            $HBookId=$_POST['HBookId'];
              $xmlRequest = $this->renderPartial('req_getrsvninfo'
                  , array(
                      'ResNo'=>$ResNo,
                      'OSRefNo'=>$OSRefNo,
                      'HBookId'=>$HBookId
                  ), true);
              $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_RSVN_INFO, $xmlRequest);

      //        $array = json_decode($jsonResult,TRUE);

              echo $jsonResult;
          }
          $this->render('form_getrsvninfo',array(
                  'ResNo'=>$ResNo
          ));
        }
        public function actionAcceptBooking()
        {
          if(isset($_POST['ResNo']))
          {
            $ResNo=$_POST['ResNo'];
            //mg/hoteldata/AcceptBooking&ResNo=WWMGMA180637503
              $xmlRequest = $this->renderPartial('req_acceptbooking'
                  , array(
                      'ResNo'=>$ResNo
                  ), true);
              $jsonResult = ApiRequestor::post(ApiRequestor::URL_ACCEPT_BOOKING, $xmlRequest);

      //        $array = json_decode($jsonResult,TRUE);

              echo $jsonResult;
          }
          $this->render('form_acceptbooking',array(
                  'ResNo'=>$ResNo
          ));
        }

        public function actionGetcancelpolicy()
        {
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

      //        $array = json_decode($jsonResult,TRUE);

              echo $jsonResult;
          }
          $this->render('form_getcancelpolicy',array(
                  'ResNo'=>$ResNo
          ));
        }

        public function actionAmendHotel()
        {
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
              $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_AMEND_HOTEL, $xmlRequest);

      //        $array = json_decode($jsonResult,TRUE);

              echo $jsonResult;
          }
          $this->render('form_AmendHotel',array(
                  'ResNo'=>$ResNo
          ));
        }

        public function actionCancelrsvn()
        {
          //r=mg/hoteldata/Cancelrsvn&ResNo=WWMGMA180637503&OSRefNo=9923012912335&HBookId=HBMA1806000254
          if(isset($_POST['ResNo']))
          {
              $ResNo=$_POST['ResNo'];
              $OSRefNo=$_POST['OSRefNo'];
              $HBookId=$_POST['HBookId'];
              $xmlRequest = $this->renderPartial('req_CancelReservation'
                  , array(
                      'ResNo'=>$ResNo,
                      'OSRefNo'=>$OSRefNo,
                      'HBookId'=>$HBookId
                  ), true);
              $jsonResult = ApiRequestor::post(ApiRequestor::URL_CANCEL_RESERVATION, $xmlRequest);

      //        $array = json_decode($jsonResult,TRUE);

              echo $jsonResult;
          }
          $this->render('form_cancelrsvn',array(
                  'ResNo'=>$ResNo
          ));
        }
}
