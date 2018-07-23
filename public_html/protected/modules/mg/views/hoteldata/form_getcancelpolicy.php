<!--<form method="post" name="getcancelpolicy-form" action="<?php echo Yii::app()->baseUrl; ?>/index.php?r=mg/hoteldata/Getcancelpolicy&id=<?php echo $id;?>">
  <input type="text" name="ResNo" placeholder="ResNo" value="<?php echo $ResNo;?>"><br>
  <input type="text" name="OSRefNo" placeholder="OSRefNo" value="<?php echo $OSRefNo;?>"><br>
  <input type="text" name="HBookId" placeholder="HBookId" value="<?php echo $HBookId;?>"><br>
      <div style="margin-bottom:5px;">
               <button type="submit"  name="simpan" value="Simpan"   onClick="return validate();"> Submit </button>
               <button onclick="goBack()">Go Back</button>
     </div>
</form>
-->
<?php echo $jsonResult;?>
