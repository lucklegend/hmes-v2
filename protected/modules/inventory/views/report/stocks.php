<h3>List of Equipment via Date Received</h3>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'scholars-by-province-form',
	'enableAjaxValidation'=>false,
)); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'DateStart'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$model,
						'attribute'=>'DateStart',
						'value'=>date('yy-mm-dd'),
						
						// additional javascript options for the date picker plugin
						
						'options' => array(
							'showAnim' => 'fold',
							'dateFormat'=>'yy-mm-dd',
							),
						
					));?>
		<?php echo $form->error($model,'DateStart'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'DateEnd'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$model,
						'attribute'=>'DateEnd',
						'value'=>date('yy-mm-dd'),
						
						// additional javascript options for the date picker plugin
						
						'options' => array(
							'showAnim' => 'fold',
							'dateFormat'=>'yy-mm-dd',
							),
						
					));?>
		<?php echo $form->error($model,'DateEnd'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('View',array('class'=> 'btn btn-danger')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>