<Service_SearchHotel>
    <?php $this->renderPartial('/layouts/req_agentlogin'); ?>
    <SearchHotel_Request>
        <PaxPassport><?php echo $nationalities; ?></PaxPassport>
        <RPCurrency><?php echo $currency; ?></RPCurrency>
        <DestCountry><?php echo $destCountry; ?></DestCountry>
        <DestCity><?php echo $city; ?></DestCity>
        <HotelId><?php echo $hotelCode; ?></HotelId>
        <ServiceCode><?php echo $rommCatCode; ?></ServiceCode>
        <Period checkIn="<?php echo $checkIn; ?>" checkOut="<?php echo $checkOut; ?>"/>
        <?php
        if($NumRooms>1){
        for($sec=0;$sec<$NumRooms;$sec++){?>
        <RoomInfo>
            <AdultNum RoomType="" RQBedChild=""><?php echo $arrRooms[$sec]['numAdults']; ?></AdultNum>
        </RoomInfo>
      <?php }
          }else{?>
        <RoomInfo>
            <AdultNum RoomType="" RQBedChild=""><?php echo $AdultNum; ?></AdultNum>
        </RoomInfo>
        <?php }?>
        <flagAvail>Y</flagAvail>
    </SearchHotel_Request>
</Service_SearchHotel>
