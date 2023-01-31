<?php
/* @var $this TestreportController */
/* @var $data Testreport */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('request_id')); ?>:</b>
	<?php echo CHtml::encode($data->request_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lab_id')); ?>:</b>
	<?php echo CHtml::encode($data->lab_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reportNum')); ?>:</b>
	<?php echo CHtml::encode($data->reportNum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reportDate')); ?>:</b>
	<?php echo CHtml::encode($data->reportDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('releaseDate')); ?>:</b>
	<?php echo CHtml::encode($data->releaseDate); ?>
	<br />


</div>