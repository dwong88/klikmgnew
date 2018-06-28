<?php
/**
 * Created by PhpStorm.
 * User: wiyantotan
 * Date: 30/05/18
 * Time: 19:07
 */

class HoteldataController extends Controller
{
    public function actionGethoteldetail($hotelCode)
    {
        $xmlRequest = $this->renderPartial('req_gethoteldetail'
            , array(
                'hotelCode'=>$hotelCode
            ), true);
        $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_HOTEL_DETAIL, $xmlRequest);

//        $array = json_decode($jsonResult,TRUE);

        echo $jsonResult;
    }

    public function actionSearchhotel($destCountry, $city, $hotelCode, $rommCatCode, $checkIn, $checkOut,$AdultNum,$RoomType)
    {
        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
        $xmlRequest = $this->renderPartial('req_searchhotel'
            , array(
                'destCountry'=>$destCountry,
                'city'=>$city,
                'hotelCode'=>$hotelCode,
                'rommCatCode'=>$rommCatCode,
                'checkIn'=>$checkIn,
                'checkOut'=>$checkOut,
                'AdultNum'=>$AdultNum,
                'RoomType'=>$RoomType,
            ), true);
        $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);

//        $array = json_decode($jsonResult,TRUE);

        echo $jsonResult;
    }
//
    public function actionViewCancelPolicy($hotelCode,$InternalCode, $checkIn, $checkOut,$SeqNo,$AdultNum,$RoomType,$flagAvail)
    {
        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
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

    public function actionBookHotel()
    {
        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
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
            ), true);
        $jsonResult = ApiRequestor::post(ApiRequestor::URL_BOOK_HOTEL, $xmlRequest);

//        $array = json_decode($jsonResult,TRUE);

        echo $jsonResult;
    }

    public function actionGetrsvninfo()
    {
        $xmlRequest = $this->renderPartial('req_getrsvninfo'
            , array(
                'hotelCode'=>$hotelCode
            ), true);
        $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_RSVN_INFO, $xmlRequest);

//        $array = json_decode($jsonResult,TRUE);

        echo $jsonResult;
    }

    public function actionGetcsv()
    {
      $list = array
          (
          "Peter,Griffin,Oslo,Norway",
          "Glenn,Quagmire,Oslo,Norway",
          );

          $file = fopen("uploads/contactsyii.csv","w");

          foreach ($list as $line)
          {
          fputcsv($file,explode(',',$line));
          }

          fclose($file);
    }

    public function actionLoadcsv()
    {
      echo $filecsv = Yii::app()->baseUrl.'';
      $model=new Usercsv; #formgeneral
  		#state and city_id
  		//$mDel = DAO::executeSql("DELETE FROM tghpropertyfeatures WHERE property_id = '".$id."'");

      //load data into table
      //$sql2 = "LOAD DATA LOCAL INFILE '".$filecsv"' INTO TABLE `usercsv` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' (`first_name` , `last_name` , `city`, `country`)";


      $connection = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {

          //$connection->createCommand($mload)->execute();
          $mload = DAO::executeSql("LOAD DATA LOCAL INFILE 'uploads/contactsyii.csv' INTO TABLE `usercsv` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' (`first_name` , `last_name` , `country`, `city`)");
          $transaction->commit();
      } catch (Exception $e) { // an exception is raised if a query fails
          print_r($e);
          exit;
          $transaction->rollBack();
      }
    }
}
