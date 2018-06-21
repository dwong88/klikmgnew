<?php
/* @var $this TghpropertyController */
/* @var $model Tghproperty */

$this->breadcrumbs=array(
	'Tghproperties'=>array('index'),
	$model->property_id=>array('view','id'=>$model->property_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tghproperty', 'url'=>array('index')),
	array('label'=>'Create Tghproperty', 'url'=>array('create')),
	array('label'=>'View Tghproperty', 'url'=>array('view', 'id'=>$model->property_id)),
	array('label'=>'Manage Tghproperty', 'url'=>array('admin')),
);
?>

<h1>Update Tghproperty <?php echo $model->property_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>