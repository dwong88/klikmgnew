<?php
/* @var $this BookmgController */
/* @var $data Bookmg */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('booking_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->booking_id), array('view', 'id'=>$data->booking_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ResNo')); ?>:</b>
	<?php echo CHtml::encode($data->ResNo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('OSRefNo')); ?>:</b>
	<?php echo CHtml::encode($data->OSRefNo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('HBookId')); ?>:</b>
	<?php echo CHtml::encode($data->HBookId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('VoucherNo')); ?>:</b>
	<?php echo CHtml::encode($data->VoucherNo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('VoucherDt')); ?>:</b>
	<?php echo CHtml::encode($data->VoucherDt); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Status')); ?>:</b>
	<?php echo CHtml::encode($data->Status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('HotelId')); ?>:</b>
	<?php echo CHtml::encode($data->HotelId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('FromDt')); ?>:</b>
	<?php echo CHtml::encode($data->FromDt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ToDt')); ?>:</b>
	<?php echo CHtml::encode($data->ToDt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CatgId')); ?>:</b>
	<?php echo CHtml::encode($data->CatgId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CatgName')); ?>:</b>
	<?php echo CHtml::encode($data->CatgName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('BFType')); ?>:</b>
	<?php echo CHtml::encode($data->BFType); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ServiceNo')); ?>:</b>
	<?php echo CHtml::encode($data->ServiceNo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('RoomType')); ?>:</b>
	<?php echo CHtml::encode($data->RoomType); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SeqNo')); ?>:</b>
	<?php echo CHtml::encode($data->SeqNo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TotalPrice')); ?>:</b>
	<?php echo CHtml::encode($data->TotalPrice); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('GuestName')); ?>:</b>
	<?php echo CHtml::encode($data->GuestName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_dt')); ?>:</b>
	<?php echo CHtml::encode($data->update_dt); ?>
	<br />

	*/ ?>

</div>