<?php
/* @var $this EquipmentcalibrationController */
/* @var $model Equipmentcalibration */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'equipmentcalibration-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	 'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    ),
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'equipmentID'); ?>
		<?php echo $form->dropDownList($model,'equipmentID',
			 CHtml::listData(Equipment::model()->findAll(),'equipmentID','name'),array('options' => array($item=>array('selected'=>true)))); ?>
		<?php echo $form->error($model,'equipmentID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$model,
						'attribute'=>'date',
						'value'=>date('yy-mm-dd'),
						
						// additional javascript options for the date picker plugin
						
						'options' => array(
							'showAnim' => 'fold',
							'dateFormat'=>'yy-mm-dd',
							),
						
					));?>
		<?php echo $form->error($model,'date'); ?>
	</div>
<?php if($model->isNewRecord!='1'){ ?>

	<div class="row">
		<?php echo $form->labelEx($model,'isdone'); ?>
		<?php echo $form->dropDownList($model,'isdone',
			 array('0'=>'Not Yet','1'=>'Done')); ?>
		<?php echo $form->error($model,'isdone'); ?>
	</div>

	<div class="row">
	        <?php echo $form->labelEx($model,'certificate'); ?>
	        <?php echo CHtml::activeFileField($model, 'certificate'); ?>  // by this we can upload pdf
	        <?php echo $form->error($model,'certificate'); ?>
	</div>
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
<?php } ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

