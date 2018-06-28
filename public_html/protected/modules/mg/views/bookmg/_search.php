<?php
/* @var $this BookmgController */
/* @var $model Bookmg */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'booking_id'); ?>
		<?php echo $form->textField($model,'booking_id',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ResNo'); ?>
		<?php echo $form->textField($model,'ResNo',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'OSRefNo'); ?>
		<?php echo $form->textField($model,'OSRefNo',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'HBookId'); ?>
		<?php echo $form->textField($model,'HBookId',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'VoucherNo'); ?>
		<?php echo $form->textField($model,'VoucherNo',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'VoucherDt'); ?>
		<?php echo $form->textField($model,'VoucherDt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Status'); ?>
		<?php echo $form->textField($model,'Status',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'HotelId'); ?>
		<?php echo $form->textField($model,'HotelId',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'FromDt'); ?>
		<?php echo $form->textField($model,'FromDt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ToDt'); ?>
		<?php echo $form->textField($model,'ToDt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CatgId'); ?>
		<?php echo $form->textField($model,'CatgId',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CatgName'); ?>
		<?php echo $form->textField($model,'CatgName',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'BFType'); ?>
		<?php echo $form->textField($model,'BFType',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ServiceNo'); ?>
		<?php echo $form->textField($model,'ServiceNo',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'RoomType'); ?>
		<?php echo $form->textField($model,'RoomType',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SeqNo'); ?>
		<?php echo $form->textField($model,'SeqNo',array('size'=>3,'maxlength'=>3)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TotalPrice'); ?>
		<?php echo $form->textField($model,'TotalPrice'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'GuestName'); ?>
		<?php echo $form->textArea($model,'GuestName',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_dt'); ?>
		<?php echo $form->textField($model,'update_dt'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->