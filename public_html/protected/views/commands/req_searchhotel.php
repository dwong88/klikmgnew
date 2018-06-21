<Service_SearchHotel>
    <?php $this->renderInternal(Yii::app()->basePath.'/views/commands/req_agentlogin.php'); ?>
    <SearchHotel_Request>
        <PaxPassport><?php echo ApiRequestor::PAX_PASSPORT; ?></PaxPassport>
        <DestCountry><?php echo $destCountry; ?></DestCountry>
        <DestCity><?php echo $city; ?></DestCity>
        <HotelId><?php echo $hotelCode; ?></HotelId>
        <ServiceCode><?php echo $rommCatCode; ?></ServiceCode>
        <Period checkIn="<?php echo $checkIn; ?>" checkOut="<?php echo $checkOut; ?>"/>
        <RoomInfo>
            <AdultNum RoomType="Twin" RQBedChild="N">2</AdultNum>
        </RoomInfo>
        <flagAvail/>
    </SearchHotel_Request>
</Service_SearchHotel>