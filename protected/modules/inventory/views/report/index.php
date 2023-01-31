<?php echo "<h3>REPORTS</h3>"?>
<?php
// echo CHtml::image(Yii::app()->baseUrl . '/images/icons_report/inventory.png', 'inventory Report', array('class'=>'image-icon-large'));

echo CHtml::link(CHtml::image(Yii::app()->baseUrl . '/images/icons_report/inventory.png', 'inventory Report', array('class'=>'image-icon-large')),
			$this->createUrl('stock')
)
?>