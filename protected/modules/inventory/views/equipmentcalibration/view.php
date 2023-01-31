<?php
/* @var $this EquipmentcalibrationController */
/* @var $model Equipmentcalibration */

$this->breadcrumbs=array(
	'Equipmentcalibrations'=>array('index'),
	$model->ID,
);

$this->menu=array(
	array('label'=>'List Equipmentcalibration', 'url'=>array('index')),
	array('label'=>'Create Equipmentcalibration', 'url'=>array('create')),
	array('label'=>'Update Equipmentcalibration', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Equipmentcalibration', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Equipmentcalibration', 'url'=>array('admin')),
);
?>

<h1>View Calibration #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'user_id',
		'equipmentID',
		'date',
		array(
			'name'=>'isdone',
			'value'=>$model->getstatus(),
			),
		'certificate',
	),
)); 
?>


<div id="pdftarget" style="height:900px">

<?php
	
		$basePath=Yii::app()->baseUrl.'/equipment_uploads/pdf/';
		//echo $basePath.$model->certificate;
		$this->widget('ext.pdfJs.QPdfJs',array(
			'id'=>'pdfviewer',
			'options'=>array(
			 	'print'=>true,
			 	),
			'url'=>$basePath.$model->certificate,
			//'url'=>Yii::getPathOfAlias('webroot').'/upload/mytest.pdf',
		));


	
	
?>

</div> 
