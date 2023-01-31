<?php
/* @var $this ConsumptionsController */
/* @var $model Consumptions */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stockID'); ?>
		<?php echo $form->textField($model,'stockID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'balance'); ?>
		<?php echo $form->textField($model,'balance'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'amountused'); ?>
		<?php echo $form->textField($model,'amountused'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dateconsumed'); ?>
		<?php echo $form->textField($model,'dateconsumed'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'withdrawnby'); ?>
		<?php echo $form->textField($model,'withdrawnby'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'remarks'); ?>
		<?php echo $form->textField($model,'remarks',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->