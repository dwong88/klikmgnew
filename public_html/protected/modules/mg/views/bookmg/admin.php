<?php
/* @var $this BookmgController */
/* @var $model Bookmg */

$this->breadcrumbs=array(
    'My Booking',
);

$this->menu=array(
    array('label'=>'List Bookmg', 'url'=>array('index')),
    array('label'=>'Create Bookmg', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#bookmg-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>My Booking</h1>
<?php Helper::showFlash(); ?>
<?php $this->widget('application.extensions.widget.GridView', array(
    'id'=>'bookmg-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'filterPosition'=>'',
    'columns'=>array(
        'property_name',
        array('name'=>'update_dt','type'=>'datetime'),
        'resno',
        'osrefno',
        'hbookid',
        'voucherno',
        array('name'=>'voucherdt','type'=>'date'),
        'status',
        array('name'=>'fromdt','type'=>'date'),
        array('name'=>'todt','type'=>'date'),
        'catgname',
        'bftype',
        'serviceno',
        'roomtype',
        array('name'=>'totalprice','type'=>'number','htmlOptions'=>array('class'=>'col-right')),
        array('name'=>'nightprice','type'=>'number','htmlOptions'=>array('class'=>'col-right')),
    ),
));



?>
