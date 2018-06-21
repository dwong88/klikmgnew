<form method="post" name="getcancelpolicy-form" action="<?php echo Yii::app()->baseUrl; ?>/index.php?r=mg/hoteldata/Getcancelpolicy">
  <input type="text" name="ResNo" placeholder="ResNo"><br>
  <input type="text" name="OSRefNo" placeholder="OSRefNo"><br>
  <input type="text" name="HBookId" placeholder="HBookId"><br>
      <div style="margin-bottom:5px;">
               <button type="submit"  name="simpan" value="Simpan"   onClick="return validate();"> Simpan </button>
     </div>
</form>
