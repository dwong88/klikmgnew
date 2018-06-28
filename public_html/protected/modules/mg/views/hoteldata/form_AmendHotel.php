<?php
if(isset($_GET['id'])) { ?>
    <form method="post" name="getcancelpolicy-form"
          action="<?php echo Yii::app()->baseUrl; ?>/index.php?r=mg/hoteldata/AmendHotel">
        <input type="text" name="hotelCode" placeholder="hotelCode" value="<?php echo $model->HotelId ?>" readonly><br>
        <input type="text" name="internalCode" placeholder="CL005" value=""><br>
        <input type="text" name="checkIn" placeholder="2018-06-11" value="<?php echo $model->FromDt ?>"><br>
        <input type="text" name="checkOut" placeholder="2018-06-12" value="<?php echo $model->ToDt ?>"><br>
        <input type="text" name="AdultNum" placeholder="AdultNum"><br>
        <input type="text" name="RoomType" placeholder="RoomType" value="<?php echo $model->RoomType ?>"><br>
        <input type="text" name="ResNo" placeholder="ResNo" value="<?php echo $model->ResNo ?>" readonly><br>
        <input type="text" name="HBookId" placeholder="HBookId" value="<?php echo $model->HBookId ?>" readonly><br>
        <div style="margin-bottom:5px;">
            <button type="submit" name="simpan" value="Simpan" onClick="return validate();"> Save</button>
            <button onclick="goBack()">Go Back</button>
        </div>
    </form>

    <?php
}
else{
    echo $jsonResult;
}

?>