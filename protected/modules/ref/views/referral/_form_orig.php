<?php
/* @var $this ReferralController */
/* @var $model Referral */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'referral-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'referralCode'); ?>
		<?php echo $form->textField($model,'referralCode',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'referralCode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'referralDate'); ?>
		<?php echo $form->textField($model,'referralDate'); ?>
		<?php echo $form->error($model,'referralDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'receivingAgencyId'); ?>
		<?php echo $form->textField($model,'receivingAgencyId'); ?>
		<?php echo $form->error($model,'receivingAgencyId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'acceptingAgencyId'); ?>
		<?php echo $form->textField($model,'acceptingAgencyId'); ?>
		<?php echo $form->error($model,'acceptingAgencyId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lab_id'); ?>
		<?php echo $form->textField($model,'lab_id'); ?>
		<?php echo $form->error($model,'lab_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customer_id'); ?>
		<?php echo $form->textField($model,'customer_id'); ?>
		<?php echo $form->error($model,'customer_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'paymentType_id'); ?>
		<?php echo $form->textField($model,'paymentType_id'); ?>
		<?php echo $form->error($model,'paymentType_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'discount_id'); ?>
		<?php echo $form->textField($model,'discount_id'); ?>
		<?php echo $form->error($model,'discount_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reportDue'); ?>
		<?php echo $form->textField($model,'reportDue'); ?>
		<?php echo $form->error($model,'reportDue'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'conforme'); ?>
		<?php echo $form->textField($model,'conforme',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'conforme'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'receivedBy'); ?>
		<?php echo $form->textField($model,'receivedBy',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'receivedBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cancelled'); ?>
		<?php echo $form->textField($model,'cancelled'); ?>
		<?php echo $form->error($model,'cancelled'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_time'); ?>
		<?php echo $form->textField($model,'create_time'); ?>
		<?php echo $form->error($model,'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_time'); ?>
		<?php echo $form->textField($model,'update_time'); ?>
		<?php echo $form->error($model,'update_time'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->