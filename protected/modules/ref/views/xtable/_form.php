<?php
/* @var $this ResultController */
/* @var $model Result */
/* @var $form CActiveForm */
//Yii::app()->clientscript->scriptMap['jquery.js'] = false;
//Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'xtable-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'firstName'); ?>
		<?php echo $form->textField($model,'firstName',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'firstName'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'lastName'); ?>
		<?php echo $form->textField($model,'lastName',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'lastName'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'mi'); ?>
		<?php echo $form->textField($model,'mi',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'mi'); ?>
	</div>
	
	<div class="row buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		<?php echo CHtml::submitButton('Create'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->