<h3>Stocks</h3>
<?php 

$this->widget('zii.widgets.grid.CGridView', array(
	//'id'=>'grid-import',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'dataProvider'=>$model,
	'columns'=>array(
		//'ID',
		'stockCode',
		'supplyID',
		// array(
		// 	'name'=>'supplyID',
		// 	//'type'=>'raw',
		// 	//'header'=>'Supply',
		// 	// 'value'=>'Supplies::model()->getSupplyname($data->supplyID)',
		// 	'value'=>$data->supplyID,
		// 	// 'value'=>function($data){
		// 	// 	return "erew";
		// 	// }
		// 	),
		'name',
		// 'description',
		// 'manufacturer',
		// 'unit',
		// 'quantity',
		// 'supplierID',
		// array(
		// 	'name'=>'supplierID',
		// 	// 'type'=>'raw',
		// 	// 'header'=>'supplier',
		// 	// 'value'=>'Suppliers::model()->getSuppliername('.$data->supplierID.')',
		// 	'value'=>$data->supplierID,
		// 	),
		'daterecieved',
		'dateopened',
		'expiry_date',
		// 'recieved_by',
		// 'threshold_limit',
		'location',
		'batch_number',
		// 'amount'
	),
)); 


?>

<?php

echo CHtml::link('Download',$this->createUrl('download',array('datestart'=>$datestart,'dateend'=>$dateend)),array('class'=>'btn btn-
success')); ?>
