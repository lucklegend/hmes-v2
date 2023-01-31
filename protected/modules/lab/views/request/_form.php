<?php
/* @var $this RequestController */
/* @var $model Request */
/* @var $form CActiveForm */
?>

<div class="form wide">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'request-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model);?>

	<div class="row">
		<?php echo $model->isNewRecord ? $form->labelEx($model,'labId') : ''; ?>
		<?php echo $model->isNewRecord ? $form->dropDownList($model,'labId', Initializecode::listLabName(), array('tabindex'=>"1", 'style'=>'width: 220px;','empty'=>'Please select a services...')) : $form->dropDownList($model,'labId', Lab::listdata(), array('tabindex'=>"1", 'style'=>'display: none;')); ?>
		<?php //echo $form->hiddenField($model,'labId',array('value'=>3)); ?>
		<?php echo $form->error($model,'labId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'requestDate'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'name'=>'Request[requestDate]',
						'value'=>$model->requestDate ? date('m/d/Y',strtotime($model->requestDate)) : date('m/d/Y'),
						// additional javascript options for the date picker plugin
						
						'options'=>array(
							'showAnim'=>'fold',
							),
						'htmlOptions'=>array(
							//'style'=>'height:8px; margin: 0px;'
							)
					));
				?>
		<?php echo $form->error($model,'requestDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'requestTime'); ?>
		<?php //echo $form->textField($model,'requestTime',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->textField($model,'requestTime', array(
				'value'=>$model->requestTime ? $model->requestTime : date('g:i A'), 'readonly'=>true,
			));?>
		<?php echo $form->error($model,'requestTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customerName'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'id'=>'Request_customerName',
			    'name'=>'Request[customerName]',
			    'source'=>$this->createUrl('request/searchCustomer'),
				'value'=>$model->customer->customerName,
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
							$("#head").val(ui.item.head);
							$("#completeAddress").val(ui.item.completeAddress);
							$("#customer tr td.email").html(ui.item.email);
							$("#customer tr td.fieldcust").html(ui.item.customerName);
							$("#customer tr td.fieldbusinesstype").html(ui.item.businesstype);
							$("#customer tr td.fieldcompaddr").html(ui.item.completeAddress);
							// $("#customer tr td.fieldfax").html(ui.item.fax);
							// $("#customer tr td.fieldhead").html(ui.item.head);
							$("#customer tr td.fieldemail").html(ui.item.email);
							$("#Request_addforcert").val(ui.item.head);
							$("#Request_contact_number").val(ui.item.tel);
							console.log("yehey");
						}'
			    ),
			    'htmlOptions'=>array(
			    	'placeholder'=>'Search company name or agency head here...',
			    	'style'=>'width:400px;',
					//'onClick' => 'value=""; document.getElementById("cust").value=""',
					//'onKeyDown'=>'document.getElementById("cust").value=value',
					//'onBlur'=>'value="enter company name or agency head to search..."',
					'onBlur'=>'	if(value==""){
							$("#Request_customerId").val("");
							$("#customer tr td.fieldcust").html("");
							//$("#customer tr td.fieldbusinesstype").html("");
							$("#customer tr td.fieldaddr").html("");
							// $("#customer tr td.fieldfax").html("");
							$("#customer tr td.fieldemail").html("");
							// $("#customer tr td.fieldhead").html("");
							$("#Request_addforcert").val("none");
							$("#Request_contact_number").val("none");
						}
					',
					//'class' => 'error'
			    ),
			   
			));
		?>
		<?php
			$imageCustomer = CHtml::image(Yii::app()->request->baseUrl . '/images/customer_add.png');
			$linkCustomer = Chtml::link($imageCustomer, '', array( 
				'style'=>'cursor:pointer;',
				'onClick'=>'js:{addCustomer(); $("#dialogCustomer").dialog("open");}',
			)); 
			echo '&nbsp;'.$linkCustomer;
		?>
		<?php echo $form->hiddenField($model,'customerId'); ?>
		<?php echo $form->error($model,'customerName'); ?>
	</div>
	<div id="customer" class="row" style='width: 800px; margin-left:150px;'>
	<?php
		if($model->isNewRecord){ ?>
        <table class="detail-view table table-striped table-condensed">
            <tr>
                <th>Agency / Customer</th>
                <td class="fieldcust"><?php echo $model->customer->customerName;?></td>
                <th>Business Type</th>
                <td class="fieldbusinesstype"><?php echo $model->customer->naturebusiness->nature;?></td>
            </tr>
            <!-- <tr>
                <th>Head of Agency</th>
                <td class="fieldhead"><?php echo $model->customer->head;?></td>
                <th>Fax</th>
                <td class="fieldfax"><?php echo $model->customer->fax;?></td>
            </tr> -->
            <tr>
                <th>Complete Address</th>
                <td class="fieldcompaddr"><?php echo $model->customer->completeAddress;?></td>
                <th>Email Address</th>
                <td class="fieldemail"><?php echo $model->customer->email;?></td>
            </tr>
        </table>
	<?php		
		}else{
			$this->widget('ext.widgets.DetailView4Col', array(
				'cssFile'=>false,
				'htmlOptions'=>array('class'=>'detail-view table table-striped table-condensed'),
				'data'=>$model,
				'attributes'=>array(
					'customer.customerName', 'customer.tel',
					// 'customer.head', 'customer.fax',
					'customer.completeAddress', 'customer.email',
				),
			));
		}
	?>    
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'addforcert'); ?>
		<?php echo $form->textField($model,'addforcert',array('size'=>50,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'addforcert'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'contact_number'); ?>
		<?php echo $form->textField($model,'contact_number',array('size'=>50)); ?>
		<?php echo $form->error($model,'contact_number'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'vat'); ?>
		<div class="compactRadioGroup">
		<?php 
			$model->isNewRecord ? $model->vat = 0: $model->vat = $model->vat ;
			echo $form->radioButtonList($model, 'vat', array(1=>'YES',0=>'NO'),
						array( 'separator' => "&nbsp;&nbsp;", 
							'labelOptions'=>array(
					            'active'=> 0),
					));
		?>  
		</div>
		<?php echo $form->error($model,'vat'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'paymentType'); ?>
		<div class="compactRadioGroup">
		<?php echo $form->radioButtonList($model, 'paymentType', array(1=>'PAID',2=>'FULLY SUBSIDIZED'),
						array( 'separator' => "  "));
		?>  
		</div>
		<?php echo $form->error($model,'paymentType'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'transmission'); ?>
		<?php //echo $form->dropDownList($model,'transmission', array('Pick-up'=>'Pick-up', 'Email'=>'Email', 'Courier'=>'Courier', 'Fax'=>'Fax'), array('Pick-up'=>'Pick Up')); ?>
		<?php echo $form->dropDownList($model,'transmission', Transmission::listData(), array('Pick-up'=>'Pick Up') ); ?>
		<?php echo $form->error($model,'transmission'); ?>
	</div>

	<!-- <div class="row"> -->
		<?php //echo $form->labelEx($model,'purpose'); ?>
		<?php //echo $form->dropDownList($model, 'purpose', Purpose::listData(), array('empty'=>'Select Here') ); ?>
		<?php //echo $form->error($model,'purpose'); ?>
	<!-- </div> -->

	<div class="row">
		<?php echo $form->labelEx($model,'discount'); ?>
		<?php echo $form->Dropdownlist($model,'discount', Discount::listData(), 
				array(
				// 'empty'=>'Select Option',
				// 'style'=>'width: 265px;',
				'id'=>'Request_discount',
				'class'=>'select_discount',
				'onchange'=>"$(document).on('change','#Request_discount.select_discount',function(){
	            	var selected = $('#Request_discount.select_discount option:selected').val();
	                if(selected == 8){
	                	console.log('ok');
	                	$('#labelDiscounted.label_discounted').css('display','block');
	                	$('#Request_discounted.input_discounted').css('display','block');
	                	$('#Request_discounted.input_discounted').removeAttr('autofocus');
	                }else{
	                	console.log('alright');
	                	$('#Request_discounted.input_discounted').css('display','none');
	                	$('#labelDiscounted.label_discounted').css('display','none');
	                	$('#Request_discounted.select_discount').val(selected);
	                	$('#Request_discounted.select_discount').attr('autofocus', true);
	                }
	            });",
			)); ?>
			<?php echo $form->error($model,'discount'); ?>
	</div>
	<?php 
		if($model->discount==8){
			$display= 'display:block;';
		}else{
			$display= 'display:none;';
		}
	?>
	<div class="row">
		<?php echo $form->labelEx($model,'discounted', array('style'=>$display, 'id'=>'labelDiscounted', 'class'=>'label_discounted')); ?>
		<?php echo $form->textField($model,'discounted', array('style'=>$display, 'class'=>'input_discounted', 'maxlength'=>1000)); ?>
		<?php echo $form->error($model,'discounted'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'reportDue'); ?>
		<?php 
			////disabling due date ////
			$createURL = Yii::app()->baseUrl."/lab/request/create";
			$url = Yii::app()->request->url;
			if($createURL==$url){
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$model,
					'attribute'=>'reportDue',
					'value'=>$model->requestDate ? date('m/d/Y',strtotime($model->requestDate)) : date('m/d/Y'),
					'options'=>array(
						'startDate'=>date('m/d/Y'),
						'minDate'=>'0',
						'showAnim'=>'fold',
						),
				));
				echo $form->error($model,'requestDate');
			}else{
				if(Yii::app()->getModule('lab')->isAdmin()){
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'model'=>$model,
							'attribute'=>'reportDue',
							'value'=>$model->requestDate ? date('m/d/Y',strtotime($model->requestDate)) : date('m/d/Y'),
							'options'=>array(
								'showAnim'=>'fold',
							),
						));
						echo $form->error($model,'requestDate'); 

				}else{
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'model'=>$model,
							'attribute'=>'reportDue',
							'value'=>$model->requestDate ? date('m/d/Y',strtotime($model->requestDate)) : date('m/d/Y'),
							'options'=>array(
								'showAnim'=>'fold',
								),
							'htmlOptions'=>array(
								//'style'=>'height:8px; margin: 0px;'
								//'disabled'=>'yes'
								)
						));
							echo $form->error($model,'requestDate'); 
				}

			}
			//// end of disabling due date ////
		?>
		<?php echo $form->error($model,'reportDue'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'conforme'); ?>
		<?php echo $form->textField($model,'conforme',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'conforme'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'conforme_designation'); ?>
		<?php echo $form->textField($model,'conforme_designation',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'conforme_designation'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'validated_by'); ?>
		<?php echo $form->textField($model,'validated_by',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'validated_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'receivedBy'); ?>
		<?php echo $form->textField($model,'receivedBy',array('size'=>50,'maxlength'=>50, 'value'=>Yii::app()->getModule('user')->user()->getFullName())); ?>
		<?php 
				/*echo $form->dropDownList($model,'receivedBy', 
					CHtml::listData(User::model()->findAll('id = :id AND role=:role ORDER BY fullname', 
					  array(':id'=>Yii::app()->user->id, ':role'=>'lab')), 'fullname', 'fullname'),
					array('options' => array(Yii::app()->user->id=>array('selected'=>true)))  
					);*/
				?>
		<?php echo $form->error($model,'receivedBy'); //echo Yii::app()->user->id; print_r(Yii::app()->getModule('user'));?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'btn btn-info')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
	
<!-- Customer Dialog : Start -->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogCustomer',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Add Customer',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>'93%',
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));

	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- Customer Dialog : End -->

<script type="text/javascript">
function addCustomer()
{
    <?php echo CHtml::ajax(array(
			'url'=>$this->createUrl('customer/create',array('id'=>$model->id)),
			'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogCustomer').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogCustomer form').submit(addCustomer);
                }
                else
                {
                    //$.fn.yiiGridView.update('sample-grid');
					$('#dialogCustomer').html(data.div);
                    setTimeout(\"$('#dialogCustomer').dialog('close') \",1000);
                }
 
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogCustomer").html(
						\'<div class="loader"><br\><br\>Generating form.<br\> Please wait...</div>\'
					);
             }',
			 'error'=>"function(request, status, error){
				 	$('#dialogCustomer').html(status+'('+error+')'+': '+ request.responseText );
					}",
			
            ))?>;
    return false; 
 
}
</script>
