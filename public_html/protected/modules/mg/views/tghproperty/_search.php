<?php
/* @var $this TghpropertyController */
/* @var $model Tghproperty */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'property_id'); ?>
		<?php echo $form->textField($model,'property_id',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'property_cd'); ?>
		<?php echo $form->textField($model,'property_cd',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'property_name'); ?>
		<?php echo $form->textField($model,'property_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'market_name'); ?>
		<?php echo $form->textField($model,'market_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'property_type_id'); ?>
		<?php echo $form->textField($model,'property_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'addressline1'); ?>
		<?php echo $form->textArea($model,'addressline1',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'addressline2'); ?>
		<?php echo $form->textArea($model,'addressline2',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'addressline3'); ?>
		<?php echo $form->textArea($model,'addressline3',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'city_id'); ?>
		<?php echo $form->textField($model,'city_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'postal_code'); ?>
		<?php echo $form->textField($model,'postal_code',array('size'=>5,'maxlength'=>5)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'country_id'); ?>
		<?php echo $form->textField($model,'country_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'state_id'); ?>
		<?php echo $form->textField($model,'state_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'weekend_start'); ?>
		<?php echo $form->textField($model,'weekend_start',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hotel_phone_number'); ?>
		<?php echo $form->textField($model,'hotel_phone_number',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phone_number'); ?>
		<?php echo $form->textField($model,'phone_number',array('size'=>15,'maxlength'=>15)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tax_number'); ?>
		<?php echo $form->textField($model,'tax_number',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'minimumroomrate'); ?>
		<?php echo $form->textField($model,'minimumroomrate',array('size'=>5,'maxlength'=>5)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'numberofstar'); ?>
		<?php echo $form->textField($model,'numberofstar',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'maximumchildage'); ?>
		<?php echo $form->textField($model,'maximumchildage',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'maximuminfantage'); ?>
		<?php echo $form->textField($model,'maximuminfantage',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bookingconfirmationemail'); ?>
		<?php echo $form->textField($model,'bookingconfirmationemail',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bookingconfirmationccemail'); ?>
		<?php echo $form->textField($model,'bookingconfirmationccemail',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'enquiryemail'); ?>
		<?php echo $form->textField($model,'enquiryemail',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'availabilityalertemail'); ?>
		<?php echo $form->textField($model,'availabilityalertemail',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gmaps_longitude'); ?>
		<?php echo $form->textField($model,'gmaps_longitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gmaps_latitude'); ?>
		<?php echo $form->textField($model,'gmaps_latitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'available_cleaning_start'); ?>
		<?php echo $form->textField($model,'available_cleaning_start',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'available_cleaning_end'); ?>
		<?php echo $form->textField($model,'available_cleaning_end',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'locationinstruction'); ?>
		<?php echo $form->textArea($model,'locationinstruction',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sync_mgdate'); ?>
		<?php echo $form->textField($model,'sync_mgdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_dt'); ?>
		<?php echo $form->textField($model,'create_dt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_by'); ?>
		<?php echo $form->textField($model,'create_by',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_dt'); ?>
		<?php echo $form->textField($model,'update_dt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_by'); ?>
		<?php echo $form->textField($model,'update_by',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->