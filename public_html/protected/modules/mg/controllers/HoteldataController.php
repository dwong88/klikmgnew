<?php
/**
 * Created by PhpStorm.
 * User: wiyantotan
 * Date: 30/05/18
 * Time: 19:07
 */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Resultsm {} #moveevent
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

        $xmlRequest = $this->renderPartial('req_gethoteldetail'
              , array(
                  'hotelCode'=>$hotelCode
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_HOTEL_DETAIL, $xmlRequest);

          $FacilityList='';
          $hotelList = json_decode($jsonResult,TRUE)['GetHotelDetail_Response'];
          /*echo "<pre>";
          print_r($hotelList);
          echo "</pre>";*/
          if(isset($hotelList['Facility'])){
              $FacilityList = json_decode($jsonResult,TRUE)['GetHotelDetail_Response']['Facility'];
          }

          $temp_fc=array();
          if($FacilityList!=NULL){
            foreach ($FacilityList as $listFac) {
              $temp_fc[] = $listFac['@attributes']['name'];
            }
          }

          $myJSON = json_encode($hotelList);
          $myFacility = json_encode($temp_fc);
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

        $countselect = count($hotels);
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

          /*echo "<pre>";
          print_r($hotelList);
          echo "</pre>";*/
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

    public function actionSearchhotel()
    {
        $mSearch = new SearchHotelForm();
        //$mSearch->checkin_dt = date('Y-m-d')
        $mSearch->checkin_dt = '2018-11-14';
        $RoomPrice =0;
        $hotels='';
        $check_in='';
        $check_out='';
        $countselecthotels='';
        $hotelCode='';

        if(isset($_POST['SearchHotelForm'])) {
            $mSearch->attributes = $_POST['SearchHotelForm'];
            if($mSearch->validate())
            {
                $check_in = $mSearch->checkin_dt;
                $tambahhari = '+' . $mSearch->duration . 'day';
                //$tambahhari = '+1day'; #hardcode tambah 1 hari
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

                if($sessionId == 0)
                {
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
                    }
                    else
                    {
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
                    echo $xmlRequest;
                    //echo '<pre>';
                    //var_dump($hotelResponse);
                    //print_r($hotelResponse);
                    //echo '</pre>';
                    //Yii::app()->end();
                    $hotelList = array();
                    if(isset($hotelResponse['SearchHotel_Response']['Hotel']))
                    {
                        if(isset($hotelResponse['SearchHotel_Response']['Hotel']['@attributes']))
                        {
                            $hotelList = array($hotelResponse['SearchHotel_Response']['Hotel']);
                        }
                        else
                        {
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

                    foreach ($hotelList as $listHotel)
                    {
//                      var_dump($listHotel);
//                      break;
                        $HotelId = $listHotel['@attributes']['HotelId'];
                        $HotelName = $listHotel['@attributes']['HotelName'];
                        //$MarketName = $listHotel['@attributes']['MarketName'];
                        $location_code = $mSearch->locationCity;
                        $currency = $listHotel['@attributes']['Currency'];
                        $rating = $listHotel['@attributes']['Rating'];
                        //$avail = $listHotel['@attributes']['avail'];
                        $avail = ($listHotel['@attributes']['avail'] == 'True' ? 1 : 0);

                        if (isset($listHotel['RoomCateg']['@attributes']))
                        {
                            $listHotel['RoomCateg']= array(0=>$listHotel['RoomCateg']);
                        }
                        foreach ($listHotel['RoomCateg'] as $key => $category)
                        {
                            $roomcateg_id = $category['@attributes']['Code'];
                            $roomcateg_name = $category['@attributes']['Name'];
                            $roomcateg_net_price = $category['@attributes']['NetPrice'];
                            $roomcateg_gross_price = $category['@attributes']['GrossPrice'];
                            $roomcateg_comm_price = $category['@attributes']['CommPrice'];
                            $roomcateg_price = $category['@attributes']['Price'];
                            $roomcateg_BFType = $category['@attributes']['BFType'];
                            $roomtype_name = $category['RoomType']['@attributes']['TypeName'];
                            $roomtype_numrooms = $category['RoomType']['@attributes']['NumRooms'];
                            $roomtype_totalprice = $category['RoomType']['@attributes']['TotalPrice'];
                            $roomtype_avrNightPrice = $category['RoomType']['@attributes']['avrNightPrice'];
                            $roomtype_RTGrossPrice = $category['RoomType']['@attributes']['RTGrossPrice'];
                            $roomtype_RTCommPrice = $category['RoomType']['@attributes']['RTCommPrice'];
                            $roomtype_RTNetPrice = $category['RoomType']['@attributes']['RTNetPrice'];
                            if (isset($category['RoomType']['Rate']['RoomRate'])) {
                                $category['RoomType']['Rate']= array(0=>$category['RoomType']['Rate']);
                            }

                            foreach ($category['RoomType']['Rate'] as $key => $rateroom) {
                              echo "<pre>";
                              var_dump($rateroom);
                              echo "</pre>";
                              Yii::app()->end();
                              if(!empty($rateroom['RoomRate']['RoomSeq']))
                              {
                                  if (isset($rateroom['RoomRate']['RoomSeq']['@attributes'])) {
                                      $rateroom['RoomRate']['RoomSeq']= array(0=>$rateroom['RoomRate']['RoomSeq']);
                                  }
                                  foreach ($rateroom['RoomRate']['RoomSeq'] as $roomrt) {
                                      $AdultNums = $roomrt['@attributes']['AdultNum'];
                                      $ChildNums = $roomrt['@attributes']['ChildNum'];
                                      if(isset($roomrt['@attributes']['RoomPrice'])){
                                        $RoomPrice = $roomrt['@attributes']['RoomPrice'];
                                      }
                                  }
                              }
                              else{
                                if (isset($rateroom['RoomRate']['RoomSeq']['@attributes'])) {
                                    $rateroom['RoomRate']['RoomSeq']= array(0=>$rateroom['RoomRate']['RoomSeq']);
                                }
                                foreach ($rateroom['RoomRate']['RoomSeq'] as $roomrt) {
                                    $AdultNums = $roomrt['@attributes']['AdultNum'];
                                    $ChildNums = $roomrt['@attributes']['ChildNum'];
                                    if(isset($roomrt['@attributes']['RoomPrice'])){
                                      $RoomPrice = $roomrt['@attributes']['RoomPrice'];
                                    }
                                }
                              }
                              if(!empty($rateroom['RoomRateInfo']['Promotion']))
                              {
                                  if (isset($rateroom['RoomRateInfo'])) {
                                      $rateroom['RoomRateInfo']= array(0=>$rateroom['RoomRateInfo']);
                                  }
                                  foreach ($rateroom['RoomRateInfo'] as $romrate) {
                                      $promo_name = $romrate['Promotion']['@attributes']['Name'];
                                      $promo_value = $romrate['Promotion']['@attributes']['Value'];
                                      $promo_code = $romrate['Promotion']['@attributes']['PromoCode'];
                                      $EBType = $romrate['EarlyBird']['@attributes']['EBType'];
                                      $EBRate = $romrate['EarlyBird']['@attributes']['EBRate'];
                                  }
                              }
                            }


                            if ($roomtype_numrooms >= $mSearch->kamar) {
                              DAO::executeSql("INSERT INTO tghsearchprice
                                (session_id,property_cd,property_name,avail,
                                check_in,check_out,roomcateg_id,roomcateg_name,
                                net_price,gross_price,comm_price,price,BFType,
                                RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
                                RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt,ebrate,ebtype
                                )
                                VALUES
                                ((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
                                ,'$HotelId','$HotelName','$avail'
                                ,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
                                ,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
                                ,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
                                ,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now','$EBRate','$EBType'
                              );");
                            }
                          }
                        }
                      }
                    }
//                Yii::app()->end();
                $hotels = DAO::queryAllSql("SELECT tghproperty.property_cd as hotelCode,tghproperty.property_name as name,tghsearchprice.price,tghsearchprice.totalprice,tghsearchprice.roomprice,tghsearchprice.ebrate,tghsearchprice.ebtype,
      				                      tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address
                                          FROM tghsearchprice
      									  INNER JOIN tghproperty ON  tghproperty.property_cd = tghsearchprice.property_cd
      									  WHERE tghsearchprice.session_id = :sid
      									  GROUP BY tghproperty.property_name", array(':sid'=>$sessionId));

                $mSearch->displayResult = true;
                $countselecthotels = count($hotels);
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
          $xmlRequest = $this->renderPartial('req_gethoteldetail'
              , array(
                  'hotelCode'=>$hotelCode
              ), true);
          $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_HOTEL_DETAIL, $xmlRequest);
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

            if (isset($hotelCode))
            {
                $InternalCode = 'CL005';
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
                if(isset($jsonResult['ViewCancelPolicy_Response']))
                {
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

    public function actionBookHoteltest($hotelCode,$checkIn,$checkOut,$AdultNum,$NumRooms,$CatgId, $national, $currency,$RoomType, $bftype,$RsvnNo=Null)
    {
      //print_r($_GET);
      if(isset($_GET['Rsvnno']))
      {
        $RsvnNo=$_GET['Rsvnno'];
      }
      $user_id = $_SESSION['_idghoursmghol__states']['employee_cd'];
      $GuestName='admin';
      $now = date('Y-m-d H:i:s');
      if(isset($hotelCode))
      {
          $InternalCode='CL005';
          $SeqNo=1;
          $flagAvail = 'Y';
          $digits_needed=13; //untuk random number
          $random_number=''; // set up a blank string
          $count=0;

          while ( $count < $digits_needed ) {
              $random_digit = mt_rand(0, 9);

              $random_number .= $random_digit;
              $count++;
          }
          $OSRefNo = $random_number;
        #cek hotel dan guest
        if($NumRooms<=1)
        {

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

              #query hotel
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
                  $price=$hotels[$c]['roomprice'];
                }
                //Yii::app()->end();
                $xmlRequest = $this->renderPartial('req_searchhotelionic'
                    , array(
                        'nationalities' => $national,
                        'currency' => $currency,
                        'destCountry' => $destCountry,
                        'city' => $city,
                        'hotelCode' => $hotelCode,
                        'rommCatCode' => $CatgId,
                        'checkIn' => $checkIn,
                        'checkOut' => $checkOut,
                        'AdultNum' => $AdultNum,
                        'NumRooms' => $NumRooms,
                        'arrRooms' => $arrRooms
                    ), true);

                #ini untuk submit second search which is “Search HotelId+ServiceCode”  to us in order to compare the price from your first and second search.
                $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
                $hotelResponse = json_decode($jsonResult,TRUE);
                $hotelList = array();
                if(isset($hotelResponse['SearchHotel_Response']['Hotel']))
                {
                    if(isset($hotelResponse['SearchHotel_Response']['Hotel']['@attributes']))
                    {
                        $hotelList = array($hotelResponse['SearchHotel_Response']['Hotel']);
                    }
                    else
                    {
                        $hotelList = $hotelResponse['SearchHotel_Response']['Hotel'];
                    }
                }

                #ambil data dari xml search request
                foreach ($hotelList as $listHotel)
                {
                    $HotelId = $listHotel['@attributes']['HotelId'];
                    $HotelName = $listHotel['@attributes']['HotelName'];
                    $currency = $listHotel['@attributes']['Currency'];
                    $rating = $listHotel['@attributes']['Rating'];
                    $avail = ($listHotel['@attributes']['avail'] == 'True' ? 1 : 0);

                    if (isset($listHotel['RoomCateg']['@attributes']))
                    {
                        $listHotel['RoomCateg']= array(0=>$listHotel['RoomCateg']);
                    }
                    foreach ($listHotel['RoomCateg'] as $key => $category)
                    {
                        $roomcateg_id = $category['@attributes']['Code'];
                        $roomcateg_name = $category['@attributes']['Name'];
                        $roomcateg_net_price = $category['@attributes']['NetPrice'];
                        $roomcateg_gross_price = $category['@attributes']['GrossPrice'];
                        $roomcateg_comm_price = $category['@attributes']['CommPrice'];
                        $roomcateg_price = $category['@attributes']['Price'];
                        $roomcateg_BFType = $category['@attributes']['BFType'];
                        $roomtype_name = $category['RoomType']['@attributes']['TypeName'];
                        $roomtype_numrooms = $category['RoomType']['@attributes']['NumRooms'];
                        $roomtype_totalprice = $category['RoomType']['@attributes']['TotalPrice'];
                        $roomtype_avrNightPrice = $category['RoomType']['@attributes']['avrNightPrice'];
                        $roomtype_RTGrossPrice = $category['RoomType']['@attributes']['RTGrossPrice'];
                        $roomtype_RTCommPrice = $category['RoomType']['@attributes']['RTCommPrice'];
                        $roomtype_RTNetPrice = $category['RoomType']['@attributes']['RTNetPrice'];
                        if (isset($category['RoomType']['Rate']['RoomRate'])) {
                            $category['RoomType']['Rate']= array(0=>$category['RoomType']['Rate']);
                        }
                        echo "<pre>";
                        print_r($category['RoomType']['Rate']);
                        echo "</pre>";
                        foreach ($category['RoomType']['Rate'] as $key => $rateroom) {
                          if(!empty($rateroom['RoomRate']['RoomSeq']))
                          {
                              if (isset($rateroom['RoomRate']['RoomSeq']['@attributes'])) {
                                  $rateroom['RoomRate']['RoomSeq']= array(0=>$rateroom['RoomRate']['RoomSeq']);
                              }
                              foreach ($rateroom['RoomRate']['RoomSeq'] as $roomrt) {
                                  $AdultNums = $roomrt['@attributes']['AdultNum'];
                                  $ChildNums = $roomrt['@attributes']['ChildNum'];
                                  if(isset($roomrt['@attributes']['RoomPrice'])){
                                    $RoomPrice = $roomrt['@attributes']['RoomPrice'];
                                  }
                              }
                          }
                          if(!empty($rateroom['RoomRateInfo']['Promotion']))
                          {
                              if (isset($rateroom['RoomRateInfo'])) {
                                  $rateroom['RoomRateInfo']= array(0=>$rateroom['RoomRateInfo']);
                              }
                              foreach ($rateroom['RoomRateInfo'] as $romrate) {
                                  $promo_name = $romrate['Promotion']['@attributes']['Name'];
                                  $promo_value = $romrate['Promotion']['@attributes']['Value'];
                                  $promo_code = $romrate['Promotion']['@attributes']['PromoCode'];
                                  $EBType = $romrate['EarlyBird']['@attributes']['EBType'];
                                  $EBRate = $romrate['EarlyBird']['@attributes']['EBRate'];
                              }
                          }
                        }
                          if($bftype==$roomcateg_BFType){
                            if($price==$RoomPrice)
                              {
                                $cekharga="True";
                              }
                              else{
                                $cekharga="False";
                              }
                          }
                        }
                    }
              //Yii::app()->end();
              if($cekharga=='True')
              {
              $xmlRequest = $this->renderPartial('req_bookhotel'
                  , array(
                      'nationalities'=>$national,
                      'currency'=>$currency,
                      'RsvnNo'=>$RsvnNo,
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
                      'price'=>$RoomPrice,
                      'random_number'=>$random_number,
                      'countselect'=>$countselect,
                  ), true);
              //echo $xmlRequest;
              $jsonResultbook = ApiRequestor::post(ApiRequestor::URL_BOOK_HOTEL, $xmlRequest);
              $bookhotelResponse = json_decode($jsonResultbook,TRUE);
              $bookhotelList = array();
              if(isset($hotelResponse['SearchHotel_Response']['Hotel']))
              {
                  if(isset($bookhotelResponse['SearchHotel_Response']['Hotel']['@attributes']))
                  {
                      $bookhotelList = array($bookhotelResponse['BookHotel_Response']);
                  }
                  else
                  {
                      $bookhotelList = $bookhotelResponse['BookHotel_Response'];
                  }
              }
          }
          if(isset($bookhotelList['ResNo']))
          {
            $ResNo=$bookhotelList['ResNo'];
            if($cekharga=='True')
            {
              $ResNo = $bookhotelList['ResNo'];
              if(!empty($bookhotelList['CompleteService']['HBookId']['@attributes']['Id'])){
                  $HBookId = $bookhotelList['CompleteService']['HBookId']['@attributes']['Id'];
                  $VoucherNo = $bookhotelList['CompleteService']['HBookId']['@attributes']['VoucherNo'];
                  $VoucherDt = $bookhotelList['CompleteService']['HBookId']['@attributes']['VoucherDt'];
                  $Status = $bookhotelList['CompleteService']['HBookId']['@attributes']['Status'];
                  $HotelId = $bookhotelList['CompleteService']['HBookId']['HotelId'];
                  $FromDt = $bookhotelList['CompleteService']['HBookId']['Period']['@attributes']['FromDt'];
                  $ToDt = $bookhotelList['CompleteService']['HBookId']['Period']['@attributes']['ToDt'];
                  $CatgId = $bookhotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgId'];
                  $CatgName = $bookhotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['CatgName'];
                  $BFType = $bookhotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['@attributes']['BFType'];
                  $ServiceNo = $bookhotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['ServiceNo'];
                  $RoomType = $bookhotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['RoomType'];
                  $SeqNo = $bookhotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['@attributes']['SeqNo'];
                  $TotalPrice = $bookhotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['TotalPrice'];
                if(!empty($bookhotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['PriceInfomation']['NightPrice'][0]['Accom']['@attributes']['Price']))
                {
                  $nightprice=$bookhotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['PriceInfomation']['NightPrice'][0]['Accom']['@attributes']['Price'];
                }
                else{
                  $nightprice=$bookhotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['PriceInfomation']['NightPrice']['Accom']['@attributes']['Price'];
                }
                //$nightprice = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']['PriceInfomation']['NightPrice'][0]['Accom']['@attributes']['Price'];

                if($RsvnNo!=Null){
                  echo "ini Amendhotel";
                }
                else {
                  DAO::executeSql("INSERT INTO tghbookmg
                  (resno,hbookid,voucherno,voucherdt,status,hotelid,fromdt,todt,catgid,catgname,bftype,serviceno,roomtype,seqno,totalprice,guestname, update_dt,user_id,osrefno,nightprice)
                  VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo','$nightprice')");

                    Yii::app()->user->setFlash('success', "Book Hotel Successfully");
                    $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                }
                Yii::app()->end();

              }
              else{
                Yii::app()->user->setFlash('error', "Book Hotel Failed Please Check Current Price");
                $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
              }
            }
            //Yii::app()->end();
          }
      }
      else{
            $avgAdultNumInRoom = ceil($AdultNum/$NumRooms);
            $arrRooms = array();
            for($i=0; $i<$NumRooms; $i++) {
                $arrRooms[] = array('numAdults'=>$avgAdultNumInRoom);
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

            //Yii::app()->end();
            $xmlRequest = $this->renderPartial('req_searchhotelionic'
                , array(
                    'nationalities' => $national,
                    'currency' => $currency,
                    'destCountry' => $destCountry,
                    'city' => $city,
                    'hotelCode' => $hotelCode,
                    'rommCatCode' => $CatgId,
                    'checkIn' => $checkIn,
                    'checkOut' => $checkOut,
                    'AdultNum' => $AdultNum,
                    'NumRooms' => $NumRooms,
                    'arrRooms' => $arrRooms
                ), true);

            #ini untuk submit second search which is “Search HotelId+ServiceCode”  to us in order to compare the price from your first and second search.

            $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
            $hotelResponse = json_decode($jsonResult,TRUE);
            $hotelList = array();
            if(isset($hotelResponse['SearchHotel_Response']['Hotel']))
            {
                if(isset($hotelResponse['SearchHotel_Response']['Hotel']['@attributes']))
                {
                    $hotelList = array($hotelResponse['SearchHotel_Response']['Hotel']);
                }
                else
                {
                    $hotelList = $hotelResponse['SearchHotel_Response']['Hotel'];
                }
            }

            #ambil data dari xml search request
            foreach ($hotelList as $listHotel)
            {
                $HotelId = $listHotel['@attributes']['HotelId'];
                $HotelName = $listHotel['@attributes']['HotelName'];
                $currency = $listHotel['@attributes']['Currency'];
                $rating = $listHotel['@attributes']['Rating'];
                $avail = ($listHotel['@attributes']['avail'] == 'True' ? 1 : 0);

                if (isset($listHotel['RoomCateg']['@attributes']))
                {
                    $listHotel['RoomCateg']= array(0=>$listHotel['RoomCateg']);
                }
                foreach ($listHotel['RoomCateg'] as $key => $category)
                {
                    $roomcateg_id = $category['@attributes']['Code'];
                    $roomcateg_name = $category['@attributes']['Name'];
                    $roomcateg_net_price = $category['@attributes']['NetPrice'];
                    $roomcateg_gross_price = $category['@attributes']['GrossPrice'];
                    $roomcateg_comm_price = $category['@attributes']['CommPrice'];
                    $roomcateg_price = $category['@attributes']['Price'];
                    $roomcateg_BFType = $category['@attributes']['BFType'];
                    $roomtype_name = $category['RoomType']['@attributes']['TypeName'];
                    $roomtype_numrooms = $category['RoomType']['@attributes']['NumRooms'];
                    $roomtype_totalprice = $category['RoomType']['@attributes']['TotalPrice'];
                    $roomtype_avrNightPrice = $category['RoomType']['@attributes']['avrNightPrice'];
                    $roomtype_RTGrossPrice = $category['RoomType']['@attributes']['RTGrossPrice'];
                    $roomtype_RTCommPrice = $category['RoomType']['@attributes']['RTCommPrice'];
                    $roomtype_RTNetPrice = $category['RoomType']['@attributes']['RTNetPrice'];
                    if(!empty($category['RoomType']['Rate']['RoomRate']['RoomSeq']))
                    {
                        if (isset($category['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes'])) {
                            $category['RoomType']['Rate']['RoomRate']['RoomSeq']= array(0=>$category['RoomType']['Rate']['RoomRate']['RoomSeq']);
                        }
                        foreach ($category['RoomType']['Rate']['RoomRate']['RoomSeq'] as $roomrt) {
                            $AdultNums = $roomrt['@attributes']['AdultNum'];
                            $ChildNums = $roomrt['@attributes']['ChildNum'];
                            if(isset($roomrt['@attributes']['RoomPrice'])){
                              $RoomPrice = $roomrt['@attributes']['RoomPrice'];
                            }
                        }
                    }
                    if(!empty($category['RoomType']['Rate']['RoomRateInfo']['Promotion']))
                    {
                        if (isset($category['RoomType']['Rate']['RoomRateInfo'])) {
                            $category['RoomType']['Rate']['RoomRateInfo']= array(0=>$category['RoomType']['Rate']['RoomRateInfo']);
                        }
                        foreach ($category['RoomType']['Rate']['RoomRateInfo'] as $romrate) {
                            $promo_name = $romrate['Promotion']['@attributes']['Name'];
                            $promo_value = $romrate['Promotion']['@attributes']['Value'];
                            $promo_code = $romrate['Promotion']['@attributes']['PromoCode'];
                        }
                    }
                      if($bftype==$roomcateg_BFType){
                        //echo $RoomPrice.'<br>';
                        if($price==$RoomPrice)
                          {
                            $cekharga="True";
                          }
                          else{
                            $cekharga="False";
                          }
                      }
                    }

                }
                //Yii::app()->end();
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
            $jsonResult = ApiRequestor::post(ApiRequestor::URL_BOOK_HOTEL, $xmlRequest);
            $hotelList = json_decode($jsonResult,TRUE)['BookHotel_Response'];

        if(isset($hotelList['ResNo']))
        {
          $ResNo = $hotelList['ResNo'];
          if($cekharga=='True')
          {
            if(!empty($hotelList['CompleteService']['HBookId']['@attributes']['Id']))
            {
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
              $sn=sizeof($hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom']);
                if($sn>1){
                  for($so=0;$so<$sn;$so++)
                  {
                      $SeqNo = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom'][$so]['@attributes']['SeqNo'];
                      $TotalPrice = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom'][$so]['TotalPrice'];
                      if(isset($hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom'][$so]['PriceInfomation']['NightPrice'][$so]['Accom']['@attributes']['Price'])){
                            $nightprice = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom'][$so]['PriceInfomation']['NightPrice'][$so]['Accom']['@attributes']['Price'];
                        }
                        else{
                            $nightprice = $hotelList['CompleteService']['HBookId']['RoomCatgInfo']['RoomCatg']['Room']['SeqRoom'][$so]['PriceInfomation']['NightPrice']['Accom']['@attributes']['Price'];
                        }

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

                  if(isset($RsvnNo)){
                    echo "ini Amendhotel";
                  }
                  else {
                    //echo "ini book hotel";
                    DAO::executeSql("INSERT INTO tghbookmg
                    (resno,hbookid,voucherno,voucherdt,status,hotelid,fromdt,todt,catgid,catgname,bftype,serviceno,roomtype,seqno,totalprice,guestname, update_dt,user_id,osrefno,nightprice)
                    VALUES('$ResNo','$HBookId','$VoucherNo','$VoucherDt','$Status','$HotelId','$FromDt','$ToDt','$CatgId','$CatgName','$BFType','$ServiceNo','$RoomType','$SeqNo','$TotalPrice','$GuestName','$now','$user_id','$OSRefNo','$OSRefNo','$nightprice')");
                      Yii::app()->user->setFlash('success', "Book Hotel Successfully");
                      $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
                  }
                  //Yii::app()->end();
                }
            }
            else{
              Yii::app()->user->setFlash('error', "Book Hotel Failed Please Check Current Price");
              $this->redirect(CHtml::normalizeUrl(array('/mg/bookmg/admin')));
            }
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

      echo $xmlRequest;
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
          $SeqNo=$_POST['SeqNo'];
          $osrefno=$_POST['osrefno'];
          $AdultNum=$_POST['AdultNum'];
          $RoomType=$_POST['RoomType'];
          $BFType=$_POST['bftype'];
          $price=$_POST['nightprice'];
          $CatgId=$_POST['catgid'];
          $CatgName=$_POST['catgname'];
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
          $hotelResponse = json_decode($jsonResult,TRUE);
          $hotelList = array();
          if(isset($hotelResponse['SearchAmendHotel_Response']['Hotel']))
          {
              if(isset($hotelResponse['SearchAmendHotel_Response']['Hotel']['@attributes']))
              {
                  $hotelList = array($hotelResponse['SearchAmendHotel_Response']['Hotel']);
              }
              else
              {
                  $hotelList = $hotelResponse['SearchAmendHotel_Response']['Hotel'];
              }
          }
          echo "<pre>";
          print_r($hotelList);
          echo "</pre>";
          $national='WSASSG';
          $currency='IDR';
          $countselect=1;
          $NumRooms=1;
          $arrRooms[] = array('numAdults' => $AdultNum);
          $flagAvail ='Y';
          $xmlRequestbook = $this->renderPartial('req_bookhotel'
              , array(
                  'nationalities'=>$national,
                  'currency'=>$currency,
                  'RsvnNo'=>$ResNo,
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
                  'random_number'=>$osrefno,
                  'countselect'=>$countselect,
              ), true);
              echo $xmlRequestbook;
          $jsonResultbook = ApiRequestor::post(ApiRequestor::URL_BOOK_HOTEL, $xmlRequestbook);
          $bookhotelList = json_decode($jsonResultbook,TRUE)['BookHotel_Response'];
          print_r($jsonResultbook);
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


    public function actionCheckPrice()
    {
      //print_r($_POST);
      $hotelCode=$_POST['hotelCode'];
      $checkIn=$_POST['checkIn'];
      $checkOut=$_POST['checkOut'];
      $AdultNum=$_POST['AdultNum'];
      $NumRooms=$_POST['NumRooms'];
      $CatgId=$_POST['CatgId'];
      $national=$_POST['national'];
      $currency=$_POST['currency'];
      $RoomType=$_POST['RoomType'];
      $bftype=$_POST['bftype'];
      $RsvnNo=Null;
      //print_r($_POST);
      $user_id = $_SESSION['_idghoursmghol__states']['employee_cd'];
      $GuestName='admin';
      $now = date('Y-m-d H:i:s');
      if(isset($hotelCode))
      {
          $InternalCode='CL005';
          $SeqNo=1;
          $flagAvail = 'Y';
          $digits_needed=13; //untuk random number
          $random_number=''; // set up a blank string
          $count=0;

          while ( $count < $digits_needed ) {
              $random_digit = mt_rand(0, 9);

              $random_number .= $random_digit;
              $count++;
          }
          $OSRefNo = $random_number;
        #cek hotel dan guest
        if($NumRooms<=1)
        {

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

              #query hotel
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

                for($c=0;$c<$countselect;$c++)
                {
                  $destCountry = $hotels[$c]['country_cd'];
                  $city = $hotels[$c]['city_cd'];
                  $property_name = $hotels[$c]['name'];
                  $RoomType = $hotels[$c]['RoomType'];
                  $CatgName = $hotels[$c]['roomcateg_name'];
                  $price=$hotels[$c]['roomprice'];
                }


                //Yii::app()->end();
                $xmlRequest = $this->renderPartial('req_searchhotelionic'
                    , array(
                        'nationalities' => $national,
                        'currency' => $currency,
                        'destCountry' => $destCountry,
                        'city' => $city,
                        'hotelCode' => $hotelCode,
                        'rommCatCode' => $CatgId,
                        'checkIn' => $checkIn,
                        'checkOut' => $checkOut,
                        'AdultNum' => $AdultNum,
                        'NumRooms' => $NumRooms,
                        'arrRooms' => $arrRooms
                    ), true);

                #ini untuk submit second search which is “Search HotelId+ServiceCode”  to us in order to compare the price from your first and second search.

                $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
                $hotelResponse = json_decode($jsonResult,TRUE);
                $hotelList = array();
                if(isset($hotelResponse['SearchHotel_Response']['Hotel']))
                {
                    if(isset($hotelResponse['SearchHotel_Response']['Hotel']['@attributes']))
                    {
                        $hotelList = array($hotelResponse['SearchHotel_Response']['Hotel']);
                    }
                    else
                    {
                        $hotelList = $hotelResponse['SearchHotel_Response']['Hotel'];
                    }
                }
                //echo "<pre>";
                //print_r($hotelList);
                //echo "</pre>";
                #ambil data dari xml search request
                foreach ($hotelList as $listHotel)
                {
                    $HotelId = $listHotel['@attributes']['HotelId'];
                    $HotelName = $listHotel['@attributes']['HotelName'];
                    $currency = $listHotel['@attributes']['Currency'];
                    $rating = $listHotel['@attributes']['Rating'];
                    $avail = ($listHotel['@attributes']['avail'] == 'True' ? 1 : 0);

                    if (isset($listHotel['RoomCateg']['@attributes']))
                    {
                        $listHotel['RoomCateg']= array(0=>$listHotel['RoomCateg']);
                    }
                    foreach ($listHotel['RoomCateg'] as $key => $category)
                    {
                        $roomcateg_id = $category['@attributes']['Code'];
                        $roomcateg_name = $category['@attributes']['Name'];
                        $roomcateg_net_price = $category['@attributes']['NetPrice'];
                        $roomcateg_gross_price = $category['@attributes']['GrossPrice'];
                        $roomcateg_comm_price = $category['@attributes']['CommPrice'];
                        $roomcateg_price = $category['@attributes']['Price'];
                        $roomcateg_BFType = $category['@attributes']['BFType'];
                        $roomtype_name = $category['RoomType']['@attributes']['TypeName'];
                        $roomtype_numrooms = $category['RoomType']['@attributes']['NumRooms'];
                        $roomtype_totalprice = $category['RoomType']['@attributes']['TotalPrice'];
                        $roomtype_avrNightPrice = $category['RoomType']['@attributes']['avrNightPrice'];
                        $roomtype_RTGrossPrice = $category['RoomType']['@attributes']['RTGrossPrice'];
                        $roomtype_RTCommPrice = $category['RoomType']['@attributes']['RTCommPrice'];
                        $roomtype_RTNetPrice = $category['RoomType']['@attributes']['RTNetPrice'];
                        if (isset($category['RoomType']['Rate']['RoomRate'])) {
                            $category['RoomType']['Rate']= array(0=>$category['RoomType']['Rate']);
                            /*echo "<pre>";
                            print_r($category['RoomType']['Rate']['RoomRate']['RoomSeq']);
                            echo "</pre>";*/
                        }
                        foreach ($category['RoomType']['Rate'] as $key => $rateroom) {
                          if(!empty($rateroom['RoomRate']['RoomSeq']))
                          {
                              /*if(!empty($category['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes'])){
                                $AdultNums = $category['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
                                $ChildNums = $category['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
                              }*/
                              if (isset($rateroom['RoomRate']['RoomSeq']['@attributes'])) {
                                  $rateroom['RoomRate']['RoomSeq']= array(0=>$rateroom['RoomRate']['RoomSeq']);
                                  /*echo "<pre>";
                                  print_r($category['RoomType']['Rate']['RoomRate']['RoomSeq']);
                                  echo "</pre>";*/
                              }
                              foreach ($rateroom['RoomRate']['RoomSeq'] as $roomrt) {
                                  //echo "trace3";
                                  $AdultNums = $roomrt['@attributes']['AdultNum'];
                                  $ChildNums = $roomrt['@attributes']['ChildNum'];
                                  if(isset($roomrt['@attributes']['RoomPrice'])){
                                    $RoomPrice = $roomrt['@attributes']['RoomPrice'];
                                  }
                                  //echo "string";
                              }
                          }
                          if(!empty($rateroom['RoomRateInfo']['Promotion']))
                          {
                              if (isset($rateroom['RoomRateInfo'])) {
                                  $rateroom['RoomRateInfo']= array(0=>$rateroom['RoomRateInfo']);
                              }
                              foreach ($rateroom['RoomRateInfo'] as $romrate) {
                                  $promo_name = $romrate['Promotion']['@attributes']['Name'];
                                  $promo_value = $romrate['Promotion']['@attributes']['Value'];
                                  $promo_code = $romrate['Promotion']['@attributes']['PromoCode'];
                                  $EBType = $romrate['EarlyBird']['@attributes']['EBType'];
                                  $EBRate = $romrate['EarlyBird']['@attributes']['EBRate'];
                              }
                                /*$promo_name = $category['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
                                $promo_value = $category['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
                                $promo_code = $category['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];*/
                          }
                        }
                        $response = new Resultsm();
                          if($bftype==$roomcateg_BFType){
                            if($price==$RoomPrice)
                              {
                                //http://localhost/klikmgnew/public_html/index.php?r=mg/hoteldata/BookHoteltest&hotelCode=WSASIDDPS000011&checkIn=2018-11-14&checkOut=2018-11-17&AdultNum=3&NumRooms=1&CatgId=WSMA06030306&national=WSOCAU&bftype=ABF&currency=IDR&RoomType=Triple&BFType=ABF
                                /*echo $price."<br>";
                                echo $RoomPrice."<br>";
                                echo $bftype."<br>";
                                echo $roomcateg_BFType."<br>";*/
                                $response->result = 'OK';
                                $response->message = 'successful';
                                $response->hotelCode = $hotelCode;
                                $response->checkIn = $checkIn;
                                $response->checkOut = $checkOut;
                                $response->AdultNum =$AdultNum;
                                $response->NumRooms =$NumRooms;
                                $response->CatgId =$CatgId;
                                $response->national =$national;
                                $response->currency =$currency;
                                $response->RoomType =$RoomType;
                                $response->bftype =$bftype;
                                header('Content-Type: application/json');
                                echo json_encode($response);
                                //$this->redirect(CHtml::normalizeUrl(array('/mg/hoteldata/BookHoteltest&hotelCode="'.$hotelCode.'"&checkIn="'.$checkIn.'"&checkOut="'.$checkOut.'"&AdultNum="'.$AdultNum.'"&NumRooms="'.$NumRooms.'"&CatgId="'.$CatgId.'"&national="'.$national.'"&bftype="'.$bftype.'"&currency="'.$currency.'"&RoomType="'.$RoomType.'"')));
                              }
                              else{
                                $response->message = 'unsuccessful';
                                header('Content-Type: application/json');
                                echo json_encode($response);
                              }
                          }
                        }
                    }
                }
              }
    }

    public function loadModel($id)
    {
        $model=Bookmg::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}
