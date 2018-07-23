<?php
if(isset($_GET['id'])) { ?>
    <form method="post" name="getcancelpolicy-form"
          action="<?php echo Yii::app()->baseUrl; ?>/index.php?r=mg/hoteldata/AmendHotel">
        <input type="text" name="hotelCode" placeholder="hotelCode" value="<?php echo $model->hotelid ?>" readonly><br>
        <input type="text" name="internalCode" placeholder="CL005" value="CL005" readonly><br>
        <input type="text" name="SeqNo" placeholder="1" value="<?php echo $model->seqno ?>" readonly><br>
        <input type="text" name="osrefno" placeholder="osrefno" value="<?php echo $model->osrefno ?>" readonly><br>
        <input type="text" name="checkIn" placeholder="2018-06-11" value="<?php echo $model->fromdt ?>"><br>
        <input type="text" name="checkOut" placeholder="2018-06-12" value="<?php echo $model->todt ?>"><br>
        <input type="text" name="AdultNum" placeholder="AdultNum"><br>
        <input type="text" name="catgid" placeholder="catgid" value="<?php echo $model->catgid ?>"><br>
        <input type="text" name="catgname" placeholder="catgname" value="<?php echo $model->catgname ?>" readonly><br>
        <input type="text" name="bftype" placeholder="bftype" value="<?php echo $model->bftype ?>"readonly><br>
        <input type="text" name="nightprice" placeholder="nightprice" value="<?php echo $model->nightprice ?>"readonly><br>
        <input type="text" name="RoomType" placeholder="RoomType" value="<?php echo $model->roomtype ?>"><br>
        <input type="text" name="ResNo" placeholder="ResNo" value="<?php echo $model->resno ?>" readonly><br>
        <input type="text" name="HBookId" placeholder="HBookId" value="<?php echo $model->hbookid ?>" readonly><br>
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
