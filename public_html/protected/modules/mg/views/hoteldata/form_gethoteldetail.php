<form method="post" name="gethoteldetial-form" action="<?php echo Yii::app()->baseUrl; ?>/index.php?r=mg/hoteldata/gethoteldetail">
      <input type="text" name="hotelCode">
      <div style="margin-bottom:5px;">
               <button type="submit"  name="simpan" value="Simpan"   onClick="return validate();"> Simpan </button>
     </div>
</form>
