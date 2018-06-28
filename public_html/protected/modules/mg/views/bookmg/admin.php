<?php
/* @var $this BookmgController */
/* @var $model Bookmg */

$this->breadcrumbs=array(
	'Bookmgs'=>array('index'),
	'Manage',
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

<h1>Manage Bookmgs</h1>

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

<?php $this->widget('application.extensions.widget.GridView', array(
	'id'=>'bookmg-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'booking_id',
		'user_id',
		'ResNo',
		'OSRefNo',
		'HBookId',
		'VoucherNo',
		/*
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
		*/
        array(
            'class'=>'application.extensions.widget.ButtonColumn',
        ),
	),
));



?>
