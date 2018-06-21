<?php
/* @var $this TghpropertyController */
/* @var $model Tghproperty */

$this->breadcrumbs=array(
	'Tghproperties'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tghproperty', 'url'=>array('index')),
	array('label'=>'Manage Tghproperty', 'url'=>array('admin')),
);
?>

<h1>Create Tghproperty</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>