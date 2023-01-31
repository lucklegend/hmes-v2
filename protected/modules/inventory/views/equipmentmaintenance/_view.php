<?php
/* @var $this EquipmentmaintenanceController */
/* @var $data Equipmentmaintenance */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ID), array('view', 'id'=>$data->ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('equipmentID')); ?>:</b>
	<?php echo CHtml::encode($data->equipmentID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isdone')); ?>:</b>
	<?php echo CHtml::encode($data->isdone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('maintenancedata')); ?>:</b>
	<?php echo CHtml::encode($data->maintenancedata); ?>
	<br />


</div>