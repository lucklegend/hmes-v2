<?php
/* @var $this ConsumptionsController */
/* @var $model Consumptions */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'consumptions-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'stockID'); ?>
		<?php echo $form->textField($model,'stockID'); ?>
		<?php echo $form->error($model,'stockID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'balance'); ?>
		<?php echo $form->textField($model,'balance'); ?>
		<?php echo $form->error($model,'balance'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'amountused'); ?>
		<?php echo $form->textField($model,'amountused'); ?>
		<?php echo $form->error($model,'amountused'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dateconsumed'); ?>
		<?php echo $form->textField($model,'dateconsumed'); ?>
		<?php echo $form->error($model,'dateconsumed'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'withdrawnby'); ?>
		<?php echo $form->textField($model,'withdrawnby'); ?>
		<?php echo $form->error($model,'withdrawnby'); ?>
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