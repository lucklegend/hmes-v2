<?php
/* @var $this EquipmentusageController */
/* @var $model Equipmentusage */

$this->breadcrumbs=array(
	'Equipmentusages'=>array('index'),
	$model->ID=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Equipmentusage', 'url'=>array('index')),
	array('label'=>'Create Equipmentusage', 'url'=>array('create')),
	array('label'=>'View Equipmentusage', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Manage Equipmentusage', 'url'=>array('admin')),
);
?>

<h1>Update Usage <?php echo $model->ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>