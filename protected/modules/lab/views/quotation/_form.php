<?php
/* @var $this QuotationController */
/* @var $model Quotation */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'quotation-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'requestDate'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'Quotation[requestDate]',
				'value'=>$model->requestDate ? date('m/d/Y',strtotime($model->requestDate)) : date('m/d/Y'),
				// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					),
				'htmlOptions'=>array(
					//'style'=>'height:8px; margin: 0px;'
					)
			));
		?>
		<?php echo $form->error($model,'requestDate'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'company'); ?>
		<?php echo $form->textField($model,'company',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'company'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textArea($model,'address',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'contact_person'); ?>
		<?php echo $form->textField($model,'contact_person',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'contact_person'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'designation'); ?>
		<?php echo $form->textField($model,'designation',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'designation'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'contact_number'); ?>
		<?php echo $form->textField($model,'contact_number',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'contact_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'noted_by'); ?>
		<?php echo $form->textField($model,'noted_by',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'noted_by'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'noted_byPos'); ?>
		<?php echo $form->textField($model,'noted_byPos',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'noted_byPos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remarks'); ?>
		<?php echo $form->textArea($model,'remarks',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'remarks'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'created_by'); ?>
		<?php echo $form->textField($model,'created_by',array('size'=>60,'maxlength'=>255, 'value'=>Yii::app()->getModule('user')->user()->getFullName())); ?>
		<?php echo $form->error($model,'created_by'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'created_byPos'); ?>
		<?php echo $form->dropDownList($model,'created_byPos',array(
							'Customer Relations Officer'=> 'Customer Relations Officer',
							'Laboratory Analyst'=> 'Laboratory Analyst',
							'Calibration Officer'=> 'Calibration Officer',
							),
							array()); ?>
		<?php echo $form->error($model,'created_byPos'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'btn btn-info')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->