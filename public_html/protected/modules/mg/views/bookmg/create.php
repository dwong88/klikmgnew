<?php
/* @var $this BookmgController */
/* @var $model Bookmg */

$this->breadcrumbs=array(
	'Bookmgs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Bookmg', 'url'=>array('index')),
	array('label'=>'Manage Bookmg', 'url'=>array('admin')),
);
?>

<h1>Create Bookmg</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>