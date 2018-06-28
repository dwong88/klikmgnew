<?php
/* @var $this BookmgController */
/* @var $model Bookmg */

$this->breadcrumbs=array(
	'Bookmgs'=>array('index'),
	$model->booking_id,
);

$this->menu=array(
	array('label'=>'List Bookmg', 'url'=>array('index')),
	array('label'=>'Create Bookmg', 'url'=>array('create')),
	array('label'=>'Update Bookmg', 'url'=>array('update', 'id'=>$model->booking_id)),
	array('label'=>'Delete Bookmg', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->booking_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Bookmg', 'url'=>array('admin')),
);
?>

<h1>View Bookmg #<?php echo $model->booking_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'booking_id',
		'user_id',
		'ResNo',
		'OSRefNo',
		'HBookId',
		'VoucherNo',
		'VoucherDt',
		'Status',
		'HotelId',
		'FromDt',
		'ToDt',
		'CatgId',
		'CatgName',
		'BFType',
		'ServiceNo',
		'RoomType',
		'SeqNo',
		'TotalPrice',
		'GuestName',
		'update_dt',
	),
)); ?>
