<Service_ViewCancelPolicy>
    <?php $this->renderPartial('/layouts/req_agentlogin'); ?>
    <ViewCancelPolicy_Request>
        <PaxPassport><?php echo ApiRequestor::PAX_PASSPORT; ?></PaxPassport>
        <RPCurrency>IDR</RPCurrency>
        <HotelId><?php echo $hotelCode; ?></HotelId>
        <InternalCode><?php echo $InternalCode; ?></InternalCode>
        <dtCheckIn><?php echo $checkIn; ?></dtCheckIn> <dtCheckOut><?php echo $checkOut; ?></dtCheckOut>
<RoomInfo SeqNo="1" RoomType="<?php echo $RoomType; ?>" RQBedChild="N">
<AdultNum><?php echo $AdultNum; ?></AdultNum> <ChildAges>
<ChildAge/> </ChildAges>
</RoomInfo> <CancelPolicyID></CancelPolicyID> <flagAvail><?php echo $flagAvail; ?></flagAvail>
    </ViewCancelPolicy_Request>
</Service_ViewCancelPolicy>
