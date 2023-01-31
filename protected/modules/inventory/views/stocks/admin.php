<?php
/* @var $this StocksController */
/* @var $model Stocks */


	/*******************************************************
	name: Bergel T. Cutara
	org: DOST-IX, 991-1024
	date created: Jan 6 2017
	description: interface for stocks, allows to download data entry form , load file and push temp data to database
	********************************************************/

$this->breadcrumbs=array(
	'Stocks'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Stocks', 'url'=>array('index')),
	array('label'=>'Create Stocks', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#stocks-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

?>
<h1>Import <I>NEW</I> Stocks via Excel </h1>
<div class="form">
<?php $image=Yii::app()->baseUrl.('/images/ajax-loader.gif');?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>
	
	<div class="row">
		<?php echo CHtml::fileField('import_path',''); ?>
        <?php echo CHtml::submitButton('Load File', array('class'=>'btn btn-info')); ?>
        <?php echo CHtml::link('<span class="icon-edit icon-white"></span> Create data entry file', array('stocks/initialinventoryform'), array('class'=>'btn btn-inverse', 'style'=>'margin: 0.2em 0 0.5em 0; float:right;','title'=>'Create data entry file'));?>
    </div>
	
<?php $this->endWidget(); ?>


<hint>*some fields are hidden</hint>
<?php
//  if($importDataProvider != NULL)
// 	       print_r($equipment);

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'grid-import',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'htmlOptions'=>array('class'=>''),
	'dataProvider'=>$importDataProvider,
	'columns'=>array(
		//'ID',
		'stockCode',
		// 'supplyID',
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
		'description',
		// 'manufacturer',
		'unit',
		'quantity',
		// 'supplierID',
		// array(
		// 	'name'=>'supplierID',
		// 	// 'type'=>'raw',
		// 	// 'header'=>'supplier',
		// 	// 'value'=>'Suppliers::model()->getSuppliername('.$data->supplierID.')',
		// 	'value'=>$data->supplierID,
		// 	),
		// 'daterecieved',
		// 'dateopened',
		// 'expiry_date',
		// 'recieved_by',
		// 'threshold_limit',
		// 'location',
		// 'batch_number',
		'amount'
	),
)); 

?>

<div class="row">
<?php 
	$importLink = Chtml::link('<span class="icon-white icon-download-alt"></span> Import Requests', '', array(
			'title'=>'Import Requests',
			'class'=>'btn btn-success',
			"onclick"=>"if (!confirm('Import all Request?')){return}else{ confirmImport(); $('#dialogConfirmImport').dialog('open'); }",	
			));
			
	//echo $has_duplicate ? '<strong><font color="red">An equipment whos ID is already existing in the database</font></strong>' : $importLink;
	//print_r($has_duplicate);
	echo $importLink;
	echo CHtml::link('<span class="icon-bin icon-white"></span> Clear File', array('stocks/clearFile'), array('class'=>'btn btn-inverse', 'style'=>'margin: 0.2em 0 0.5em 0; float:right;','title'=>'Create data entry file'));
?>
</div>

<?php if($data != NULL)
	       var_dump($arr);
?>

</div>

<h1>Manage Stocks</h1>

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

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'stocks-grid',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		// 'id',
		'stockCode',
		array(
			'name'=>'supplyname',
			'value'=>'$data->supply->name'
			),
		'name',
		// 'description',
		// 'manufacturer',
		
		// 'unit',
		// 'quantity',
		'daterecieved', 
		// 'dateopened',
		// 'expiry_date',
		// 'recieved_by',
		// 'threshold_limit',
		// 'location',
		// 'batch_number',
		// 'supplierID',
		// 'amount',
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>


<?php



/*******************************************************
name: Bergel T. Cutara
org: DOST-IX, 991-1024
date created: Jan 6 2017
description: uploading data using excel // function and pop up dialog // function calls the controller for the data communication
********************************************************/


	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogConfirmImport',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Import Details',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>400,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>


<!-- ConfirmImport Dialog : End -->
<?php $image = CHtml::image(Yii::app()->request->baseUrl . '/images/ajax-loader.gif');?>
<script type="text/javascript">


function confirmImport()
{
	<?php 
			echo CHtml::ajax(array(
					'url'=>$this->createUrl('stocks/importdata'),
		            'type'=>'post',
		            'dataType'=>'json',
		            'success'=>"function(data)
		            {
		                if (data.status == 'failure')
		                {
		                    $('#dialogConfirmImport').html(data.div);
		                    // Here is the trick: on submit-> once again this function!
		                    $('#dialogConfirmImport form').submit(confirmImport);
		                }
		                else
		                {
							$('#dialogConfirmImport').html(data.div);
							$.fn.yiiGridView.update('stocks-grid');
							$.fn.yiiGridView.update('grid-import');
		                    setTimeout(\"$('#dialogConfirmImport').dialog('close') \",2500);
		                }
		            }",
					'beforeSend'=>'function(jqXHR, settings){
		                    $("#dialogConfirmImport").html(
								\'<div class="loader">'.$image.'<br\><br\>Import in progress.<br\> Please wait...</div>\'
							);
		            }',
					 'error'=>"function(request, status, error){
						 	$('#dialogConfirmImport').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
							}",
		            ))?>;
		    return false; 
}
</script>
