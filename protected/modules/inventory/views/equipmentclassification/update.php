<?php
/* @var $this EquipmentclassificationController */
/* @var $model Equipmentclassification */

$this->breadcrumbs=array(
	'Equipmentclassifications'=>array('index'),
	$model->name=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Equipmentclassification', 'url'=>array('index')),
	array('label'=>'Create Equipmentclassification', 'url'=>array('create')),
	array('label'=>'View Equipmentclassification', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Manage Equipmentclassification', 'url'=>array('admin')),
);
?>

<h1>Update Equipmentclassification <?php echo $model->ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>