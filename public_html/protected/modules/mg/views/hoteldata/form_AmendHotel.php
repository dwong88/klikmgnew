<form method="post" name="getcancelpolicy-form" action="<?php echo Yii::app()->baseUrl; ?>/index.php?r=mg/hoteldata/AmendHotel">
      <input type="text" name="hotelCode" placeholder="hotelCode"><br>
      <input type="text" name="internalCode" placeholder="CL005"><br>
      <input type="text" name="checkIn" placeholder="2018-06-11"><br>
      <input type="text" name="checkOut" placeholder="2018-06-12"><br>
      <input type="text" name="AdultNum" placeholder="AdultNum"><br>
      <input type="text" name="RoomType" placeholder="RoomType"><br>
      <input type="text" name="ResNo" placeholder="breakfast"><br>
      <input type="text" name="HBookId" placeholder="1"><br>
      <div style="margin-bottom:5px;">
               <button type="submit"  name="simpan" value="Simpan"   onClick="return validate();"> Simpan </button>
     </div>
</form>
