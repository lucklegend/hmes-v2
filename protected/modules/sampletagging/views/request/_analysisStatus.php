<?php
/* @var $this RequestController */
/* @var $data Request */
Yii::app()->clientscript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>

<div class="view">

<?php echo "<font color='#666666'>Test Name: </font><br \><b style='font-size:1.25em;'>". $analysis->testName."</b>";?>


<!--<span  class="badge badge-info"  style="float:right; min-width:80px; min-height:30px; line-height:30px;text-align:center;display:inline-block;font-weight:bold;">Ongoing</span>-->
<?php
	    $this->widget('zii.widgets.grid.CGridView', array(
    	'id'=>'analysis-grid',
	    'summaryText'=>false,
		'emptyText'=>'No analyses.',
		'htmlOptions'=>array('class'=>'grid-view padding0 paddingLeftRight10'),
		'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	    'rowHtmlOptionsExpression' => 'array("title" => "Select analysis", "style"=>"cursor:pointer")', 
        'dataProvider' => $tagging,
      	'columns'=>array(
		 	array(
				'name'=>'Start Date',
				'type'=>'raw',
				'value'=>'$data->startDate',
				'htmlOptions'=>array('style'=>'text-align:center;width:120px;'),
		 ),
         	array(
				'name'=>'End Date',
				'type'=>'raw',
				'value'=>'$data->endDate',
				'htmlOptions'=>array('style'=>'text-align:center;width:120px;'),
		 ),
		//  array(
		// 		'name'=>'Reason of cancellation',
		// 	//	'visible' => '$data->status == 3 ? true : false',
		// 		'type'=>'raw',
		// 		'value'=>'$data->reason',
		// 		'htmlOptions'=>array('style'=>'text-align:center;width:120px;'),
		// 	//	'visible' => '(($data->status===3) ? "true" : "false")',
		// 	//	'visible' => $data->status===3 ? true : false,
				
		//  ),
		//  	 array(
		// 		'name'=>'Cancel Date',
		// 	//	'visible' => '$data->status == 3 ? true : false',
		// 		'type'=>'raw',
		// 		'value'=>'$data->cancelDate',
		// 		'htmlOptions'=>array('style'=>'text-align:center;width:120px;'),
		// 	//	'visible' => (($data->status===3) ? true : false),
		//  ),
            array(
             'header' =>'Status',
			 'type'=>'raw',
			 'htmlOptions'=>array('style'=>'text-align:center;width:120px;'),
			 'filter'=>false,
 			 'value'=> function($data){
				 $tagStatus=$data->status;
				 if ($tagStatus==0){
 					return CHtml::label(
 						'<center><span class="badge badge-default">Pending</span></center>',
 						'javascript:void(0)'	
 				);} else if ($tagStatus==1) {
					 	return CHtml::label(
 						'<center><span class="badge badge-info">Ongoing</span></center>',
 						'javascript:void(0)'	
 				);}else if ($tagStatus==2) {
					 	return CHtml::label(
 						'<center><span class="badge badge-success">Completed</span></center>',
 						'javascript:void(0)'	
 				);}
				else if ($tagStatus==3) {
					 	return CHtml::label(
 						'<center><span class="badge badge-danger">Cancelled</span></center>',
 						'javascript:void(0)'	
 				);}
			
 				},	
		 ),

 
		),

		
    ));
   ?>
</div>
