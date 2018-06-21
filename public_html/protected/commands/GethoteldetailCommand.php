<?php
class GethoteldetailCommand extends CConsoleCommand
{
    public function actionSavehoteldata()
    {

        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
        $dateNow = date('Y-m-d');
        $checkIn = date('Y-m-d', strtotime($dateNow.' + 5 day'));
        $checkOut = date('Y-m-d', strtotime($checkIn.' + 1 day'));

        $hotels = DAO::queryAllSql('SELECT property_cd
                                                FROM tghproperty
                                                ORDER BY property_cd');

          $countselect = count($hotels);


          for($c=0;$c<$countselect;$c++)
          {
             $hotelCode[$c] = $hotels[$c]['property_cd'];

          //$destCountry = 'WSASTH';
          //$city = 'WSASTHBKK';
          //$hotelCode = 'WSASTHBKK000160';
          $file = fopen(FileUpload::getFilePath("hoteldetailyii.csv", FileUpload::HOTEL_CSV_PATH),"w");

          $mod = new CWebModule('mg', null);
          $controller = new CommController('hoteldata', $mod);

          $xmlRequest = $controller->renderInternal(Yii::app()->basePath.'/views/commands/req_gethoteldetail.php'
              , array(
                  'hotelCode'=>$hotelCode[$c],
              ), true);

          $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_HOTEL_DETAIL, $xmlRequest);

          $hotelList = json_decode($jsonResult,TRUE)['GetHotelDetail_Response'];
          print_r($hotelList);
          //echo $hotelList['HotelName'];
          $textCSV = '';
          $lineText = '';
          $lineText = Helper::putStringCSV($lineText, 'Hotel ID', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Hotel Name', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Hotel Rooms', 'number');
          $lineText = Helper::putStringCSV($lineText, 'Address1', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Address2', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Address3', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Location', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Telephone', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Email', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Rating', 'number');
          $textCSV = $textCSV.(empty($textCSV)? '' : "\r\n").$lineText;

          $strAdd1 = '';
          $strAdd2 = '';
          $strAdd3 = '';
          $strAddEmail = '';
          $strPhone = '';
          if(empty($hotelList['Address1']) === false) {
            $strAdd1 = $hotelList['Address1'];
          }
          if(empty($hotelList['Address2']) === false) {
            $strAdd2 = $hotelList['Address2'];
          }
          if(empty($hotelList['Address3']) === false) {
            $strAdd3 = $hotelList['Address3'];
          }
          if(empty($hotelList['Email']) === false) {
            $strAddEmail = $hotelList['Email'];
          }
          if(empty($hotelList['Telephone']) === false) {
            $strAddTelephone = $hotelList['Telephone'];
          }

              $lineText = '';
              $lineText = Helper::putStringCSV($lineText, $hotelList['HotelId'], 'str');
              $lineText = Helper::putStringCSV($lineText, $hotelList['HotelName'], 'str');
              $lineText = Helper::putStringCSV($lineText, $hotelList['HotelRooms'], 'number');
              $lineText = Helper::putStringCSV($lineText, $strAdd1, 'str');
              $lineText = Helper::putStringCSV($lineText, $strAdd2, 'str');
              $lineText = Helper::putStringCSV($lineText, $strAdd3, 'str');
              $lineText = Helper::putStringCSV($lineText, $hotelList['Location'], 'str');
              $lineText = Helper::putStringCSV($lineText, $strAddTelephone, 'str');
              $lineText = Helper::putStringCSV($lineText, $strAddEmail, 'str');
              $lineText = Helper::putStringCSV($lineText, $hotelList['Rating'], 'number');
              $textCSV = $textCSV.(empty($textCSV)? '' : "\r\n").$lineText;
              //fputcsv($file,explode('\r\n',$textCSV));


          fwrite($file,$textCSV);

          fclose($file);

          $now=date('Y-m-d H:i:s');
          $file = FileUpload::getFilePath("hoteldetailyii.csv", FileUpload::HOTEL_CSV_PATH);
          $connection = Yii::app()->db;
          $transaction = $connection->beginTransaction();
          try {
              DAO::executeSql("TRUNCATE TABLE tghuploadhoteldetail");
              //$connection->createCommand($mload)->execute();
              DAO::executeSql("LOAD DATA LOCAL INFILE '".$file."' INTO TABLE `tghuploadhoteldetail` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES (`property_cd` , `property_name` , `hotelrooms`, `address_line1`, `address_line2`, `address_line3`, `location`, `telephone`, `email`, `rating`)");
              $location_type = Searchlocation::LOCATION_TYPE_HOTEL;
              //$msearchloc = DAO::executeSql("LOAD DATA LOCAL INFILE '".$file."' INTO TABLE `tghsearchlocation` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES (`property_cd` , `property_name` , `market_name`)");


              #insert tghproperty
              DAO::executeSql("UPDATE tghproperty
                JOIN tghuploadhoteldetail ON tghproperty.property_cd = tghuploadhoteldetail.property_cd
                SET tghproperty.property_name = tghuploadhoteldetail.property_name
                , tghproperty.addressline1 = tghuploadhoteldetail.address_line1
                , tghproperty.addressline2 = tghuploadhoteldetail.address_line2
                , tghproperty.addressline3 = tghuploadhoteldetail.address_line3
                , tghproperty.locationinstruction = tghuploadhoteldetail.location
                , tghproperty.hotel_phone_number = tghuploadhoteldetail.telephone
                , tghproperty.bookingconfirmationemail = tghuploadhoteldetail.email
                , tghproperty.numberofstar = tghuploadhoteldetail.rating
                , tghproperty.update_dt = :udt;",array(':udt'=>$now));
              /*DAO::executeSql("INSERT INTO tghproperty (property_cd, property_name, market_name,gmaps_longitude,gmaps_latitude,numberofstar, update_dt)
                    SELECT DISTINCT tghuploadhoteldetail.property_cd, tghuploadhoteldetail.property_name, tghuploadhoteldetail.hotelrooms,tghuploadhoteldetail.address_line1,tghuploadhoteldetail.address_line2,tghuploadhoteldetail.location,tghuploadhoteldetail.telephone,tghuploadhoteldetail.email,tghuploadhoteldetail.rating, :udt
                    FROM tghuploadhoteldetail
                    LEFT JOIN tghproperty ON tghuploadhotel.property_cd = tghproperty.property_cd
                    WHERE tghproperty.property_cd IS NULL",array(':udt'=>$now));*/


              #update query SearchHotel
              /*DAO::executeSql("UPDATE tghsearchlocation
                JOIN tghproperty ON tghsearchlocation.location_code = tghproperty.property_cd AND tghsearchlocation.location_type = :lty
                SET tghsearchlocation.location_name = tghproperty.property_name
                , tghsearchlocation.market_name = tghproperty.market_name
                , tghsearchlocation.update_dt = :udt;",array(':lty'=>$location_type,':udt'=>$now));
              DAO::executeSql("INSERT INTO tghsearchlocation (location_type, location_code, location_name, market_name, update_dt)
                    SELECT :lty, tghproperty.property_cd, tghproperty.property_name, tghproperty.market_name, :udt
                    FROM tghproperty
                    LEFT JOIN tghsearchlocation ON tghproperty.property_cd = tghsearchlocation.location_code AND tghsearchlocation.location_type = :lty
                    WHERE tghsearchlocation.location_code IS NULL",array(':lty'=>$location_type,':udt'=>$now));*/
              $transaction->commit();
          } catch (Exception $e) { // an exception is raised if a query fails
              print_r($e);
              exit;
              $transaction->rollBack();
          }
        }
    }
}
