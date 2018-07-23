<div class="title-page">Search Hotel</div>

<?php Helper::registerNumberField('.tnumber');?>
<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'pencairan-form',
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array('onsubmit'=>'validateform(this); return false;'),
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php if($mSearch->hasErrors()) echo $form->errorSummary(array($mSearch)); ?>

    <?php Helper::showFlash(); ?>

    <div class="row">
        <?php echo $form->labelEx($mSearch,'nationalities'); ?>
        <?php echo $form->dropDownList($mSearch,'nationalities',CHtml::listData(Countrymg::model()->findAll(array('order'=>'country_name ASC')),'country_cd','country_name')); ?>
        <?php echo $form->error($mSearch,'nationalities'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($mSearch,'currency'); ?>
        <?php echo $form->dropDownList($mSearch,'currency', array('IDR'=>'IDR', 'USD'=>'USD')); ?>
        <?php echo $form->error($mSearch,'currency'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($mSearch,'destination'); ?>
        <?php echo $form->dropDownList($mSearch,'destination',CHtml::listData(Searchlocation::model()->findAll(array('order'=>'location_name ASC')),'selectVal','location_name')); ?>
        <?php echo $form->error($mSearch,'destination'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($mSearch,'checkin_dt'); ?>
        <?php $this->widget('application.extensions.widget.JuiDatePicker', array(
            'model'=>$mSearch,
            'attribute'=>'checkin_dt',
            'htmlOptions'=>array('size'=>10)
        ));
        ?>
        <?php echo $form->error($mSearch,'checkin_dt'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($mSearch,'duration'); ?>
        <?php
            $arr = array();
            for($dr=1;$dr<=30;$dr++)
                $arr[$dr] = $dr;
            echo $form->dropDownList($mSearch,'duration',$arr);
            ?>
        <?php echo $form->error($mSearch,'duration'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($mSearch,'tamu'); ?>
        <?php
        $arr = array();
        for($dr=1;$dr<=10;$dr++)
            $arr[$dr] = $dr;
        echo $form->dropDownList($mSearch,'tamu',$arr);
        ?>
        <?php echo $form->error($mSearch,'tamu'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($mSearch,'kamar'); ?>
        <?php echo $form->dropDownList($mSearch,'kamar',$arr); ?>
        <?php echo $form->error($mSearch,'kamar'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>

<?php if($mSearch->displayResult) { ?>
<table border="1">
<tr><td>Search Result</td></tr>
    <?php
        if($mSearch->kamar == null && $mSearch->tamu== null ){
            $NumRooms=0;
            $AdultNum=0;
        }
      echo "<tr><td width=20%>Destination</td><td>".$mSearch->locationName."</td></tr>";
      echo "<tr><td>check_in</td><td>".Yii::app()->format->formatDate($check_in)."</td></tr>";
      echo "<tr><td>check_out</td><td>".Yii::app()->format->formatDate($check_out)."</td></tr>";
      echo "<tr><td>AdultNum.</td><td>".$mSearch->tamu." People</td></tr>";
      echo "<tr><td>NumRooms</td><td>".$mSearch->kamar." Room</td></tr>";
    ?>

</table>
<hr>
<table border="1">
    <tr>
      <th>Name Hotel</th>
      <th>Address</th>
      <th>Room Price/Night</th>
      <th>Total Price</th>
      <th>Early Bird Type</th>
      <th>Early Bird</th>
      <th>Action</th>
    </tr>
    <?php
    //print_r($hotels);
    for($ht=0;$ht<$countselecthotels;$ht++)
    {
      echo "<tr>";
      echo "<td>".$hotels[$ht]['name']."</td>";
      echo "<td>".$hotels[$ht]['displayAddress']."</td>";
      echo "<td>".Yii::app()->format->formatNumber($hotels[$ht]['roomprice'])."</td>";
      echo "<td>".Yii::app()->format->formatNumber($hotels[$ht]['totalprice'])."</td>";
      echo "<td>".$hotels[$ht]['ebtype']."</td>";
      echo "<td>".Yii::app()->format->formatNumber($hotels[$ht]['ebrate'])."</td>";
      echo "<td><a target='_blank' href='".CHtml::normalizeUrl(array('/mg/hoteldata/gethoteldetail&hotelCode='.$hotels[$ht]['hotelCode']))."'>Detail</a></td>";
      echo "<td><a target='_blank' href='".CHtml::normalizeUrl(array('/mg/hoteldata/Viewhoteldetail&hotelCode='.$hotels[$ht]['hotelCode'].'&checkIn='.$check_in.'&checkOut='.$check_out.'&national='.$mSearch->nationalities.'&currency='.$mSearch->currency.'&AdultNum='.$mSearch->tamu.'&NumRooms='.$mSearch->kamar))."'>Rooms</a></td>";
      echo "<td><a target='_blank' href='".CHtml::normalizeUrl(array('/mg/hoteldata/ViewCancelPolicy&hotelCode='.$hotels[$ht]['hotelCode'].'&checkIn='.$check_in.'&checkOut='.$check_out.'&national='.$mSearch->nationalities.'&currency='.$mSearch->currency))."'>Cancel Policy</a></td>";
      echo "</tr>";
    }
    ?>
</table>

<script>
function getval(sel)
{
  alert(sel.value);
}
</script>

<?php } ?>
