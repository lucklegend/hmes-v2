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
            echo $requestId;
			echo $form->hiddenField($model,'id', array('value'=>$requestId, 'readonly'=>true)); 
		?>
	</div>
    <div class="row">
		<?php 
            echo $form->labelEx($model,'requestRefNum');
            echo $form->textField($model,'requestRefNum',array('size'=>50,'maxlength'=>50, 'value'=>$request->requestRefNum, 'readonly'=>true));
            echo $form->error($model,'requestRefNum'); 
        ?>
    </div>
    <div class="row buttons">
    <?php echo CHtml::submitButton('Duplicate', array('id'=>'formsubmit','class'=>'btn btn-primary')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>