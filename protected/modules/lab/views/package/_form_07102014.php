<?php
/* @var $this PackageController */
/* @var $model Package */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'package-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name', array('value'=>$model->isNewRecord ? 'Package ' : $model->name)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rate'); ?>
		<?php echo $form->textField($model,'rate', array('value'=>$model->rate ? $model->rate : '0.90', 'readonly'=>true)); ?>
		<?php echo $form->error($model,'rate'); ?>
	</div>	
	
	<div class="row">
		<?php echo $form->labelEx($model,'testcategory_id'); ?>
		<?php //echo $form->dropDownList($model,'testcategory_id', Testcategory::listData()); ?>
		<?php echo $form->dropDownList($model, 'testcategory_id',
						Testcategory::listData(), 
						array('ajax'=>array( 
										'type'=>'POST',
								 		'url'=>$this->createUrl('package/getSampletype'),
								 		'update'=>'#Package_sampletype_id',
								    ),
						'empty'=>''
								    ));?>
		<?php echo $form->error($model,'testcategory_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'sampletype_id'); ?>
		<?php echo $form->dropDownList($model, 'sampletype_id',
						//isset(Yii::app()->session['sampleType']) ? Yii::app()->session['sampleType'] : Sampletype::listData(),
						$model->isNewRecord ? array() : Sampletype::listData3($model->testcategory_id),
						array('ajax'=>array( 
									 	'type'=>'POST',
									 	'url'=>$this->createUrl('package/getTest'),
									 	'update'=>'#Package_tests',
							    	),
						'empty'=>''
							    	));?>
		<?php echo $form->error($model,'sampletype_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tests');?>
		<?php echo $form->listBox($model, 'tests', 
					$model->isNewRecord ? array() : Test::listData2($model->sampletype_id),
					 
					array('multiple' => 'multiple', 
						  'size' => '10', 
						  'options'=>$selected,
						  'ajax'=>array(
								'type' => 'post',
							        'url' => $this->createUrl('package/updateTestGrid'),
									'update'=> '#tests-grid',
							)
					)
					);
		?>
		<?php echo $form->error($model,'tests'); ?>
	</div>

	<div id="tests-grid">
		<?php $this->renderPartial('_tests', array('model'=>$model, 'gridDataProviderTest'=>$gridDataProviderTest), false, true); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>


	
<?php /*$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'test-grid',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'rowHtmlOptionsExpression' => 'array("title" => "Click to view project", "class"=>"link-hand")',
	'dataProvider'=>$testDataProvider,
	'selectableRows'=>1,
	'selectionChanged'=>'function(id){location.href = "'.$this->createUrl('test/view/id').'/"+$.fn.yiiGridView.getSelection(id);}',
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'testName',
		//'method',
		//'references',
		array(
				'header' =>'Test Name',
				'name'=>'testName',
				),
		array(
				'header' =>'Method',
				'name'=>'method',
				),
		array(
				'header' =>'References',
				'name'=>'references',
				),								
		array(
				'header' =>'Fee',
				'name'=>'fee',
				'value'=>'Yii::app()->format->formatNumber($data["fee"])',
				'htmlOptions' => array('style' => 'text-align: right;'),
				//'footer'=>Yii::app()->format->formatNumber($provider->itemCount===0 ? '' : 
						//$this->getTotal($barangays->totalCount(),'householdPerBarangay'))
				),
	),
));*/ ?>
</div><!-- form -->

<?php /*$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'test-grid',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'rowHtmlOptionsExpression' => 'array("title" => "Click to view project", "class"=>"link-hand")',
	'dataProvider'=>$modelTests->search(),
	//'filter'=>$modelTests,
	'columns'=>array(
		'id',
		'testName',
		'method',
		'references',
		array( 
				'name'=>'oldRate', 
				'value'=>'Yii::app()->format->formatNumber($data->fee)', 
				'htmlOptions' => array('style' => 'text-align: right;'),
		),
		array( 
				'name'=>'newRate', 
				'value'=>'Yii::app()->format->formatNumber($data->updatedFee->fee)',
				'htmlOptions' => array('style' => 'text-align: right;'),
		),
	),
));*/ ?>


<?php //print_r($testDataProvider);?>
