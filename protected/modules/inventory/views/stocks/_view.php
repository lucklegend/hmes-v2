<?php
/* @var $this StocksController */
/* @var $data Stocks */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stockCode')); ?>:</b>
	<?php echo CHtml::encode($data->stockCode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('supplyID')); ?>:</b>
	<?php echo CHtml::encode($data->supplyID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('manufacturer')); ?>:</b>
	<?php echo CHtml::encode($data->manufacturer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('unit')); ?>:</b>
	<?php echo CHtml::encode($data->unit); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('quantity')); ?>:</b>
	<?php echo CHtml::encode($data->quantity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('daterecieved')); ?>:</b>
	<?php echo CHtml::encode($data->daterecieved); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dateopened')); ?>:</b>
	<?php echo CHtml::encode($data->dateopened); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('expiry_date')); ?>:</b>
	<?php echo CHtml::encode($data->expiry_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('recieved_by')); ?>:</b>
	<?php echo CHtml::encode($data->recieved_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('threshold_limit')); ?>:</b>
	<?php echo CHtml::encode($data->threshold_limit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('location')); ?>:</b>
	<?php echo CHtml::encode($data->location); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('batch_number')); ?>:</b>
	<?php echo CHtml::encode($data->batch_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('supplierID')); ?>:</b>
	<?php echo CHtml::encode($data->supplierID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amount')); ?>:</b>
	<?php echo CHtml::encode($data->amount); ?>
	<br />

	*/ ?>

</div>