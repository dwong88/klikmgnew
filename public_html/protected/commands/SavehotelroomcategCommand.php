<?php
class SavehotelroomcategCommand extends CConsoleCommand
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
          $file = fopen(FileUpload::getFilePath("contactsyii.csv", FileUpload::ROOMCATEG_CSV_PATH),"w");

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
          $lineText = Helper::putStringCSV($lineText, 'RoomTypeID', 'str');
          $lineText = Helper::putStringCSV($lineText, 'RoomTypeName', 'str');
          $lineText = Helper::putStringCSV($lineText, 'RoomType BFType', 'str');
          $textCSV = $textCSV.(empty($textCSV)? '' : "\r\n").$lineText;

          $temp_roomtype_code= '';
          $temp_roomtype_name= '';
          $temp_roomtype_bftype= '';
          foreach ($hotelList as $listHotel) {
              $lineText = '';

              if(isset($listHotel['RoomCateg']['@attributes']))
              {
                $listHotel['RoomCateg']=array(0=>$listHotel['RoomCateg']);
              }
              $countroomcateg=count($listHotel['RoomCateg']);

              for($rc=0;$rc<$countroomcateg;$rc++){
                /*$lineText = Helper::putStringCSV($lineText, $listHotel['RoomCateg'][$rc]['@attributes']['Code'], 'str');
                $lineText = Helper::putStringCSV($lineText, $listHotel['RoomCateg'][$rc]['@attributes']['Name'], 'str');
                $lineText = Helper::putStringCSV($lineText, $listHotel['RoomCateg'][$rc]['@attributes']['BFType'], 'str');*/
                $temp_roomtype_code= $listHotel['RoomCateg'][$rc]['@attributes']['Code'];
                $temp_roomtype_name= $listHotel['RoomCateg'][$rc]['@attributes']['Name'];
                $temp_roomtype_bftype= $listHotel['RoomCateg'][$rc]['@attributes']['BFType'];
                if($temp_roomtype_code!=''){
                  //echo "hapus";
                  $temp_roomtype_code= $listHotel['RoomCateg'][$rc]['@attributes']['Code'];
                  $temp_roomtype_name= $listHotel['RoomCateg'][$rc]['@attributes']['Name'];
                  $temp_roomtype_bftype= $listHotel['RoomCateg'][$rc]['@attributes']['BFType'];
                  $lineText = '';
                  $lineText = Helper::putStringCSV($lineText, $listHotel['@attributes']['HotelId'], 'str');
                  $lineText = Helper::putStringCSV($lineText, $listHotel['@attributes']['HotelName'], 'str');
                  $lineText = Helper::putStringCSV($lineText, $temp_roomtype_code, 'str');
                  $lineText = Helper::putStringCSV($lineText, $temp_roomtype_name, 'str');
                  $lineText = Helper::putStringCSV($lineText, $temp_roomtype_bftype, 'str');
                  $temp_roomtype_code= '';
                  $temp_roomtype_name= '';
                  $temp_roomtype_bftype= '';
                }
                  $textCSV = $textCSV.(empty($textCSV)? '' : "\r\n").$lineText;
              }
              /*foreach ($listHotel['RoomCateg'] as $key => $RoomCateg) {
                  $lineText = Helper::putStringCSV($lineText, $RoomCateg['@attributes']['Code'], 'str');
                  $lineText = Helper::putStringCSV($lineText, $RoomCateg['@attributes']['Name'], 'str');
                  $lineText = Helper::putStringCSV($lineText, $RoomCateg['@attributes']['BFType'], 'str');
                  $textCSV = $textCSV.(empty($textCSV)? '' : "\r\n").$lineText;
              }*/

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

          //Yii::app()->end();
          $now=date('Y-m-d H:i:s');
          $file = FileUpload::getFilePath("contactsyii.csv", FileUpload::ROOMCATEG_CSV_PATH);
          $connection = Yii::app()->db;
          $transaction = $connection->beginTransaction();
          try {
              DAO::executeSql("TRUNCATE TABLE tghuploadhotel_roomcateg");
              //$connection->createCommand($mload)->execute();
              DAO::executeSql("LOAD DATA LOCAL INFILE '".$file."' INTO TABLE `tghuploadhotel_roomcateg` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES (`property_cd` , `property_name` , `roomtype_cd`, `roomtype_name`, `roomtype_bftype`)");
              //$location_type = Searchlocation::LOCATION_TYPE_HOTEL;
              //$msearchloc = DAO::executeSql("LOAD DATA LOCAL INFILE '".$file."' INTO TABLE `tghsearchlocation` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES (`property_cd` , `property_name` , `market_name`)");


              #insert tghproperty
              DAO::executeSql("UPDATE tghroomtype
                JOIN tghuploadhotel_roomcateg ON
                tghroomtype.room_type_id = tghuploadhotel_roomcateg.roomtype_cd AND
                tghroomtype.property_cd = tghuploadhotel_roomcateg.property_cd
                SET tghroomtype.room_type_name = tghuploadhotel_roomcateg.roomtype_name
                , tghroomtype.update_dt = :udt;",array(':udt'=>$now));
              DAO::executeSql("INSERT INTO tghroomtype (room_type_id,property_cd, room_type_name, room_type_desc,create_dt,create_by,update_dt,update_by,room_type_cleaning_minutes)
                    SELECT DISTINCT tghuploadhotel_roomcateg.roomtype_cd,tghuploadhotel_roomcateg.property_cd, tghuploadhotel_roomcateg.roomtype_name,'', :udt,1, :udt,1,2
                    FROM tghuploadhotel_roomcateg
                    ",array(':udt'=>$now));

                  /*
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
                    WHERE tghsearchlocation.location_code IS NULL",array(':lty'=>$location_type,':udt'=>$now));*/
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
          DAO::executeSql("LOAD DATA LOCAL INFILE '".$file."' INTO TABLE `tghuploadhotel_roomcateg` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES (`property_cd` , `property_name` , `market_name`, `longitude`, `latitude`, `rating`)");
          $location_type = Searchlocation::LOCATION_TYPE_HOTEL;
          //$msearchloc = DAO::executeSql("LOAD DATA LOCAL INFILE '".$file."' INTO TABLE `tghsearchlocation` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES (`property_cd` , `property_name` , `market_name`)");


          #insert tghproperty
          DAO::executeSql("UPDATE tghproperty
            JOIN tghuploadhotel_roomcateg ON tghproperty.property_cd = tghuploadhotel_roomcateg.property_cd
            SET tghproperty.property_name = tghuploadhotel_roomcateg.property_name
            , tghproperty.market_name = tghuploadhotel_roomcateg.market_name
            , tghproperty.update_dt = :udt;",array(':udt'=>$now));
          DAO::executeSql("INSERT INTO tghproperty (property_cd, property_name, market_name,gmaps_longitude,gmaps_latitude,numberofstar, update_dt)
                SELECT DISTINCT tghuploadhotel_roomcateg.property_cd, tghuploadhotel_roomcateg.property_name, tghuploadhotel_roomcateg.market_name,tghuploadhotel_roomcateg.longitude,tghuploadhotel_roomcateg.latitude,tghuploadhotel_roomcateg.rating, :udt
                FROM tghuploadhotel_roomcateg
                LEFT JOIN tghproperty ON tghuploadhotel_roomcateg.property_cd = tghproperty.property_cd
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

          DAO::executeSql("TRUNCATE TABLE tghuploadhotel_roomcateg");
          $transaction->commit();
      } catch (Exception $e) { // an exception is raised if a query fails
          print_r($e);
          exit;
          $transaction->rollBack();
      }
    }
}
