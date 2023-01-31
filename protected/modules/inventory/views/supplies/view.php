<?php
/* @var $this SuppliesController */
/* @var $model Supplies */

$this->breadcrumbs=array(
	'Supplies'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Supplies', 'url'=>array('index')),
	array('label'=>'Create Supplies', 'url'=>array('create')),
	array('label'=>'Update Supplies', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Supplies', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Supplies', 'url'=>array('admin')),
);
?>

<h1>View Supplies #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		array(
			'name'=>'lab',
			'value'=>$model->labaccess->labName,
			),
		'description',
	),
)); ?>
        <?php echo CHtml::link('<span class="icon-edit icon-white"></span> Import Stock', array('stocks/admin'), array('class'=>'btn btn-inverse', 'style'=>'margin: 0.2em 0 0.5em 0; float:right;','title'=>'Import Stock'));?>
<h1>Stocks</h1>	

<div class="row-fluid">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'stocks-grid',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'dataProvider'=>$stocks->searchbysupply($model->id),
	'filter'=>$stocks,
	'columns'=>array(
		// 'id',
		'stockCode',
		// 'supplyID',
		'name',
		'description',
		// 'manufacturer',
		
		// 'unit',
		// 'quantity',
		// 'daterecieved',
		// 'dateopened',
		// 'expiry_date',
		// 'recieved_by',
		// 'threshold_limit',
		// 'location',
		// 'batch_number',
		// 'supplierID',
		array(
			'name'=>'supplier',
			'value'=>'$data->suppliers->name'),
		// 'amount',
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
</div>