<?php
/* @var $this EquipmentusageController */
/* @var $model Equipmentusage */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'equipmentusage-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
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
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'startdate'); ?>
		<?php $form->widget('zii.widgets.jui.CJuiDatePicker', array(
						//'name'=>'Request[requestDate]',
						//'value'=>$model->startdate ? date('Y-m-d',strtotime($model->startdate)) : date('Y-m-d'),
						// additional javascript options for the date picker plugin
						'model'     => $model,
				        'attribute' => 'startdate',
				        //'language'=> 'ru',//default Yii::app()->language
				        //'mode'    => 'date',

						'options'=>array(
							'dateFormat'=>'yy-mm-dd',
							'showAnim'=>'fold',
							),
						'htmlOptions'=>array(
							//'style'=>'height:8px; margin: 0px;'
							)
					));
				?>
		<?php echo $form->error($model,'startdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'enddate'); ?>
		<?php $form ->widget('zii.widgets.jui.CJuiDatePicker', array(
						//'name'=>'Request[requestDate]',
						//'value'=>$model->enddate ? date('m/d/Y',strtotime($model->enddate)) : date('m/d/Y'),
						// additional javascript options for the date picker plugin
						'model'     => $model,
				        'attribute' => 'enddate',
				        //'language'=> 'ru',//default Yii::app()->language
				        //'mode'    => 'date',
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>'yy-mm-dd',
							),
						'htmlOptions'=>array(
							//'style'=>'height:8px; margin: 0px;'
							)
					));
				?>
		<?php echo $form->error($model,'enddate'); ?>
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