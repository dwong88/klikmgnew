<?php
/* @var $this TghpropertyController */
/* @var $data Tghproperty */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('property_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->property_id), array('view', 'id'=>$data->property_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('property_cd')); ?>:</b>
	<?php echo CHtml::encode($data->property_cd); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('property_name')); ?>:</b>
	<?php echo CHtml::encode($data->property_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('market_name')); ?>:</b>
	<?php echo CHtml::encode($data->market_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('property_type_id')); ?>:</b>
	<?php echo CHtml::encode($data->property_type_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('addressline1')); ?>:</b>
	<?php echo CHtml::encode($data->addressline1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('addressline2')); ?>:</b>
	<?php echo CHtml::encode($data->addressline2); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('addressline3')); ?>:</b>
	<?php echo CHtml::encode($data->addressline3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city_id')); ?>:</b>
	<?php echo CHtml::encode($data->city_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postal_code')); ?>:</b>
	<?php echo CHtml::encode($data->postal_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('country_id')); ?>:</b>
	<?php echo CHtml::encode($data->country_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state_id')); ?>:</b>
	<?php echo CHtml::encode($data->state_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weekend_start')); ?>:</b>
	<?php echo CHtml::encode($data->weekend_start); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hotel_phone_number')); ?>:</b>
	<?php echo CHtml::encode($data->hotel_phone_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone_number')); ?>:</b>
	<?php echo CHtml::encode($data->phone_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tax_number')); ?>:</b>
	<?php echo CHtml::encode($data->tax_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('minimumroomrate')); ?>:</b>
	<?php echo CHtml::encode($data->minimumroomrate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('numberofstar')); ?>:</b>
	<?php echo CHtml::encode($data->numberofstar); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('maximumchildage')); ?>:</b>
	<?php echo CHtml::encode($data->maximumchildage); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('maximuminfantage')); ?>:</b>
	<?php echo CHtml::encode($data->maximuminfantage); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bookingconfirmationemail')); ?>:</b>
	<?php echo CHtml::encode($data->bookingconfirmationemail); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bookingconfirmationccemail')); ?>:</b>
	<?php echo CHtml::encode($data->bookingconfirmationccemail); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('enquiryemail')); ?>:</b>
	<?php echo CHtml::encode($data->enquiryemail); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('availabilityalertemail')); ?>:</b>
	<?php echo CHtml::encode($data->availabilityalertemail); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gmaps_longitude')); ?>:</b>
	<?php echo CHtml::encode($data->gmaps_longitude); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gmaps_latitude')); ?>:</b>
	<?php echo CHtml::encode($data->gmaps_latitude); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('available_cleaning_start')); ?>:</b>
	<?php echo CHtml::encode($data->available_cleaning_start); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('available_cleaning_end')); ?>:</b>
	<?php echo CHtml::encode($data->available_cleaning_end); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('locationinstruction')); ?>:</b>
	<?php echo CHtml::encode($data->locationinstruction); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sync_mgdate')); ?>:</b>
	<?php echo CHtml::encode($data->sync_mgdate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_dt')); ?>:</b>
	<?php echo CHtml::encode($data->create_dt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_by')); ?>:</b>
	<?php echo CHtml::encode($data->create_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_dt')); ?>:</b>
	<?php echo CHtml::encode($data->update_dt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_by')); ?>:</b>
	<?php echo CHtml::encode($data->update_by); ?>
	<br />

	*/ ?>

</div>