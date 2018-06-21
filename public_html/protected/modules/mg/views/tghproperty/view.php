<?php
/* @var $this TghpropertyController */
/* @var $model Tghproperty */

$this->breadcrumbs=array(
	'Tghproperties'=>array('index'),
	$model->property_id,
);

$this->menu=array(
	array('label'=>'List Tghproperty', 'url'=>array('index')),
	array('label'=>'Create Tghproperty', 'url'=>array('create')),
	array('label'=>'Update Tghproperty', 'url'=>array('update', 'id'=>$model->property_id)),
	array('label'=>'Delete Tghproperty', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->property_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tghproperty', 'url'=>array('admin')),
);
?>

<h1>View Tghproperty #<?php echo $model->property_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'property_id',
		'property_cd',
		'property_name',
		'market_name',
		'property_type_id',
		'addressline1',
		'addressline2',
		'addressline3',
		'city_id',
		'postal_code',
		'country_id',
		'state_id',
		'weekend_start',
		'hotel_phone_number',
		'phone_number',
		'tax_number',
		'minimumroomrate',
		'numberofstar',
		'maximumchildage',
		'maximuminfantage',
		'bookingconfirmationemail',
		'bookingconfirmationccemail',
		'enquiryemail',
		'availabilityalertemail',
		'description',
		'gmaps_longitude',
		'gmaps_latitude',
		'available_cleaning_start',
		'available_cleaning_end',
		'locationinstruction',
		'sync_mgdate',
		'create_dt',
		'create_by',
		'update_dt',
		'update_by',
	),
)); ?>
