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
	'id'=>'remarks-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); 

?>

<?php echo $form->errorSummary($model); ?>
<?php echo $form->hiddenField($model,'id', array('value'=>$requestId ) );?>
<div class="row">
	<?php echo $form->labelEx($model,'remarks'); ?>
	<?php //echo $form->textArea($model,'remarks',array('rows'=>6, 'cols'=>50, 'style'=>'width: 255px;', 'value'=>$model->getRemarks() ));?>
	<?php echo $form->Dropdownlist($model,'remarks',array(
			$model->remarks=>$model->remarks,
			'None'=> 'None',
			'Provide a "Recommended Due Date" of one(1) year calibration interval as requested by the customer.'=>'Provide a "Recommended Due Date" of one(1) year calibration interval as requested by the customer.',
			'with 1 year calibration interval'=>'with 1 year calibration interval',
			'with 6 months calibration interval'=>'with 6 months calibration interval',
			'no recommended due date'=>'no recommended due date',
			'other'=>'Other',
		), array(
			'style'=>'width: 365px;',
			'class'=>'select_remarks',
			'onchange'=>"$(document).on('change','#Request_remarks.select_remarks',function(){
            	var selected = $('#Request_remarks.select_remarks option:selected').val();
                if(selected == 'other'){
                	console.log('ok');
                	$('#Request_remarks.input_remarks').css('display','block');
                	$('#Request_remarks.input_remarks').removeAttr('autofocus');
                }else{
                	console.log('alright');
                	$('#Request_remarks.input_remarks').css('display','none');
                	$('#Request_remarks.input_remarks').val(selected);
                	$('#Request_remarks.input_remarks').attr('autofocus', true);
                }
            });",
		)); ?>
		<?php echo $form->error($model,'remarks'); ?>
		<?php echo $form->textField($model,'remarks', array('style'=>'width: 350px;display:none;', 'class'=>'input_remarks', 'maxlength'=>1000)); ?>
</div>
<div class="row buttons">
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Save' : 'Update', array('id'=>'formsubmit','class'=>'btn btn-primary')); ?>
</div>
<?php $this->endWidget(); ?>
</div>