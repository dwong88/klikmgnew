<?php
class SavehotelCommand extends CConsoleCommand
{
    public function actionSavehoteldata()
    {

        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
        $dateNow = date('Y-m-d');
        $checkIn = date('Y-m-d', strtotime($dateNow.' + 5 day'));
        $checkOut = date('Y-m-d', strtotime($checkIn.' + 1 day'));

        $citys = DAO::queryAllSql('SELECT country_cd, city_cd
                                                FROM tghcitymg
                                                ORDER BY city_cd');

        $countselect = count($citys);
        #fungsi convert ke single array
        for($c=0;$c<$countselect;$c++)
        {
          $destCountry[$c] = $citys[$c]['country_cd'];
          $city[$c] = $citys[$c]['city_cd'];

          /*$destCountry = 'WSASTH';
          $city = 'WSASTHBKK';*/
          $hotelCode = '';
          $file = fopen(FileUpload::getFilePath("contactsyii.csv", FileUpload::HOTEL_CSV_PATH),"w");

          $mod = new CWebModule('mg', null);
          $controller = new CommController('hoteldata', $mod);

          $xmlRequest = $controller->renderInternal(Yii::app()->basePath.'/views/commands/req_searchhotel.php'
              , array(
                  'destCountry'=>$destCountry[$c],
                  'city'=>$city[$c],
                  'hotelCode'=>$hotelCode,
                  'rommCatCode'=>'',
                  'checkIn'=>$checkIn,
                  'checkOut'=>$checkOut,
              ), true);

          $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);

          $hotelList = json_decode($jsonResult,TRUE)['SearchHotel_Response']['Hotel'];
          //print_r($hotelList);
          $textCSV = '';
          $lineText = '';
          $lineText = Helper::putStringCSV($lineText, 'Hotel ID', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Hotel Name', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Market Name', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Latitude', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Longitude', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Rating', 'str');
          $lineText = Helper::putStringCSV($lineText, 'Country', 'str');
          $textCSV = $textCSV.(empty($textCSV)? '' : "\r\n").$lineText;


          foreach ($hotelList as $listHotel) {
              $lineText = '';
              $lineText = Helper::putStringCSV($lineText, $listHotel['@attributes']['HotelId'], 'str');
              $lineText = Helper::putStringCSV($lineText, $listHotel['@attributes']['HotelName'], 'str');
              $lineText = Helper::putStringCSV($lineText, $listHotel['@attributes']['MarketName'], 'str');
              $lineText = Helper::putStringCSV($lineText, $listHotel['@attributes']['Latitude'], 'number');
              $lineText = Helper::putStringCSV($lineText, $listHotel['@attributes']['Longitude'], 'number');
              $lineText = Helper::putStringCSV($lineText, $listHotel['@attributes']['Rating'], 'number');
              $lineText = Helper::putStringCSV($lineText, $destCountry[$c], 'str');
              $lineText = Helper::putStringCSV($lineText, $city[$c], 'str');
              $textCSV = $textCSV.(empty($textCSV)? '' : "\r\n").$lineText;
              //fputcsv($file,explode('\r\n',$textCSV));

          }
          fwrite($file,$textCSV);
          //echo $textCSV;
          //$file = fopen("uploads/contactsyii.csv","w");
          //$file = fopen(FileUpload::getFilePath("contactsyii.csv", FileUpload::HOTEL_CSV_PATH),"w");
          /*foreach ($textCSV as $line)
          {
              fputcsv($file,explode(',',$line));
          }*/
          fclose($file);
          $now=date('Y-m-d H:i:s');
          $file = FileUpload::getFilePath("contactsyii.csv", FileUpload::HOTEL_CSV_PATH);
          $connection = Yii::app()->db;
          $transaction = $connection->beginTransaction();
          try {
              DAO::executeSql("TRUNCATE TABLE tghuploadhotel");
              //$connection->createCommand($mload)->execute();
              DAO::executeSql("LOAD DATA LOCAL INFILE '".$file."' INTO TABLE `tghuploadhotel` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES (`property_cd` , `property_name` , `market_name`, `longitude`, `latitude`, `rating`, `country_cd`, `city_cd`)");
              $location_type = Searchlocation::LOCATION_TYPE_HOTEL;
              //$msearchloc = DAO::executeSql("LOAD DATA LOCAL INFILE '".$file."' INTO TABLE `tghsearchlocation` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES (`property_cd` , `property_name` , `market_name`)");


              #insert tghproperty
              DAO::executeSql("UPDATE tghproperty
                JOIN tghuploadhotel ON tghproperty.property_cd = tghuploadhotel.property_cd
                SET tghproperty.property_name = tghuploadhotel.property_name
                , tghproperty.market_name = tghuploadhotel.market_name
                , tghproperty.property_type_id = 2
                , tghproperty.country_cd = tghuploadhotel.country_cd
                , tghproperty.city_cd = tghuploadhotel.city_cd
                , tghproperty.update_dt = :udt;",array(':udt'=>$now));
              DAO::executeSql("INSERT INTO tghproperty (property_cd, property_name, market_name,property_type_id,gmaps_longitude,gmaps_latitude,numberofstar, `country_cd`, `city_cd`, update_dt)
                    SELECT DISTINCT tghuploadhotel.property_cd, tghuploadhotel.property_name, tghuploadhotel.market_name,2,tghuploadhotel.longitude,tghuploadhotel.latitude,tghuploadhotel.rating,tghuploadhotel.country_cd,tghuploadhotel.city_cd, :udt
                    FROM tghuploadhotel
                    LEFT JOIN tghproperty ON tghuploadhotel.property_cd = tghproperty.property_cd
                    WHERE tghproperty.property_cd IS NULL",array(':udt'=>$now));


              #update query SearchHotel
              DAO::executeSql("UPDATE tghsearchlocation
                JOIN tghproperty ON tghsearchlocation.location_code = tghproperty.property_cd AND tghsearchlocation.location_type = :lty
                SET tghsearchlocation.location_name = tghproperty.property_name
                , tghsearchlocation.market_name = tghproperty.market_name
                , tghsearchlocation.update_dt = :udt;",array(':lty'=>$location_type,':udt'=>$now));
              DAO::executeSql("INSERT INTO tghsearchlocation (location_type, location_code, location_name, market_name, update_dt)
                    SELECT :lty, tghproperty.property_cd, tghproperty.property_name, tghproperty.market_name, :udt
                    FROM tghproperty
                    LEFT JOIN tghsearchlocation ON tghproperty.property_cd = tghsearchlocation.location_code AND tghsearchlocation.location_type = :lty
                    WHERE tghsearchlocation.location_code IS NULL",array(':lty'=>$location_type,':udt'=>$now));
              $transaction->commit();
          } catch (Exception $e) { // an exception is raised if a query fails
              print_r($e);
              exit;
              $transaction->rollBack();
          }
        }

        // @todo write to file untuk variable $textCSV
    }

    public function actionLoadhoteldata()
    {
      $now=date('Y-m-d H:i:s');
      $file = FileUpload::getFilePath("contactsyii.csv", FileUpload::HOTEL_CSV_PATH);
      $connection = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
          //$connection->createCommand($mload)->execute();
          DAO::executeSql("LOAD DATA LOCAL INFILE '".$file."' INTO TABLE `tghuploadhotel` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES (`property_cd` , `property_name` , `market_name`, `longitude`, `latitude`, `rating`)");
          $location_type = Searchlocation::LOCATION_TYPE_HOTEL;
          //$msearchloc = DAO::executeSql("LOAD DATA LOCAL INFILE '".$file."' INTO TABLE `tghsearchlocation` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES (`property_cd` , `property_name` , `market_name`)");


          #insert tghproperty
          DAO::executeSql("UPDATE tghproperty
            JOIN tghuploadhotel ON tghproperty.property_cd = tghuploadhotel.property_cd
            SET tghproperty.property_name = tghuploadhotel.property_name
            , tghproperty.market_name = tghuploadhotel.market_name
            , tghproperty.update_dt = :udt;",array(':udt'=>$now));
          DAO::executeSql("INSERT INTO tghproperty (property_cd, property_name, market_name,gmaps_longitude,gmaps_latitude,numberofstar, update_dt)
                SELECT DISTINCT tghuploadhotel.property_cd, tghuploadhotel.property_name, tghuploadhotel.market_name,tghuploadhotel.longitude,tghuploadhotel.latitude,tghuploadhotel.rating, :udt
                FROM tghuploadhotel
                LEFT JOIN tghproperty ON tghuploadhotel.property_cd = tghproperty.property_cd
                WHERE tghproperty.property_cd IS NULL",array(':udt'=>$now));


          #update query SearchHotel
          DAO::executeSql("UPDATE tghsearchlocation
            JOIN tghproperty ON tghsearchlocation.location_code = tghproperty.property_cd AND tghsearchlocation.location_type = :lty
            SET tghsearchlocation.location_name = tghproperty.property_name
            , tghsearchlocation.market_name = tghproperty.market_name
            , tghsearchlocation.update_dt = :udt;",array(':lty'=>$location_type,':udt'=>$now));
          DAO::executeSql("INSERT INTO tghsearchlocation (location_type, location_code, location_name, market_name, update_dt)
                SELECT :lty, tghproperty.property_cd, tghproperty.property_name, tghproperty.market_name, :udt
                FROM tghproperty
                LEFT JOIN tghsearchlocation ON tghproperty.property_cd = tghsearchlocation.location_code AND tghsearchlocation.location_type = :lty
                WHERE tghsearchlocation.location_code IS NULL",array(':lty'=>$location_type,':udt'=>$now));

          DAO::executeSql("TRUNCATE TABLE tghuploadhotel");
          $transaction->commit();
      } catch (Exception $e) { // an exception is raised if a query fails
          print_r($e);
          exit;
          $transaction->rollBack();
      }
    }
}
