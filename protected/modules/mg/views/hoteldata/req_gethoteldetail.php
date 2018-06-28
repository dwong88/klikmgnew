<Service_GetHotelDetail>
    <?php $this->renderPartial('/layouts/req_agentlogin'); ?>
    <GetHotelDetail_Request>
        <HotelId><?php echo $hotelCode; ?></HotelId>
    </GetHotelDetail_Request>
</Service_GetHotelDetail>
