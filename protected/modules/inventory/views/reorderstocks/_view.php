<?php
/* @var $this ReorderstocksController */
/* @var $data Reorderstocks */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('supplyID')); ?>:</b>
	<?php echo CHtml::encode($data->supplyID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reorderdate')); ?>:</b>
	<?php echo CHtml::encode($data->reorderdate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('daterequested')); ?>:</b>
	<?php echo CHtml::encode($data->daterequested); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datereceived')); ?>:</b>
	<?php echo CHtml::encode($data->datereceived); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('supplierID')); ?>:</b>
	<?php echo CHtml::encode($data->supplierID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('remarks')); ?>:</b>
	<?php echo CHtml::encode($data->remarks); ?>
	<br />


</div>