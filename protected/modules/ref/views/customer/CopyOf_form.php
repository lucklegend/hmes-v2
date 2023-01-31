<?php
/* @var $this CustomerController */
/* @var $model Customer */
/* @var $form CActiveForm */
if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientscript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
}
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
			<?php echo $form->labelEx($model,'customerName'); ?>
			<?php 
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'id'=>'Request_customerName',
				    'name'=>'Request[customerName]',
				    'source'=>$this->createUrl('request/searchCustomer'),
					//'value'=>$model->customer->customerName,
				    'options'=>array(
				        //'delay'=>300,
				        'minLength'=>1,
				        'showAnim'=>'fold',
						'select'=>'js:function(event, ui) 
							{ 
								$("#Request_customerId").val(ui.item.id); // assign customerId to hidden field
								$("#customerName").val(ui.item.customerName);
								$("#address").val(ui.item.address);
								$("#tel").val(ui.item.tel);
								$("#fax").val(ui.item.fax);
								$("#customer tr td.fieldcust").html(ui.item.customerName);
								$("#customer tr td.fieldtel").html(ui.item.tel)
								$("#customer tr td.fieldaddr").html(ui.item.address)
								$("#customer tr td.fieldfax").html(ui.item.fax)
								
							}'
				    ),
				    'htmlOptions'=>array(
				    	'placeholder'=>'Search from local ULIMS',
				    	//'style'=>'width:400px;',
						//'onClick' => 'value=""; document.getElementById("cust").value=""',
						//'onKeyDown'=>'document.getElementById("cust").value=value',
						//'onBlur'=>'value="enter company name or agency head to search..."',
						'onBlur'=>'	if(value==""){
								$("#Request_customerId").val("");
								$("#customer tr td.fieldcust").html("");
								$("#customer tr td.fieldtel").html("");
								$("#customer tr td.fieldaddr").html("");
								$("#customer tr td.fieldfax").html("");
							}
						',
						//'class' => 'error'
				    ),
				   
				));
			?>
			<?php echo $form->error($model,'customerName'); ?>
		</div>
		
	<div id="left" style="float: left; margin-right: 50px;">
	<fieldset class="legend-border">
    	<legend class="legend-border">Legend/Status</legend>
    
	</fieldset>
		<h4><i>Agency/Personal Info</i></h4>
		<div class="row">
			<?php echo $form->labelEx($model,'customerName'); ?>
			<?php echo $form->textField($model,'customerName',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'customerName'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'agencyHead'); ?>
			<?php echo $form->textField($model,'agencyHead',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'agencyHead'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'type_id'); ?>
			<?php echo $form->dropDownList($model,'type_id',	Customertype::listData());?>
			<?php echo $form->error($model,'type_id'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'nature_id'); ?>
			<?php echo $form->dropDownList($model,'nature_id', Businessnature::listData());?>
			<?php echo $form->error($model,'nature_id'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'industry_id'); ?>
			<?php echo $form->dropDownList($model,'industry_id', Industrytype::listData());?>
			<?php echo $form->error($model,'industry_id'); ?>
		</div>
	</div>
	
	<div id="mid" style="float: left; margin-right: 50px;">
		<h4><i>Address</i></h4>
		<div class="row">
			<?php echo $form->labelEx($model,'region_id'); ?>
			<?php $region = Region::listData();?>
			<?php echo $form->dropDownList($model, 'region_id', 
							$region, 
							array(
								'ajax'=>array( 
									'type'=>'POST',
									'url'=>$this->createUrl('customer/getProvince'),
									'dataType'=>'json',
									'data'=>'js:$(this).serialize()',
									'update'=>'#Customer_province_id',
									'success'=>'js:function(data){
										if(data){		
											$("#Customer_province_id").html(data.dropDownProvince);
											$("#Customer_municipalitycity_id").html(data.dropDownMunicipalityCity);
											$("#Customer_barangay_id").html(data.dropDownBarangay);
										}
									}'
								),
								'empty'=>''
							));?>
			<?php echo $form->error($model,'region_id'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'province_id'); ?>
			<?php 
				$provinces = Province::listDataByRegion($model->region_id);
				echo $form->dropDownList($model, 'province_id',
							$provinces,
							array(
								'ajax'=>array( 
									'type'=>'POST',
							 		'url'=>$this->createUrl('customer/getMunicipalityCity'),
							 		'dataType'=>'json',
									'data'=>'js:$(this).serialize()',
									'success'=>'js:function(data){
										if(data){		
											$("#Customer_municipalityCity_id").html(data.dropDownMunicipalityCity);
											$("#Customer_barangay_id").html(data.dropDownBarangay);
										}
									}'
								),
								'empty'=>''
							)	
						);
			?>
			<?php echo $form->error($model,'province_id'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'municipalityCity_id'); ?>
			<?php
			//echo $model->province_id;
			$provinceId=MunicipalityCity::model()->findByPk($model->municipalityCity_id)->provinceId;
			if($model->province_id)
				$provinceId = $model->province_id;
				$municipality = MunicipalityCity::listDataByProvince($provinceId);
						//$municipality=array(''=>'');
						echo $form->dropDownList($model, 'municipalityCity_id', 
									$municipality,
									array('ajax'=>array( 
										'type'=>'POST',
									 	'url'=>$this->createUrl('customer/getBarangay'),
									 	'dataType'=>'json',
										'data'=>'js:$(this).serialize()',
										'success'=>'js:function(data){
											if(data){
												$("#Customer_barangay_id").html(data.dropDownBarangay);
											}
										}
										'
									),
										'empty'=>''
						));?>
			<?php echo $form->error($model,'municipalityCity_id'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'barangay_id'); ?>
			<?php //echo $form->textField($model,'barangay_id'); ?>
			<?php 
				$municipalityCityId = $model->municipalityCity_id;
				$barangay = Barangay::listDataByMunicipalityCity($municipalityCityId);
				echo $form->dropDownList($model, barangay_id, $barangay);
			?>
			<?php echo $form->error($model,'barangay_id'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'houseNumber'); ?>
			<?php echo $form->textField($model,'houseNumber',array('size'=>60,'maxlength'=>200)); ?>
			<?php echo $form->error($model,'houseNumber'); ?>
		</div>
	</div>
	
	<div id="right" style="float: left;">
		<h4><i>Contact Info</i></h4>
		<div class="row">
			<?php echo $form->labelEx($model,'tel'); ?>
			<?php echo $form->textField($model,'tel',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'tel'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'fax'); ?>
			<?php echo $form->textField($model,'fax',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'fax'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->labelEx($model,'email'); ?>
			<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>
		
		<div class="row buttons">
			<?php echo CHtml::submitButton(!isset($model->id) ? 'Create' : 'Save', 
							array('class'=>'btn btn-info')); 
			?>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->