<?php
/* @var $this SampleController */
/* @var $model Sample */
/* @var $form CActiveForm */
Yii::app()->clientscript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>

<div class="form">
<?php
	if (isset($modelSample->request_id)){ 
		//$id=$modelSample->request_id;
	}else{
		$id=$requestId;
	}
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sample-form',
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
		<?php 
		$this->widget('ext.select2.ESelect2',array(
          //'model'=>$model,
          'name'=>'sampleName',
          'data'=>Samplename::listData(),
          'options'=>array(
                'width'=>'268px',
                'allowClear'=>true,
				'minimumInputLength'=>2,
                'placeholder'=>'Search sample template here...',
            ),
          'events' =>array('change'=>'js:function(e) 
                    { 
                       data = $(this).select2("data");
                       $("#Sample_sampleName").val(data.text);
                       $("#Sample_description").val($(this).select2("val"));
                    }
                    '
            ),
        ));
		?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'sampleName'); ?>
		<?php echo $form->textField($model,'sampleName', array('style'=>'width: 255px;')); ?>
		<?php echo $form->error($model,'sampleName'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Quantity', 'quantity'); ?>
		<?php echo CHtml::textField('quantity', 1, array('style'=>'width: 125px;')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'samplingDate'); ?>
		<?php 
		if($model->samplingDate){
			if($model->samplingDate == "0000-00-00" || $model->samplingDate == "1970-01-01" || $model->samplingDate == NULL){
				$samplingDate = "";
			}else{
				$samplingDate = $model->samplingDate;
			}
		}else{
			$samplingDate = "";
		}
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'name'=>'Sample[samplingDate]',
						'value'=>$samplingDate,
						// additional javascript options for the date picker plugin
						'options'=>array(
							'showAnim'=>'fold',
							),
						'htmlOptions'=>array(
							'style'=>'width: 255px;'
							)
					));
				?>
		<?php echo $form->error($model,'samplingDate'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'remarks'); ?>
		<?php echo $form->Dropdownlist($model,'remarks',array(
			$model->remarks=>$model->remarks,
			'with 1 year calibration interval'=>'with 1 year calibration interval',
			'with 6 months calibration interval'=>'with 6 months calibration interval',
			'no recommended due date'=>'no recommended due date',
			'sample bottle provided by the customer'=>'sample bottle provided by the customer',
			'Drinking Water'=>'Drinking Water',
			'Non-Drinking Water'=>'Non-Drinking Water',
			'other'=>'Other',
		), array(
			'style'=>'width: 265px;',
			'class'=>'select_remarks',
			'onchange'=>"$(document).on('change','#Sample_remarks.select_remarks',function(){
            	var selected = $('#Sample_remarks.select_remarks option:selected').val();
                if(selected == 'other'){
                	console.log('ok');
                	$('#Sample_remarks.input_remarks').css('display','block');
                	$('#Sample_remarks.input_remarks').removeAttr('autofocus');
                }else{
                	console.log('alright');
                	$('#Sample_remarks.input_remarks').css('display','none');
                	$('#Sample_remarks.input_remarks').val(selected);
                	$('#Sample_remarks.input_remarks').attr('autofocus', true);
                }
            });",
		)); ?>
		<?php echo $form->error($model,'remarks'); ?>
		<?php echo $form->textField($model,'remarks', array('style'=>'width: 255px;display:none;', 'class'=>'input_remarks', 'maxlength'=>1000)); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50, 'style'=>'width: 255px;')); ?>
		<?php echo $form->error($model,'description'); ?>
		<br/>
		<?php echo CHtml::checkBox('saveAsTemplate', false,//$form->checkBox($model,'discount',
			array(
					//'template'=>'{input} {label}',
					//'separator'=>'',
					//'style' =>"",
					'classname'=>'saveTemplate'
					)
			); ?>
		<?php echo "Save Template";?>
	</div>

	<div class="row">
		<?php echo $form->hiddenField($model,'requestId',array('value'=>$model->isNewRecord ? $request->requestRefNum : $model->requestId,'size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->hiddenField($model,'request_id', array('value'=>$model->isNewRecord ? $id : $model->request_id)); ?>
	</div>

	<div class="row">
		<?php echo $form->hiddenField($model,'sampleMonth',array('value'=>$model->isNewRecord ? date('m', strtotime($request->requestDate)) : $model->sampleMonth)); ?>
	</div>

	<div class="row">
		<?php echo $form->hiddenField($model,'sampleYear',array('value'=>$model->isNewRecord ? date('Y', strtotime($request->requestDate)) : $model->sampleYear)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'btn btn-info')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->