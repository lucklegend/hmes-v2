<?php
/* @var $this RequestController */
/* @var $model Request */
/* @var $form CActiveForm */

?>

<div class="form">
    <?php
        $requestId = $_REQUEST['id'];

        $form = $this->beginWidget('CActiveForm', array(
            'id'=>'duplicate-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation'=>false,
        )); 

        echo $form->errorSummary($model); 
        echo $form->hiddenField($model, 'id', array('value' => $requestId)); 
    ?>
    <div class="row">
		<?php
            echo $form->hiddenField($model,'id', array('value'=>$requestId, 'readonly'=>true)); 
            echo $form->hiddenField($model,'labId', array('value'=>$model->labId, 'readonly'=>true)); 
		?>
	</div>
    
    <div class="span4" style="margin-left:0px!important">
        <div class="row">
            <?php 
                echo $form->labelEx($model,'requestRefNum', array('label'=>'Duplicate the Service Request'));
                echo $form->textField($model,'requestRefNum',array('size'=>50, 'maxlength'=>50, 'value'=>$request->requestRefNum, 'readonly'=>true));
                echo $form->error($model,'requestRefNum'); 
            ?>
        </div>
        <div class="row">
            <?php 
                echo $form->labelEx($model,'requestRefNum', array('label'=>'To this new Service Request'));
                echo $form->textField($model,'requestRefNum', array('size'=>50, 'maxlength'=>50, 'value'=>$data['lastest_gen_request'], 'readonly'=>true));
                echo $form->error($model,'requestRefNum'); 
            ?>
        </div>
        <div class="row">
            <?php 
                echo $form->labelEx($model,'requestDate');

                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name'=>'Request[requestDate]',
                    'value'=>$model->requestDate ? date('m/d/Y',strtotime($model->requestDate)) : date('m/d/Y'),
                    // additional javascript options for the date picker plugin
                    
                    'options'=>array(
                        'startDate'=>date('m/d/Y'),
                        'showAnim'=>'fold',
                        ),
                    'htmlOptions'=>array(
                        //'style'=>'height:8px; margin: 0px;'
                        )
                ));
                
                echo $form->error($model,'requestDate'); 
            ?>
        </div>
        <div class="row">
            <?php 
                echo $form->labelEx($model,'requestTime');
                echo $form->textField($model,'requestTime', array(
                    'value'=>date('g:i A'), 'readonly'=>true,
                ));
                echo $form->error($model,'requestTime'); 
            ?>
        </div>

        <div class="row">
            <?php 
                echo $form->labelEx($model,'addforcert');   
                echo $form->textField($model,'addforcert',array('size'=>50,'maxlength'=>100)); 
                echo $form->error($model,'addforcert'); 
            ?>
        </div>
        <div class="row">
            <?php 
                echo $form->labelEx($model,'contact_number');
                echo $form->textField($model,'contact_number',array('size'=>50));   
                echo $form->error($model,'contact_number'); 
            ?>
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

    </div>

    <div class="span4" style="margin-left:0px!important">
        
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
            <?php 
                echo $form->labelEx($model,'reportDue');  
                if(Yii::app()->getModule('lab')->isAdmin()){
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model'=>$model,
                        'name'=>'Request[reportDue]',
                        'value'=>date('m/d/Y',strtotime($model->reportDue)),
                        'options'=>array(
                            'startDate'=>date('m/d/Y'),
                            'minDate'=>'0',
                            'showAnim'=>'fold',
                            ),
                    ));
                    echo $form->error($model,'reportDue'); 

                } else {
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model'=>$model,
                        'name'=>'Request[reportDue]',
                        'value'=>date('m/d/Y',strtotime($model->reportDue)),
                        'options'=>array(
                            'showAnim'=>'fold',
                            ),
                        'htmlOptions'=>array(
                            //'style'=>'height:8px; margin: 0px;'
                            //'disabled'=>'yes'
                            )
                    ));
                    echo $form->error($model,'reportDue'); 
                }
            
                echo $form->error($model,'reportDue'); 
            ?>
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
    </div>
	<div class="span4" style="margin-left:0px!important"></div>
    <div class="span4" style="margin-left:0px!important">
        <div class="row buttons">
            <?php 
                echo CHtml::submitButton('Duplicate', array('id'=>'formsubmit','class'=>'btn btn-primary')); 
            ?>
        </div>
    </div>
    

    <?php $this->endWidget(); ?>
</div>