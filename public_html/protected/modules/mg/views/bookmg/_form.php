<?php
/* @var $this BookmgController */
/* @var $model Bookmg */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'bookmg-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ResNo'); ?>
		<?php echo $form->textField($model,'ResNo',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'ResNo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'OSRefNo'); ?>
		<?php echo $form->textField($model,'OSRefNo',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'OSRefNo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'HBookId'); ?>
		<?php echo $form->textField($model,'HBookId',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'HBookId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'VoucherNo'); ?>
		<?php echo $form->textField($model,'VoucherNo',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'VoucherNo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'VoucherDt'); ?>
		<?php echo $form->textField($model,'VoucherDt'); ?>
		<?php echo $form->error($model,'VoucherDt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Status'); ?>
		<?php echo $form->textField($model,'Status',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'Status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'HotelId'); ?>
		<?php echo $form->textField($model,'HotelId',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'HotelId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'FromDt'); ?>
		<?php echo $form->textField($model,'FromDt'); ?>
		<?php echo $form->error($model,'FromDt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ToDt'); ?>
		<?php echo $form->textField($model,'ToDt'); ?>
		<?php echo $form->error($model,'ToDt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'CatgId'); ?>
		<?php echo $form->textField($model,'CatgId',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'CatgId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'CatgName'); ?>
		<?php echo $form->textField($model,'CatgName',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'CatgName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'BFType'); ?>
		<?php echo $form->textField($model,'BFType',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'BFType'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ServiceNo'); ?>
		<?php echo $form->textField($model,'ServiceNo',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'ServiceNo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'RoomType'); ?>
		<?php echo $form->textField($model,'RoomType',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'RoomType'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SeqNo'); ?>
		<?php echo $form->textField($model,'SeqNo',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'SeqNo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TotalPrice'); ?>
		<?php echo $form->textField($model,'TotalPrice'); ?>
		<?php echo $form->error($model,'TotalPrice'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'GuestName'); ?>
		<?php echo $form->textArea($model,'GuestName',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'GuestName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_dt'); ?>
		<?php echo $form->textField($model,'update_dt'); ?>
		<?php echo $form->error($model,'update_dt'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->