<?php
 $this->widget('ext.widgets.DetailView4Col', array(
	'cssFile'=>false,
	'htmlOptions'=>array('class'=>'detail-view table table-striped table-condensed'),
	'data'=>$quotation->data->customer,
	'attributes'=>array(
		array(
            'name'=>'CustomerDetails',
			'cssClass'=>'title-row',
            'oneRow'=>true,
            'type'=>'raw',
            'value'=>'',
        ),
		'name', 'telNo',
		array(
			'name'=>'address',
			'type'=>'raw',
			'value'=> $quotation->data->customer->address->barangay.', '.$quotation->data->customer->address->locality.', '.$quotation->data->customer->address->province.', '.$quotation->data->customer->address->country
			),
		'faxNo',
		'type.label', 'email'
	),
)); ?>

<?php
foreach($gridDataProvider as $dataProvider){
	$this->widget('zii.widgets.grid.CGridView', array(
	//$this->widget('bootstrap.widgets.TbGridView', array(
		'id'=>'analysis',
		'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
		'htmlOptions'=>array('class'=>'grid-view padding0 paddingLeftRight10'),
		'dataProvider'=>$dataProvider,
		//'filter'=>$model,
		'columns'=>array(
			//'id',
			array(
				'name'=>'testName',
				'header'=>'Test Name',
				'htmlOptions' => array('style' => 'width: 250px; padding-left: 10px; text-align: left;')
			),
			array(
				'name'=>'method',
				'header'=>'Method',
				'htmlOptions' => array('style' => 'width: 250px; padding-left: 10px; text-align: left;')
			),
			array(
				'name'=>'reference',
				'header'=>'Reference',
				'htmlOptions' => array('style' => 'width: 500px; padding-left: 10px; text-align: left;')
			),
			array(
				'name'=>'fee',
				'header'=>'Fee',
				'value'=>'Yii::app()->format->formatNumber($data->fee)',
				'htmlOptions' => array('style' => 'width: 75px; padding-right: 20px; text-align: right;'),
				//'footer'=>
				// 'footer'=>function($data){
				// 	$total = 0;
				// 	foreach($data as $item){
				// 		$total += $item->fee;
				// 	}
				// 	echo $total;
				// },
				'footerHtmlOptions'=>array('style'=>'text-align: right; padding-right: 20px;'),
			),
		),
	));
} 


?>
<pre>
<?php 
	//print_r($quotation->data->analysesCart);
	print_r($quotation);
?>
</pre>

