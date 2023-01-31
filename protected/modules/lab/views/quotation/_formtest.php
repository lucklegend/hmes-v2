<?php
/* @var $this QuotationController */
/* @var $model QuotationSample */
/* @var $form CActiveForm */
Yii::app()->clientscript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>

<div class="form">
<?php
	if (isset($modelSample->request_id)){ 
		//$id=$modelSample->request_id;
	}else{
		$id=$quotationId;
	}
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'analysis-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'id')?>
	<div class="row">
		<?php echo $form->labelEx($model,'sample_id'); ?>
		<?php 
			$codes = CHtml::listdata(
					QuotationSample::model()->findAll(array('condition'=>'quotation_id = :quotation_id', 'params'=>array(':quotation_id'=>$id))),'id','sampleName');
		?>			
		<?php echo $form->checkBoxList($model,'sample_id',
					$codes 
					,array(
						'template'=>'{input} {label}',
						'separator'=>'',
						'style' =>"",
						'classname'=>'samples',
						'checkAll' => 'All',
						//'checkAllLast'=>true
						)
			); ?>
		<?php echo $form->error($model,'sample_id'); ?>
	</div>
	<div class="row">
		<table>
			<tr>
				<td><?php echo $form->labelEx($model,'lab_id');?></td>
				<td><?php echo $form->dropDownList($model,'lab_id',Initializecode::listLabName(),
							array('ajax'=>array( 
									 	'type'=>'POST',
									 	'url'=>$this->createUrl('quotation/getanalysis'),
									 	'update'=>'#QuotationTest_testName',
										),
								 'empty'=>'Please select lab...'
						    	));
					?>
					<?php echo $form->error($model,'lab_id'); ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($model,'testName'); ?></td>
				<td>
					<?php echo $form->dropDownList($model,'testName',Test::listData3($model->lab_id),
							array('ajax'=>array( 
									 	'type'=>'POST',
										'dataType'=>'JSON',
									 	'url'=>$this->createUrl('quotation/getanalysisdetails'),
									 	'success'=>'js:function(data){
									 			  $("#QuotationTest_method").val(data.method);
									 			  $("#QuotationTest_references").val(data.references);
									 			  $("#QuotationTest_fee").val(data.fee);
									 			  }',
										),
								 'empty'=>'Select test/calibration',
						    	));
					?>
					<?php echo $form->error($model,'testName'); ?>

				</td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($model,'method'); ?></td>
				<td>
					<?php echo $form->textField($model,'method',array('size'=>60,'maxlength'=>150, 'readonly'=>true));?>
					<?php echo $form->error($model,'method'); ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($model,'references'); ?></td>
				<td>
					<?php echo $form->textField($model,'references',array('size'=>60,'maxlength'=>150, 'readonly'=>true)); ?>
					<?php echo $form->error($model,'references'); ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($model,'fee'); ?></td>
				<td>
					<?php echo $form->textField($model,'fee',array('size'=>60,'readonly'=>true)); ?>
					<?php echo $form->error($model,'fee'); ?>
				</td>
			</tr>
		</table>
				
	</div>
	
	<div class="row">
		<?php echo $form->hiddenField($model,'quotation_id', array('value'=>$model->isNewRecord ? $id : $model->id)); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'btn btn-info')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->