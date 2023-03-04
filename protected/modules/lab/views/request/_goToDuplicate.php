<?php
/* @var $this RequestController */
/* @var $model Request */
/* @var $form CActiveForm */
Yii::app()->clientscript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>

<div>
    <div class="row">
        Successfully Duplicate to : 
        <?php
            echo CHtml::link('Go to: '.$model->requestRefNum, '', array(
                'id' => 'duplicate-button',
                'title' => 'Go to duplicate SR',
                'class' => 'btn btn-success',
                "onclick" => Yii::app()->Controller->createUrl("request/view",array("id"=>$model->id)),
            ));
            echo CHtml::link($model->requestRefNum, Yii::app()->Controller->createUrl("request/view",array("id"=>$model->id)));
        ?>
    </div>
</div>