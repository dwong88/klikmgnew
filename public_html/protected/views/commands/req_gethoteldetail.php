<Service_GetHotelDetail>
    <?php $this->renderInternal(Yii::app()->basePath.'/views/commands/req_agentlogin.php'); ?>
    <GetHotelDetail_Request>
        <HotelId><?php echo $hotelCode; ?></HotelId>
    </GetHotelDetail_Request>
</Service_GetHotelDetail>
