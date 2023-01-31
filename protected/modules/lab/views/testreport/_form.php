<?php
/* @var $this TestreportController */
/* @var $model Testreport */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'testreport-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'request_id'); ?>
		<?php $this->widget('ext.select2.ESelect2',array(
					'model'=>$model,
					'attribute'=>'request_id',
					'data'=>$request,
					'options'=>array(
						'width'=>'400px',
						'placeholder'=>'Select Request Reference Number...',
					),					
					'htmlOptions'=>array(
						'ajax'=>array( 
									'type'=>'POST',
							 		'url'=>$this->createUrl('testreport/searchSamples'),
									'update'=>'#samples',
							    ),
					  ),
				));
			?>
		<?php //echo $form->hiddenField($model,'request_id'); ?>
		<?php echo $form->error($model,'request_id'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'reportDate'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'name'=>'Testreport[reportDate]',
						'value'=>$model->reportDate ? date('m/d/Y',strtotime($model->reportDate)) : date('m/d/Y'),
						// additional javascript options for the date picker plugin
						
						'options'=>array(
							'showAnim'=>'fold',
							),
						'htmlOptions'=>array(
							//'style'=>'height:8px; margin: 0px;'
							)
					));
				?>
		<?php echo $form->error($model,'reportDate'); ?>
	</div>

    <div class="row buttons" id="samples">    	
		<?php $this->renderPartial('_samples', array('gridDataProvider'=>$gridDataProvider)); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('id'=>'createTestReport', 'class'=>'btn btn-info')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('testreport-script','
    $("#createTestReport").click(function(){
        var checked=$("#samples-grid").yiiGridView("getChecked","sampleIds");
        var count=checked.length;
        if(count<1){alert("Please select sample(s).");return false;}
    });
');
?>