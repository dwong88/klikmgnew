<?php
/* @var $this TghpropertyController */
/* @var $model Tghproperty */

$this->breadcrumbs=array(
	'Tghproperties'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Tghproperty', 'url'=>array('index')),
	array('label'=>'Create Tghproperty', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#tghproperty-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Tghproperties</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tghproperty-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'property_id',
		'property_cd',
		'property_name',
		'market_name',
		'property_type_id',
		'addressline1',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
