<?php
/* @var $this EquipmentController */
/* @var $model Equipment */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'equipment-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<div class="row span6">
		<div class="row">
			<?php echo $form->labelEx($model,'equipmentID'); ?>
			<?php echo $form->textField($model,'equipmentID',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'equipmentID'); ?>
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
			<?php echo $form->labelEx($model,'lab'); ?>
			<?php echo $form->dropDownList($model,'lab',
				 CHtml::listData(Lab::model()->findAll(),'id','labName')); ?>
			<?php echo $form->error($model,'lab'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'specification'); ?>
			<?php echo $form->textArea($model,'specification',array('size'=>60,'maxlength'=>500)); ?>
			<?php echo $form->error($model,'specification'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'brand'); ?>
			<?php echo $form->textArea($model,'brand',array('size'=>60,'maxlength'=>500)); ?>
			<?php echo $form->error($model,'brand'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'model'); ?>
			<?php echo $form->textArea($model,'model',array('size'=>60,'maxlength'=>500)); ?>
			<?php echo $form->error($model,'model'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'serialno'); ?>
			<?php echo $form->textArea($model,'serialno',array('size'=>60,'maxlength'=>500)); ?>
			<?php echo $form->error($model,'serialno'); ?>
		</div>

	</div>
	<div class="row span6">
		<div class="row">
			<?php echo $form->labelEx($model,'tags'); ?>
			<?php echo $form->textField($model,'tags',array('size'=>60,'maxlength'=>500)); ?>
			<?php echo $form->error($model,'tags'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'classificationID'); ?>
			<?php echo $form->dropDownList($model,'classificationID',
				 CHtml::listData(Equipmentclassification::model()->findAll(),'ID','name')); ?>
			<?php echo $form->error($model,'classificationID'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'date_received'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'model'=>$model,
							'attribute'=>'date_received',
							'value'=>date('m/d/Y'),
							// additional javascript options for the date picker plugin
							
							'options'=>array(
								'showAnim'=>'slide',
								),
							'htmlOptions'=>array(
								//'style'=>'height:8px; margin: 0px;'
								)
						));?>
			<?php echo $form->error($model,'date_received'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'received_by'); ?>
			<?php echo $form->textField($model,'received_by',array('size'=>50,'maxlength'=>50, 'value'=>Yii::app()->user->id, 'readonly'=>true)); ?>
			<?php 
					// echo $form->dropDownList($model,'received_by', 
					// 	CHtml::listData(User::model()->findAll(),'user_id','user_id'
					// 	//array('options' => array(Yii::app()->user->id=>array('selected'=>true)))  
					// 	));
					?>
			<?php echo $form->error($model,'received_by'); //echo Yii::app()->user->id; print_r(Yii::app()->getModule('user'));?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'amount'); ?>
			<?php echo $form->textField($model,'amount'); ?>
			<?php echo $form->error($model,'amount'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'supplier'); ?>
			<?php echo $form->textField($model,'supplier'); ?>
			<?php echo $form->error($model,'supplier'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'status'); ?>
			<?php echo $form->dropDownList($model,'status',
				 CHtml::listData(Equipmentstatus::model()->findAll(),'ID','name')); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'lengthofuse'); ?>
			<?php echo $form->textField($model,'lengthofuse'); ?>
			<?php echo $form->error($model,'lengthofuse'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'remarks'); ?>
			<?php echo $form->textField($model,'remarks',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'remarks'); ?>
		</div>
	</div>
	

	<div class="row-fluid buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'btn btn-info')); ; ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->