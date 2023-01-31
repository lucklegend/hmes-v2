<?php
/* @var $this StocksController */
/* @var $model Stocks */

$this->breadcrumbs=array(
	'Stocks'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Stocks', 'url'=>array('index')),
	array('label'=>'Create Stocks', 'url'=>array('create')),
	array('label'=>'Update Stocks', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Stocks', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Stocks', 'url'=>array('admin')),
);
?>

<h1>View Stocks #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'stockCode',
		'supplyID',
		'name',
		'description',
		'manufacturer',
		'unit',
		'quantity',
		'daterecieved',
		'dateopened',
		'expiry_date',
		'recieved_by',
		'threshold_limit',
		'location',
		'batch_number',
		// 'supplierID',
		array(
			'name'=>'supplierID',
			'value'=>$model->suppliers->name,
			),
		'amount',
		'totalAmount'
	),
)); ?>
