<font style="font-weight:bold;font-size:0.9em">Select Samples : </font>
<?php
		$this->widget('zii.widgets.grid.CGridView', array(
		   'id'=>'samples-grid', // the containerID for getChecked
		   'emptyText'=>'No sample.',
		   'summaryText'=>false,
		   'htmlOptions'=>array('class'=>'grid-view padding0', 'style'=>'width:1080px;'),
		   'dataProvider'=>$gridDataProvider,
		   'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
		   'rowHtmlOptionsExpression' => 'array("class"=>"link-hand")',
		   //'selectableRows'=>2,
		   'columns'=>array(
               
		       array(
					'id'=>'sampleIds', // the columnID for getChecked
					'class'=>'CCheckBoxColumn',
					'selectableRows'=>2,
					'disabled'=>'$data[testReportSample] ? TRUE : FALSE',
                    'htmlOptions' => array('style' => 'width: 20px; text-align: center;')
		       ),
		       //'customer.customerName',
		       array(
		       	'header'=>'Sample Code',
		       	'name'=>'sampleCode',
		       	'htmlOptions' => array('style' => 'width: 90x; text-align: center;')
		       ),
		       array(
		       	'header'=>'Sample Name',
		       	'name'=>'sampleName',
		       	'htmlOptions' => array('style' => 'width: 150px; text-align: center;'),
		       ),
               array(
		       	'header'=>'Description',
		       	'name'=>'description',
		       	'htmlOptions' => array('style' => 'width: 500px; text-align: left; padding-left: 10px;'),
		       ),
               array(
		       	'header'=>'Remarks',
		       	'name'=>'remarks',
		       	'htmlOptions' => array('style' => 'width: 350px; text-align: left; padding-left: 10px;'),
		       ),
		   ),
		));
		?>