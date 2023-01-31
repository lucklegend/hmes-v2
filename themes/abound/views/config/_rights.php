<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'rights-grid',
        'summaryText'=>false,
		'htmlOptions'=>array('class'=>'grid-view padding0'),
        'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
        'rowHtmlOptionsExpression' => 'array("title" => "Click to update", "class"=>"link-hand")',
        'dataProvider'=>$rightsDataProvider,
        'columns'=>array(
            //'id',
            array(
                'name'=>'item',
                'header'=>'Table',
                //'value'=>,
                'htmlOptions' => array('style' => 'width: 300px; text-align: left; padding-left:10px;')
            ),
            array(
                'name'=>'count',
                'header'=>'Records (Local / API)',
                'htmlOptions' => array('style' => 'width: 300px; text-align: center;')
            ),
            array(
                'name'=>'actions',
                'type'=>'raw',
                'value'=>function($data){
                
                echo Chtml::link('<span class="icon-refresh icon-white"></span> Synch', '', array(
                    'class'=>'btn btn-success btn-small',
                    'style'=>'cursor:pointer; font-weight:normal;color:white;',
                    'title'=>'Sync Table',
                    'onClick'=>'js:{
                                   synchTable('.$data["id"].');
                                }',
                    ));
                }
            )
        )
));
?>

<!-- Sync Table Dialog : Start -->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogSyncTable',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Initialize this Laboratory',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>250,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));

	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- Sync Table Dialog : End -->

<script type="text/javascript">
function synchTable(id)
{
    <?php echo CHtml::ajax(array(
			'url'=>$this->createUrl('config/syncTable'),
			'data'=> "js:$(this).serialize()+ '&id='+id",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
					$('#dialogSyncTable').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    //$('#dialogSyncTable form').submit(initializeCode);
                }
                else
                {
					$.fn.yiiGridView.update('rights-grid');
					//$('#dialogSyncTable').html(data.div);
					//setTimeout(\"$('#dialogSyncTable').dialog('close') \",1000);
                }
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogSyncTable").html(
						\'<div class="loader">'.$image.'<br\><br\>Processing.<br\> Please wait...</div>\'
					);
             }',
			 'error'=>"function(request, status, error){
				 	$('#dialogSyncTable').html(status+'('+error+')'+': '+ request.responseText );
					}",
			
            ))?>;
    return false;	
}
</script>
