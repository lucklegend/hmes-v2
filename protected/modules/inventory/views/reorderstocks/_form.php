<?php
/* @var $this ReorderstocksController */
/* @var $model Reorderstocks */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'reorderstocks-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'supplyID'); ?>
		<?php echo $form->textField($model,'supplyID'); ?>
		<?php echo $form->error($model,'supplyID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reorderdate'); ?>
		<?php echo $form->textField($model,'reorderdate'); ?>
		<?php echo $form->error($model,'reorderdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'daterequested'); ?>
		<?php echo $form->textField($model,'daterequested'); ?>
		<?php echo $form->error($model,'daterequested'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'datereceived'); ?>
		<?php echo $form->textField($model,'datereceived'); ?>
		<?php echo $form->error($model,'datereceived'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'supplierID'); ?>
		<?php echo $form->textField($model,'supplierID'); ?>
		<?php echo $form->error($model,'supplierID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remarks'); ?>
		<?php echo $form->textField($model,'remarks',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'remarks'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->