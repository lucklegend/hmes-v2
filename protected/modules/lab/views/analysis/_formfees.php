<?php
/* @var $this AnalysisController */
/* @var $model Analysis */
/* @var $form CActiveForm */
Yii::app()->clientscript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
// Yii::app()->getClientScript()->registerScript('myscript','$("#Analysis_references").keyup(function() {
//   alert(\'Hello world !\');
// });');

?>

<div class="form">
<?php
	if (isset($modelSample->request_id)){ 
		//$id=$modelSample->request_id;
	}else{
		$id=$sampleId;
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
		<?php //echo $form->labelEx($model,'requestId'); ?>
		<?php echo $form->hiddenField($model,'requestId',array('value'=>$model->isNewRecord ? $request->requestRefNum : $model->requestId, 'size'=>50,'maxlength'=>50, 'readonly'=>true)); ?>
		<?php //echo $form->error($model,'requestId'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'sample_id'); ?>
		<?php //echo $form->textField($model,'sample_id'); ?>
		<?php /*echo $form->dropDownList($model, 'sample_id', CHtml::listdata(
					$model->isNewRecord ?
					Sample::model()->findAll(array('condition'=>'request_id = :request_id', 'params'=>array(':request_id'=>$request->id))) :
					Sample::model()->findAll(array('condition'=>'id = :sample_id', 'params'=>array(':sample_id'=>$model->sample_id)))
					,'id','sampleName'));*/?>
		<?php 
			$codes = CHtml::listdata($model->isNewRecord ?
					Sample::model()->findAll(array('condition'=>'request_id = :request_id', 'params'=>array(':request_id'=>$request->id))) :
					Sample::model()->findAll(array('condition'=>'id = :sample_id', 'params'=>array(':sample_id'=>$model->sample_id)))
					,'id','sampleName');
		?>			
		<?php echo $form->checkBoxList($model,'sample_id',
					$codes 
					,array(
						'template'=>'{input} {label}',
						'separator'=>'',
						'style' =>"",
						'classname'=>'samples',
						'checkAll' => 'All',
						)
			); ?>
		<?php echo $form->error($model,'sample_id'); ?>
	</div>
	
	<table>
		<tr>
			<td width="100"><?php echo chtml::label('Item'); ?></td>
			<td><?php echo CHtml::dropDownList('item', '' ,
						Fee::listData(), 
						array('ajax'=>array( 
									 	'type'=>'POST',
										'dataType'=>'JSON',
									 	'url'=>$this->createUrl('analysis/getFeedetails'),
									 	'success'=>'js:function(data){
									 				  switch(data.code){
									 				  	
									 				  }

										 			  $("#Analysis_fee").val(data.unitCost);
													  $("#Analysis_reference").val(data.reference);
													  $("#Analysis_total").val($("#Analysis_fee").val()*$("#Analysis_quantity").val());
									 			  }',
							    	),
							'empty'=>''
								    ));
						?>
					<?php echo $form->hiddenField($model,'reference',array('value'=>''));?>
					</td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($model,'quantity');?></td>
			<td>
				<?php echo $form->textField($model,'quantity', 
					array(	'value'=>1, 
							'onKeyup'=>'$("#Analysis_total").val($("#Analysis_fee").val()*$("#Analysis_quantity").val());'
							));
				?>
				<?php echo $form->error($model,'quantity'); ?>
			</td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($model,'unitPrice');?></td>
			<td>
				<?php echo $form->textField($model,'fee', array('readonly'=>true, 'value'=>0.00));?>
				<?php echo $form->error($model,'unitPrice'); ?>
			</td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($model,'total');?></td>
			<td>
				<?php echo $form->textField($model,'total',array('size'=>60,'readonly'=>true)); ?>
				<?php echo $form->error($model,'total'); ?>
			</td>
		</tr>
	</table>

	<div class="row">
		<?php //echo $form->labelEx($model,'analysisMonth'); ?>
		<?php echo $form->hiddenField($model,'analysisMonth', array('value'=>$model->isNewRecord ? date('m', strtotime($request->requestDate)) : $model->analysisMonth, 'readonly'=>true)); ?>
		<?php //echo $form->error($model,'analysisMonth'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'analysisYear'); ?>
		<?php echo $form->hiddenField($model,'analysisYear', array('value'=>$model->isNewRecord ? date('Y', strtotime($request->requestDate)) : $model->analysisYear, 'readonly'=>true)); ?>
		<?php //echo $form->error($model,'analysisYear'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
