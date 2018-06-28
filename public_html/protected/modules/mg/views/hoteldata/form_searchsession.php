<form method="post" name="searchsession-form" action="<?php echo Yii::app()->baseUrl; ?>/index.php?r=mg/hoteldata/Searchsession">
      <!--<input type="text" name="destCountry" placeholder="destCountry"><br>-->
      <input type="text" name="city" placeholder="city"><br>
      <input type="text" name="checkIn" placeholder="2018-06-11"><br>
      <input type="text" name="checkOut" placeholder="2018-06-12"><br>
      <input type="text" name="NumRooms" placeholder="NumRooms"><br>
      <input type="text" name="AdultNum" placeholder="AdultNum"><br>
      <input type="text" name="ChildNum" placeholder="ChildNum"><br>
      <!--<input type="text" name="city" placeholder="city"><br>
      <input type="text" name="hotelCode" placeholder="hotelCode"><br>
      <input type="text" name="rommCatCode" placeholder="room Category Code"><br>
      <input type="text" name="checkIn" placeholder="2018-06-11"><br>
      <input type="text" name="checkOut" placeholder="2018-06-12"><br>
      <input type="text" name="AdultNum" placeholder="AdultNum"><br>
      <input type="text" name="RQBedChild" placeholder="RQBedChild"><br>
      <input type="text" name="ChildAge" placeholder="ChildAge"><br>-->
      <div style="margin-bottom:5px;">
               <button type="submit"  name="simpan" value="Simpan"   onClick="return validate();"> Search </button>
               <button onclick="goBack()">Go Back</button>
     </div>
</form>
