<?php
/* @var $this TghpropertyController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tghproperties',
);

$this->menu=array(
	array('label'=>'Create Tghproperty', 'url'=>array('create')),
	array('label'=>'Manage Tghproperty', 'url'=>array('admin')),
);
?>

<h1>Tghproperties</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
