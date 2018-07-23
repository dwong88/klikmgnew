<?php
/**
 * Created by PhpStorm.
 * User: wiyantotan
 * Date: 06/06/18
 * Time: 19:02
 */

class ApiRequestor
{
    const URL_GET_HOTEL_DETAIL = 'http://xml.travflex.com/11WS/ServicePHP/GetHotelDetails.php';
    const URL_SEARCH_HOTEL = 'http://xml.travflex.com/11WS_SP2_1/ServicePHP/SearchHotel.php';
    const URL_VIEW_CANCEL_POLICY = 'http://xml.travflex.com/11WS_SP2_1/ServicePHP/ViewCancelPolicy.php';
    const URL_BOOK_HOTEL = 'http://xml.travflex.com/11WS_SP2_1/ServicePHP/BookHotel.php';
    const URL_ACCEPT_BOOKING = 'http://xml.travflex.com/11WS_SP2_1/ServicePHP/AcceptBooking.php';
    const URL_GET_RSVN_INFO = 'http://xml.travflex.com/11WS_SP2_1/ServicePHP/GetRSVNInfo.php';
    const URL_GET_CANCEL_POLICY = 'http://xml.travflex.com/11WS_SP2_1/ServicePHP/GetCancelpolicy.php';
    const URL_CANCEL_RESERVATION = 'http://xml.travflex.com/11WS_SP2_1/ServicePHP/CancelRsvn.php';
    const URL_SEARCH_AMEND_HOTEL = 'http://xml.travflex.com/11WS_SP2_1/ServicePHP/SearchAmendHotel.php';
    const MG_AGENT_ID = 'WWMG';
    const MG_USERNAME = 'WWMGXML';
    const MG_PASSWORD = 'WWMGXML2018';

    const PAX_PASSPORT = 'WSASSG';

    /**
     * @param $url url to request
     * @param $xml xml string to request
     * @return mixed the result of curl request.
     * @throws Exception
     */
    private static function remoteCall($url, $xml)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'requestXML=<?xml version="1.0" encoding="utf-8" ?>'.$xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // ask for results to be returned

        // Send to remote and return data to caller.
        $result = curl_exec($ch);
        curl_close($ch);
        //echo $result;
        $result = stristr($result,"<?xml");

        if ($result === FALSE) {
            throw new Exception('CURL Error: ' . curl_error($ch), curl_errno($ch));
        }

        return $result;
    }

    /**
     * @param $url
     * @param $xml
     * @return string json
     * @throws Exception
     */
    public static function post($url, $xml)
    {
        $result = self::remoteCall($url, $xml);

        $xml = simplexml_load_string($result);

        echo $xml;
        //Yii::app()->end();
        return json_encode($xml);
    }
}
