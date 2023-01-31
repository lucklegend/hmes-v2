<?php
/* @var $this EquipmentController */
/* @var $model Equipment */

$this->breadcrumbs=array(
	'Equipments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Equipment', 'url'=>array('index')),
	array('label'=>'Manage Equipment', 'url'=>array('admin')),
);


$this->beginWidget('zii.widgets.CPortlet', array(
    'title'=>"<i class='icon-wrench'></i><strong>Import Data</strong  >",
		),array('class'=>'portletbold '));
?>
<div class="form">
<?php $image=Yii::app()->baseUrl.('/images/ajax-loader.gif');?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>
	
	<div class="row">
		<?php echo CHtml::fileField('import_path',''); ?>
        <?php echo CHtml::submitButton('Load File', array('class'=>'btn btn-info')); ?>
        <?php echo CHtml::link('<span class="icon-edit icon-white"></span> Create data entry file', array('equipment/createDataEntryFile'), array('class'=>'btn btn-inverse', 'style'=>'margin: 0.2em 0 0.5em 0; float:right;','title'=>'Create data entry file'));?>
    </div>
	
<?php $this->endWidget(); ?>

<?php
//  if($importDataProvider != NULL)
// 	       print_r($equipment);

	  $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'grid-import',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'htmlOptions'=>array('class'=>'grid-view padding0'),
	'dataProvider'=>$importDataProvider,
	'columns'=>array(
		//'ID',
		'equipmentID',
		'name',
		'brand',
		'model',
		'serialno',
		//'description',
		// array(
		// 	'name'=>'lab',
		// 	'value'=>'$data->labaccess->labName',
		// 	'filter'=> Lab::listLabName(),
		// ),
		// array(
		// 	'name'=>'classificationID',
		// 	'value'=>'$data->classification->name',
		// 	//'filter'=> Lab::listLabName(),
		//  ),
		// 'lab',
		// 'classificationID',
		// 'specification',
		// 'date_received',
		// 'received_by',
		// 'amount',
		// 'supplier',
		// 'status',
		// 'remarks',
	),
)); 

?>

</div>
<?php
$this->endWidget();
?>
<div class="row-fluid">
<?php 
	$importLink = Chtml::link('<span class="icon-white icon-download-alt"></span> Import Requests', '', array(
			'title'=>'Import Requests',
			'class'=>'btn btn-success',
			"onclick"=>"if (!confirm('Import all Request?')){return}else{ confirmImport(); $('#dialogConfirmImport').dialog('open'); }",	
			));
			
	echo $has_duplicate ? '<strong><font color="red">An equipment whos ID is already existing in the database</font></strong>' : $importLink;
	//print_r($has_duplicate);
	echo CHtml::link('<span class="icon-bin icon-white"></span> Clear File', array('equipment/clearFile'), array('class'=>'btn btn-inverse', 'style'=>'margin: 0.2em 0 0.5em 0; float:right;','title'=>'Create data entry file'));
?>
</div>


<hr />
<h1>Or Create Equipment</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
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
					'url'=>$this->createUrl('equipment/import'),
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
