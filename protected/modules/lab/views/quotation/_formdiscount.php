<?php
/* @var $this RequestController */
/* @var $model Request */
/* @var $form CActiveForm */
Yii::app()->clientscript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>

<div class="form">
<?php
	if (isset($modelSample->request_id)){ 
		//$id=$modelSample->request_id;
	}else{
		$id=$sampleId;
	}
	$requestId = $_REQUEST['id']
	
?>
<?php


	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'discount-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); 

?>

<?php echo $form->errorSummary($model); ?>
<?php echo $form->hiddenField($model,'id', array('value'=>$quotationtId ) );?>
<div class="row">
	<?php echo $form->labelEx($model,'discount'); ?>
	<?php echo $form->dropDownList($model,'discount', Discount::listData(), array()); ?>
</div>
<div class="row buttons">
	<?php echo CHtml::submitButton('Save', array('id'=>'formsubmit','class'=>'btn btn-primary')); ?>
</div>
<?php $this->endWidget(); ?>
</div>