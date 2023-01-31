<?php
/* @var $this RequestController */
/* @var $data Request */
Yii::app()->clientscript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
?>
<div class="view">
<?php echo "<font color='#666666'>Test Name: </font><br \><b style='font-size:1.25em;'>". $analysis->testName."</b>";?>
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
				'name'=>'Cancelled Date',
				'type'=>'raw',
				'value'=>'$data->cancelDate',
				'htmlOptions'=>array('style'=>'text-align:center;width:120px;'),
		 ),
         array(
				'name'=>'Cancelled By',
				'type'=>'raw',
				'value'=>'$data->CancelledBy->firstname." &nbsp;".$data->CancelledBy->lastname',
				'htmlOptions'=>array('style'=>'text-align:center;width:120px;'),
		 ),
            array(
             'header' =>'Status',
			 'type'=>'raw',
			 'htmlOptions'=>array('style'=>'text-align:center;width:120px;'),
			 'filter'=>false,
 			 'value'=> function($data){
				 $tagStatus=$data->status;
				 if ($tagStatus==3) {
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
