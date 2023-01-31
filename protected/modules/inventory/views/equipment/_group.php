<?php
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
echo "<h3>Equipment : $equipment->equipmentID $equipment->name</h3>";


if($equipment->tags!=""){
	echo "<h4>Tag with: '".$equipment->tags."'</h4>";
	
}
?>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'group-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    ));  ?>
     <p class="note">Fields with <span class="required">*</span> are required.</p>
     <?php echo $form->errorSummary($model); ?>

   	 <div class="row">
        <?php echo $form->labelEx($model,'EquipmentID'); ?>
      <?php
            $this->widget('ext.select2.ESelect2',array(
                   
                   'model'=>$model,
                   'attribute' =>'EquipmentID',
                   'data'=>CHtml::listData(Equipment::model()->findAll(array('condition'=>'Tags = ""')), 'ID', 'name'),
                   
                   'options'  => array(
                       'placeholder'=>'Select Equipment',
                       'width'=>'200px',
                   ),
                   'htmlOptions'=>array(
                       'multiple'=>'multiple',
                   ),
            ));
        ?>
        <?php echo $form->error($model,'EquipmentID'); ?>
      </div>

       <div class="row">
        <?php echo $form->labelEx($model,'Tag'); ?>
        <?php echo $form->textField($model,'Tag',array('style'=>'width:150px;')); ?>
        <?php echo $form->error($model,'Tag'); ?>
        <div class="row-fluid">
        <div class="hint">search for tags</div>
          <?php
            $this->widget('ext.select2.ESelect2',array(
                   
                   'name'=>'custom',
                   'data'=>CHtml::listData(Equipment::model()->findAll(), 'tags', 'name','tags'),
                   
                   'options'  => array(
                       'placeholder'=>'Select Tag',
                       'width'=>'200px',
                   ),
                   'events' =>array('change'=>'js:function(e) 
                    { 
                       
                        $("#GroupForm_Tag").val( e.val);
                    }'),   
                     
            ));
        ?>
        </div>
      </div>

       <div class="row buttons">
      <?php
      echo CHtml::submitButton(
          'Group Now',
          array(
              'class'=>'btn btn-success',
          )
      );
      ?>
      </div>
       <?php $this->endWidget(); ?>
   </div><!-- form -->





