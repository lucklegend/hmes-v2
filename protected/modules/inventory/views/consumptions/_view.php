<?php
/* @var $this ConsumptionsController */
/* @var $data Consumptions */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stockID')); ?>:</b>
	<?php echo CHtml::encode($data->stockID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('balance')); ?>:</b>
	<?php echo CHtml::encode($data->balance); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amountused')); ?>:</b>
	<?php echo CHtml::encode($data->amountused); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dateconsumed')); ?>:</b>
	<?php echo CHtml::encode($data->dateconsumed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('withdrawnby')); ?>:</b>
	<?php echo CHtml::encode($data->withdrawnby); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('remarks')); ?>:</b>
	<?php echo CHtml::encode($data->remarks); ?>
	<br />


</div>