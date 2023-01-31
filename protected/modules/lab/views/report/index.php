<h1>Reports</h1>
<div class="container" style="padding:15px;">
	<div class="row">
		<div class="span3">
		  	<?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl . '/images/icons_report/accomplishment-report.png', 'Accomplishment Report', array('class'=>'report-icons')), $this->createUrl('/lab/accomplishments'));?>
		</div>

		<div class="span3">
		  	<?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl . '/images/icons_report/customer-statistics.png', 'Customers Statistics', array('class'=>'report-icons')),$this->createUrl('/lab/statistic/customer'));?>
		</div>

		<div class="span3">
		  	<?php echo CHtml::link(
					CHtml::image(Yii::app()->baseUrl . '/images/icons_report/samples.png', 'Daily Sample Summary', array('class'=>'report-icons')),
					$this->createUrl('/lab/statistic/samples')
					)?>
		</div>
		<div class="span3">
		  	<?php echo CHtml::link(
					CHtml::image(Yii::app()->baseUrl . '/images/icons_report/lab.png', 'Daily Sample Summary', array('class'=>'report-icons')),
					$this->createUrl('/lab/statistic/lab')
					)?>
		</div>
	</div>

	<div class="row">
		<div class="span3">
		  	<?php echo CHtml::link(
					CHtml::image(Yii::app()->baseUrl . '/images/icons_report/test-report.png', 'Test Report Statistics', array('class'=>'report-icons')
		            ),
					$this->createUrl('/lab/statistic/reports')
			)?>
		</div>

		<div class="span3">
		  	<?php echo CHtml::link(
					CHtml::image(Yii::app()->baseUrl . '/images/icons_report/test-report.png', 'Monthly Summary', array('class'=>'report-icons')
		                        ),
					$this->createUrl('/lab/accomplishments/monthlySummary')
					)?>
		</div>

		<div class="span3">
		  	<?php echo CHtml::link(
					CHtml::image(Yii::app()->baseUrl . '/images/icons_report/test-sample.png', 'Monthly Summary', array('class'=>'report-icons')
		                        ),
					$this->createUrl('/lab/accomplishments/testmonthlysummary')
					)?>
		</div>
	</div>

</div>
