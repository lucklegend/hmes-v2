<?php
/* @var $this QuotationController */
/* @var $model QuotationSample */
/* @var $form CActiveForm */
Yii::app()->clientscript->scriptMap['jquery.js'] = true;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = true;
?>

<div class="form">
<?php
	if (isset($modelSample->request_id)){ 
		//$id=$modelSample->request_id;
	}else{
		$id=$quotationId;
	}
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sample-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'id')?>
	<div class="row">
		<?php //echo $form->labelEx($model,'sampleCode'); ?>
		<?php //echo $form->textField($model,'sampleCode',array('size'=>20,'maxlength'=>20)); ?>
		<?php //echo $form->error($model,'sampleCode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sampleName'); ?>
		<?php echo $form->textField($model,'sampleName', array('style'=>'width: 255px;')); ?>
		<?php echo $form->error($model,'sampleName'); ?>
		
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'qty'); ?>
		<?php echo $form->textField($model,'qty',array('style'=>'width: 255px;')); ?>
		<?php echo $form->error($model,'qty'); ?>
		<br/>
	</div>

	<div class="row">
		<?php echo $form->hiddenField($model,'quotation_id', array('value'=>$model->isNewRecord ? $id : $model->id)); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'btn btn-info')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->