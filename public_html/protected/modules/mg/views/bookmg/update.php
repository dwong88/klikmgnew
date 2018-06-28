<?php
/* @var $this BookmgController */
/* @var $model Bookmg */

$this->breadcrumbs=array(
	'Bookmgs'=>array('index'),
	$model->booking_id=>array('view','id'=>$model->booking_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Bookmg', 'url'=>array('index')),
	array('label'=>'Create Bookmg', 'url'=>array('create')),
	array('label'=>'View Bookmg', 'url'=>array('view', 'id'=>$model->booking_id)),
	array('label'=>'Manage Bookmg', 'url'=>array('admin')),
);
?>

<h1>Update Bookmg <?php echo $model->booking_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>