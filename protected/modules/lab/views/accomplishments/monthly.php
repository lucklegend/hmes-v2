<?php //print_r($summarys);?>
<?php echo CHtml::dropDownList('year', $select,	
				   CHtml::listData($this->getYear(), 'index', 'year'));?>
<div id="submenu">
<?php	  
	  $this->widget('zii.widgets.CMenu',array(
				'items'=>array(
					array('label'=>'CHEMLAB', 'url'=>array('/accomplishments/consolidated/1')),
					array('label'=>'MICROLAB', 'url'=>array('/accomplishments/consolidated/2')),
					array('label'=>'METROLAB', 'url'=>array('/accomplishments/consolidated/3')),
				),
			));
?>
</div>
<br/><br/>
<?php ?>
