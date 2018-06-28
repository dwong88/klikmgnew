<?php
/* @var $this BookmgController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Bookmgs',
);

$this->menu=array(
	array('label'=>'Create Bookmg', 'url'=>array('create')),
	array('label'=>'Manage Bookmg', 'url'=>array('admin')),
);
?>

<h1>Bookmgs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
