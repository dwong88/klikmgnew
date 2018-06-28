<Service_SearchHotel>
    <?php $this->renderPartial('/layouts/req_agentlogin'); ?>
    <SearchHotel_Request>
        <PaxPassport><?php echo ApiRequestor::PAX_PASSPORT; ?></PaxPassport>
        <RPCurrency>IDR</RPCurrency>
        <DestCountry><?php echo $destCountry; ?></DestCountry>
        <DestCity><?php echo $city; ?></DestCity>
        <HotelId><?php echo $hotelCode; ?></HotelId>
        <ServiceCode><?php echo $rommCatCode; ?></ServiceCode>
        <Period checkIn="<?php echo $checkIn; ?>" checkOut="<?php echo $checkOut; ?>"/>
        <RoomInfo>
            <AdultNum RoomType="" RQBedChild="<?php echo $RQBedChild; ?>"><?php echo $AdultNum; ?></AdultNum>
        </RoomInfo>
        <flagAvail/>
    </SearchHotel_Request>
</Service_SearchHotel>
