<?php
/* @var $this TestreportController */
/* @var $model Testreport */

$this->breadcrumbs=array(
	'Testreports'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List Testreport', 'url'=>array('index')),
	array('label'=>'Create Testreport', 'url'=>array('create')),
	//array('label'=>'Update Testreport', 'url'=>array('update', 'id'=>$model->id)),
	//array('label'=>'Delete Testreport', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Testreport', 'url'=>array('admin')),
);
?>

<h3>View Testreport : <?php echo $model->reportNum; ?></h3>

<?php //$this->widget('ext.widgets.DetailView4Col', array(
	//$this->widget('ext.bootstrap.widgets.TbDetailView', array(
	$this->widget('ext.bootstrap.widgets.TbDetailView', array(
	//'cssFile'=>false,
	//'htmlOptions'=>array('class'=>'detail-view table table-striped table-condensed'),
	'data'=>$model,
	'attributes'=>array(
		'reportNum',
		'reportDate',
        'status',
		'releaseDate',
		
	),
)); ?>

<?php
	$this->beginWidget('zii.widgets.CPortlet', array(
		'title'=>"<b>Sample(s)</b>",
	));

?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    	'id'=>'paymentitems-grid',
	    'summaryText'=>false,
		'htmlOptions'=>array('class'=>'grid-view padding0 paddingLeftRight10'),
		'itemsCssClass'=>'table table-striped table-bordered table-condensed',
		'rowHtmlOptionsExpression' => 'array("class"=>"link-hand")', 
        //It is important to note, that if the Table/Model Primary Key is not "id" you have to
        //define the CArrayDataProvider's "keyField" with the Primary Key label of that Table/Model.
        'dataProvider' => $testReportSampleDataProvider,
        'columns' => array(
    		array(
				'header'=>'Sample Code',
				'name'=>'sample.sampleCode',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 250px; padding-left: 10px; text-align: left;'),
			),
            array(
				'header'=>'Sample Name',
				'name'=>'sample.sampleName',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 250px; padding-left: 10px; text-align: left;'),
			),
            array(
				'header'=>'Status',
				'name'=>'',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 250px; padding-left: 10px; text-align: left;'),
			),
            array(
			//'class'=>'CButtonColumn',
			'header'=>'Actions',
			'class'=>'bootstrap.widgets.TbButtonColumn',
						'deleteConfirmation'=>"js:'Do you really want to delete sample: '+$.trim($(this).parent().parent().children(':nth-child(1)').text())+'?'",
						'template'=>'{delete}',
						'buttons'=>array
						(
							'delete' => array(
								'label'=>'Delete Sample',
								'url'=>'Yii::app()->createUrl("lab/testreportSample/delete/id/$data->id")',
								),
						),
            'visible'=>Yii::app()->getModule('lab')->isLabAdmin()
			)
        ),
    ));
    ?>
    
	<div class="alert alert-info" style="margin: 0 10px 10px 10px">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Note: Only Lab Managers are permitted to modify Test Reports</strong><br />
    </div>
<?php $this->endWidget(); //End Portlet ?>

<?php 
    echo CHtml::link('<span class="icon-white icon-print"></span> Print', $this->createUrl('testreport/printExcel',array('id'=>$model->id)), array('id'=>'print-testreport', 'class'=>'btn btn-info'));

Yii::app()->clientScript->registerScript('testreport-script','
    $("#print-testreport").click(function(){
        alert("This feature is not yet implemented! Coming soon....");
        return false;
    });
');
?>