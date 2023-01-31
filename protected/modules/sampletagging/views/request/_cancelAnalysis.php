<?php
/* @var $this CancelledrequestController */
/* @var $model Cancelledrequest */
/* @var $form CActiveForm */
Yii::app()->clientscript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>
<div class="form">
<?php echo "<font color='#666666'>Sample Name: </font><br \><b style='font-size:1.25em;'>". $sample->sampleName."</b><br>";?>
<?php echo "<font color='#666666'>Test Name: </font><br \><b style='font-size:1.25em;'>". $analysis->testName."</b><br>";
?>
<br>
 <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'cancelanalysis-form',
    'enableClientValidation'=>true,
	'focus'=>array($model,'reason'),
    'clientOptions'=>array(
    'validateOnSubmit'=>true,
    ),
  ));  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'id')?>

	<div class="row">
		<?php echo CHtml::hiddenField('id',$analysisId);?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reason'); ?>
		<?php echo CHtml::textField('reason', '',
		array('width'=>100, 
			'maxlength'=>100)); ?>
		<?php echo $form->error($model,'reason'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'cancelDate','date',  array('disabled'=>'disabled')); ?>
		<?php echo CHtml::textField('cancelDate',date('Y-m-d'),  array('disabled'=>'disabled'));?>
				<?php ?>
		<?php echo $form->error($model,'cancelDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cancelledBy'); ?>
		<?php echo CHtml::textField('cancelledBy', Users::model()->findByPk(Yii::app()->getModule('user')->user()->id)->getFullname(), array('disabled'=>'disabled')); ?>

		<?php echo $form->error($model,'cancelledBy'); ?>
	</div>

	<div class="row buttons">	
	</div>
<?php

$cancelAnalysis =  Chtml::link(
	'<span class="btn btn-primary">Cancel</span>', 
	 '', 
	  array( 
			'style'=>'cursor:pointer;',
			'onClick'=>'cancelAnalysis();',			
			));
			echo ($generated > 1) ? $generated : $cancelAnalysis;  ?>
<script>
function cancelAnalysis()
{
    <?php echo CHtml::ajax(array(
            'url'=>array('sample/cancelForm'),
             'data'=> 'js: { "samplecode":$("#barcode").val(),
			  "reason":$("#reason").val(),
			  "cancelDate":$("#cancelDate").val(),
			  "cancelledBy":$("#cancelledBy").val(),
			  "id":$("#id").val()}',
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data){		
				if (data.status=='exit'){
					$('#barcode').val(data.sample_code);
					$('#scanbarcode').click();
					$('#dialogCancel').html(data.div);
					setTimeout(\"$('#dialogCancel').dialog('close') \",1000);
				}else{		
				}
			}",
			'error'=>"function(request, status, error){
				 alert(error);
			}"
	))
?>
}
</script>
<?php $this->endWidget(); ?>
</div><!-- form -->