<Service_SearchAmendHotel>
    <?php $this->renderPartial('/layouts/req_agentlogin'); ?>
    <SearchAmendHotel_Request>
      <PaxPassport><?php echo ApiRequestor::PAX_PASSPORT; ?></PaxPassport>
      <RPCurrency>IDR</RPCurrency>
      <HotelId InternalCode="<?php echo $InternalCode; ?>" OrgResId="<?php echo $ResNo; ?>" OrgHBId="<?php echo $HBookId; ?>"><?php echo $hotelCode; ?></HotelId>
      <Period checkIn="<?php echo $checkIn; ?>" checkOut="<?php echo $checkOut; ?>"/> <RoomInfo>
      <AdultNum RoomType="<?php echo $RoomType; ?>"><?php echo $AdultNum; ?></AdultNum> <ChildAges>
      <ChildAge/> </ChildAges>
      </RoomInfo>
    <flagAvail/> </SearchAmendHotel_Request>
</Service_SearchAmendHotel>
