<?php
/* @var $this StocksController */
/* @var $model Stocks */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stocks-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'stockCode'); ?>
		<?php echo $form->textField($model,'stockCode',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'stockCode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'supplyID'); ?>
		<?php echo $form->dropDownList($model,'supplyID',
			 CHtml::listData(Supplies::model()->findAll(),'id','name')); ?>
		<?php echo $form->error($model,'supplyID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'manufacturer'); ?>
		<?php echo $form->textField($model,'manufacturer',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'manufacturer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'unit'); ?>
		<?php echo $form->textField($model,'unit',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'unit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'quantity'); ?>
		<?php echo $form->textField($model,'quantity'); ?>
		<?php echo $form->error($model,'quantity'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'daterecieved'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$model,
						'attribute'=>'daterecieved',
						'value'=>date('yy-mm-dd'),
						
						// additional javascript options for the date picker plugin
						
						'options' => array(
							'showAnim' => 'fold',
							'dateFormat'=>'yy-mm-dd',
							),
						
					));?>
		<?php echo $form->error($model,'daterecieved'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dateopened'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$model,
						'attribute'=>'dateopened',
						'value'=>date('yy-mm-dd'),
						
						// additional javascript options for the date picker plugin
						
						'options' => array(
							'showAnim' => 'fold',
							'dateFormat'=>'yy-mm-dd',
							),
						
					));?>
		<?php echo $form->error($model,'dateopened'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'expiry_date'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$model,
						'attribute'=>'expiry_date',
						'value'=>date('yy-mm-dd'),
						
						// additional javascript options for the date picker plugin
						
						'options' => array(
							'showAnim' => 'fold',
							'dateFormat'=>'yy-mm-dd',
							),
						
					));?>
		<?php echo $form->error($model,'expiry_date'); ?>
	</div>

	<!-- <div class="row">
		<?php echo $form->labelEx($model,'recieved_by'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$model,
						'attribute'=>'recieved_by',
						'value'=>date('yy-mm-dd'),
						
						// additional javascript options for the date picker plugin
						
						'options' => array(
							'showAnim' => 'fold',
							'dateFormat'=>'yy-mm-dd',
							),
						
					));?>
		<?php echo $form->error($model,'recieved_by'); ?>
	</div> -->

	<div class="row">
		<?php echo $form->labelEx($model,'threshold_limit'); ?>
		<?php echo $form->textField($model,'threshold_limit'); ?>
		<?php echo $form->error($model,'threshold_limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'location'); ?>
		<?php echo $form->textField($model,'location',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'location'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'batch_number'); ?>
		<?php echo $form->textField($model,'batch_number',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'batch_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'supplierID'); ?>
		<?php echo $form->dropDownList($model,'supplierID',
			 CHtml::listData(Suppliers::model()->findAll(),'id','name')); ?>
		<?php echo $form->error($model,'supplierID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model,'amount'); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'totalAmount'); ?>
		<?php echo $form->textField($model,'totalAmount'); ?>
		<?php echo $form->error($model,'totalAmount'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->