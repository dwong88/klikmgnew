<?php
/* @var $this TghpropertyController */
/* @var $model Tghproperty */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tghproperty-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'property_cd'); ?>
		<?php echo $form->textField($model,'property_cd',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'property_cd'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'property_name'); ?>
		<?php echo $form->textField($model,'property_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'property_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'market_name'); ?>
		<?php echo $form->textField($model,'market_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'market_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'property_type_id'); ?>
		<?php echo $form->textField($model,'property_type_id'); ?>
		<?php echo $form->error($model,'property_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'addressline1'); ?>
		<?php echo $form->textArea($model,'addressline1',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'addressline1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'addressline2'); ?>
		<?php echo $form->textArea($model,'addressline2',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'addressline2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'addressline3'); ?>
		<?php echo $form->textArea($model,'addressline3',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'addressline3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city_id'); ?>
		<?php echo $form->textField($model,'city_id'); ?>
		<?php echo $form->error($model,'city_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postal_code'); ?>
		<?php echo $form->textField($model,'postal_code',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'postal_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'country_id'); ?>
		<?php echo $form->textField($model,'country_id'); ?>
		<?php echo $form->error($model,'country_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'state_id'); ?>
		<?php echo $form->textField($model,'state_id'); ?>
		<?php echo $form->error($model,'state_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'weekend_start'); ?>
		<?php echo $form->textField($model,'weekend_start',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'weekend_start'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hotel_phone_number'); ?>
		<?php echo $form->textField($model,'hotel_phone_number',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'hotel_phone_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone_number'); ?>
		<?php echo $form->textField($model,'phone_number',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'phone_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tax_number'); ?>
		<?php echo $form->textField($model,'tax_number',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'tax_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'minimumroomrate'); ?>
		<?php echo $form->textField($model,'minimumroomrate',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'minimumroomrate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'numberofstar'); ?>
		<?php echo $form->textField($model,'numberofstar',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'numberofstar'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'maximumchildage'); ?>
		<?php echo $form->textField($model,'maximumchildage',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'maximumchildage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'maximuminfantage'); ?>
		<?php echo $form->textField($model,'maximuminfantage',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'maximuminfantage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bookingconfirmationemail'); ?>
		<?php echo $form->textField($model,'bookingconfirmationemail',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'bookingconfirmationemail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bookingconfirmationccemail'); ?>
		<?php echo $form->textField($model,'bookingconfirmationccemail',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'bookingconfirmationccemail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'enquiryemail'); ?>
		<?php echo $form->textField($model,'enquiryemail',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'enquiryemail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'availabilityalertemail'); ?>
		<?php echo $form->textField($model,'availabilityalertemail',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'availabilityalertemail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gmaps_longitude'); ?>
		<?php echo $form->textField($model,'gmaps_longitude'); ?>
		<?php echo $form->error($model,'gmaps_longitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gmaps_latitude'); ?>
		<?php echo $form->textField($model,'gmaps_latitude'); ?>
		<?php echo $form->error($model,'gmaps_latitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'available_cleaning_start'); ?>
		<?php echo $form->textField($model,'available_cleaning_start',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'available_cleaning_start'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'available_cleaning_end'); ?>
		<?php echo $form->textField($model,'available_cleaning_end',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'available_cleaning_end'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'locationinstruction'); ?>
		<?php echo $form->textArea($model,'locationinstruction',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'locationinstruction'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sync_mgdate'); ?>
		<?php echo $form->textField($model,'sync_mgdate'); ?>
		<?php echo $form->error($model,'sync_mgdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_dt'); ?>
		<?php echo $form->textField($model,'create_dt'); ?>
		<?php echo $form->error($model,'create_dt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_by'); ?>
		<?php echo $form->textField($model,'create_by',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'create_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_dt'); ?>
		<?php echo $form->textField($model,'update_dt'); ?>
		<?php echo $form->error($model,'update_dt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_by'); ?>
		<?php echo $form->textField($model,'update_by',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'update_by'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->