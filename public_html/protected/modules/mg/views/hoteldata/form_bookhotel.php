<!--<form method="post" name="getcancelpolicy-form" action="<?php echo Yii::app()->baseUrl; ?>/index.php?r=mg/hoteldata/BookHoteltest">
      <input type="text" name="hotelCode" placeholder="hotelCode"><br>
      <input type="text" name="internalCode" placeholder="CL005"><br>
      <input type="text" name="checkIn" placeholder="2018-06-11"><br>
      <input type="text" name="checkOut" placeholder="2018-06-12"><br>
      <input type="text" name="SeqNo" placeholder="1"><br>
      <input type="text" name="AdultNum" placeholder="AdultNum"><br>
      <input type="text" name="RoomType" placeholder="RoomType"><br>
      <input type="text" name="flagAvail" placeholder="True"><br>
      <input type="text" name="CatgId" placeholder="Category ID Room"><br>
      <input type="text" name="CatgName" placeholder="Category Name Room"><br>
      <input type="text" name="BFType" placeholder="breakfast"><br>
      <input type="text" name="price" placeholder="Price"><br>
      <div style="margin-bottom:5px;">
               <button type="submit"  name="simpan" value="Simpan"   onClick="return validate();"> Book </button>
               <button onclick="goBack()">Go Back</button>
     </div>
</form>-->

<?php
echo $jsonResult;
?>
