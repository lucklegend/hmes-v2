<?php
/* @var $this ReorderstocksController */
/* @var $model Reorderstocks */
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
		<?php echo $form->label($model,'supplyID'); ?>
		<?php echo $form->textField($model,'supplyID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'reorderdate'); ?>
		<?php echo $form->textField($model,'reorderdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'daterequested'); ?>
		<?php echo $form->textField($model,'daterequested'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'datereceived'); ?>
		<?php echo $form->textField($model,'datereceived'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'supplierID'); ?>
		<?php echo $form->textField($model,'supplierID'); ?>
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