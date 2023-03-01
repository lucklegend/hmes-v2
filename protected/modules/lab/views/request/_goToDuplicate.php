<?php
/* @var $this RequestController */
/* @var $model Request */
/* @var $form CActiveForm */
?>

<div>
    <div class="row">
        Successfully Duplicate to : 
        <?php
            echo Chtml::link('Go to: '$model->requestRefNum, '', array(
                'id' => 'cancel-button',
                'title' => 'Go to duplicate SR',
                'class' => 'btn btn-success',
                "onclick" => Yii::app()->Controller->createUrl("request/view",array("id"=>$model->id),
            ));
            echo CHtml::link($model->requestRefNum, Yii::app()->Controller->createUrl("request/view",array("id"=>$model->id)));
        ?>
    </div>
</div>