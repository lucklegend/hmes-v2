<?php 
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'equipment-grid',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		// 'ID',
		'equipmentID',
		'name',
		//'description',
		array(
			'name'=>'lab',
			'value'=>'$data->labaccess->labName',
			'filter'=> Lab::listLabName(),
		),
		// array(
		// 	'name'=>'classificationID',
		// 	'value'=>'$data->classification->name',
		// ),
		//'specification',
		'date_received',
		//'received_by',
		// 'amount',
		// 'supplier',
		// 'status',
		// 'lengthofuse',
		// 'remarks',
		// array(
		// 	'name'=>'tags',
		// 	'value'=>'',
		// 	'filter'=>false
		// 	),
		'tags',
		array(
                'header'=>'actions',
                'class'=>'CButtonColumn',
                'template' => '{tag}{test}{view}{update}{delete}',
                'htmlOptions' => array('style'=>'width:90px'),
                'buttons'=>array(

                        'tag' => array(

                                'label'=>'<i class="view icon icon-tag"></i>',
                                //'imageUrl'=>'images/icn/status.png',  // make sure you have an image
                                'url'=>'Yii::app()->createUrl("/inventory/equipment/group",array("id"=>$data->ID))',
                                'options' => array(
                                    'title'=>'Group',
	                               //  'class'=>'btn btn-warning btn-small',
	                               //  'confirm'=>'Are you sure you wish to cancel this request?',
                                    'onclick'=>'$("#mydialog").dialog("open");',
	                                'ajax' => array(
	                                    'type' => 'POST',
	                                   // 'data'=> "js:'id='+ id",
	                                    'url'=>'js:$(this).attr("href")',
	                                    'success' => 'js:function(data) {
	                                    	$("#mydialog").html(data);
	                                     }',
	                                     )

                                ),
                         ),
						 'test'=>array(
                                    'label'=>'AAA',
                                    'url'=>'Yii::app()->createUrl("/inventory/equipment/admin",array("id"=>$data->ID))',
                                    // 'click'=>'function(){var rowIdx=$(this).closest("tr").attr("sectionRowIndex");var rowId=$("#"+$(this).closest("div.grid-view").attr("id")+" > div.keys > span:eq("+rowIdx+")").text();alert(rowId);}', 
                                	'options' => array(
	                                    'title'=>'Group',
		                               //  'class'=>'btn btn-warning btn-small',
		                               //  'confirm'=>'Are you sure you wish to cancel this request?',
	                                    'onclick'=>'$("#mydialog").dialog("open");',
		                                'ajax' => array(
		                                    'type' => 'POST',
		                                   // 'data'=> "js:'id='+ id",
		                                    //'url'=>'$this->createUrl("/inventory/equipment/group",array("id"=>$data->ID))',
		                                    'dataType'=>'json',
		                                    'url'=>'js:$(this).attr("href")',
		                                    'success' => "js:function(data){
								                if (data.status == 'failure')
								                {
								                    $('#mydialog').html(data.div);
								                    // Here is the trick: on submit-> once again this function!
								                    $('#mydialog form').submit(grouptag);
								                }
								                
								            }",
								            'beforeSend'=>'function(jqXHR, settings){
								                    $("#mydialog").html(
								                        \'<div class="loader"><br\><br\>Generating form.<br\> Please wait...</div>\'
								                    );
								             }',
								             'error'=>"function(request, status, error){
								                    $('#mydialog').html(status+'('+error+')'+': '+ request.responseText );
								                    }",
		                                     )
	                                ),
                                ),
                       

                    ),            
            ),
		// array(
		// 	'class'=>'CButtonColumn',
		// ),
	),
)); 

?>