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

    public function actionSearchhotel($destCountry, $city, $hotelCode, $rommCatCode, $checkIn, $checkOut)
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
            ), true);

        $jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
        echo $jsonResult;
    }

    public function actionSavehotel($destCountry, $city, $hotelCode, $rommCatCode, $checkIn, $checkOut)
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

        public function actionBookHotel($hotelCode,$InternalCode, $checkIn, $checkOut,$SeqNo,$AdultNum,$RoomType,$flagAvail,$CatgId,$CatgName,$BFType,$price)
        {
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
                ), true);
            $jsonResult = ApiRequestor::post(ApiRequestor::URL_BOOK_HOTEL, $xmlRequest);

    //        $array = json_decode($jsonResult,TRUE);

            echo $jsonResult;
        }

        public function actionGetrsvninfo($ResNo,$OSRefNo,$HBookId)
        {
            $xmlRequest = $this->renderPartial('req_getrsvninfo'
                , array(
                    'hotelCode'=>$ResNo,
                    'OSRefNo'=>$OSRefNo,
                    'HBookId'=>$HBookId
                ), true);
            $jsonResult = ApiRequestor::post(ApiRequestor::URL_GET_RSVN_INFO, $xmlRequest);

    //        $array = json_decode($jsonResult,TRUE);

            echo $jsonResult;
        }
        public function actionAcceptBooking($ResNo)
        {
          //mg/hoteldata/AcceptBooking&ResNo=WWMGMA180637503
            $xmlRequest = $this->renderPartial('req_acceptbooking'
                , array(
                    'ResNo'=>$ResNo
                ), true);
            $jsonResult = ApiRequestor::post(ApiRequestor::URL_ACCEPT_BOOKING, $xmlRequest);

    //        $array = json_decode($jsonResult,TRUE);

            echo $jsonResult;
        }

        public function actionGetcancelpolicy($ResNo,$OSRefNo,$HBookId)
        {
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

        public function actionAmendHotel($hotelCode,$InternalCode, $checkIn, $checkOut,$AdultNum,$RoomType,$ResNo,$HBookId)
        {
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

        public function actionCancelrsvn($ResNo,$OSRefNo,$HBookId)
        {
          //r=mg/hoteldata/Cancelrsvn&ResNo=WWMGMA180637503&OSRefNo=9923012912335&HBookId=HBMA1806000254
            $xmlRequest = $this->renderPartial('req_CancelReservation'
                , array(
                    '$ResNo'=>$ResNo,
                    'OSRefNo'=>$OSRefNo,
                    'HBookId'=>$HBookId
                ), true);
            $jsonResult = ApiRequestor::post(ApiRequestor::URL_CANCEL_RESERVATION, $xmlRequest);

    //        $array = json_decode($jsonResult,TRUE);

            echo $jsonResult;
        }
}
