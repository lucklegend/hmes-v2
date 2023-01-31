<?php
/* @var $this EquipmentstatusController */
/* @var $model Equipmentstatus */

$this->breadcrumbs=array(
	'Equipmentstatuses'=>array('index'),
	$model->name=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Equipmentstatus', 'url'=>array('index')),
	array('label'=>'Create Equipmentstatus', 'url'=>array('create')),
	array('label'=>'View Equipmentstatus', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Manage Equipmentstatus', 'url'=>array('admin')),
);
?>

<h1>Update Equipmentstatus <?php echo $model->ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>