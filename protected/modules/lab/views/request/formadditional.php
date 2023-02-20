<?php
/* @var $this RequestController */
/* @var $model Request */
/* @var $form CActiveForm */
Yii::app()->clientscript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

?>

<div class="form">
	<?php
		$requestId = $_REQUEST['id'];
		
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'additional-form',
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// There is a call to performAjaxValidation() commented in generated controller code.
			// See class documentation of CActiveForm for details on this.
			'enableAjaxValidation'=>false,
		)); 

		echo $form->errorSummary($model);
		echo $form->hiddenField($model,'id', array('value'=>$requestId ) );
	?>
	<div class="row">
		<?php 
			echo $form->labelEx($model,'additional'); 	
			echo $form->textField($model,'additional',array('size'=>60,'maxlength'=>150, 'value'=>$model->getAdditional() ));
		?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Save' : 'Update', array('id'=>'formsubmit','class'=>'btn btn-primary')); ?>
	</div>
	<?php 
		$this->endWidget(); 
	?>
</div>