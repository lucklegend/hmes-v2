<?php
/* @var $this EquipmentController */
/* @var $model Equipment */

$this->breadcrumbs=array(
	'Equipments'=>array('index'),
	'Manage',
);

//echo Yii::app()->basePath.'/equipment_pictures/';
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'mydialog',
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'Group/Tag',
        'autoOpen'=>false,
        'show'=>'scale',
        'hide'=>'scale',
        'width'=>'auto',
        'min-width'=>500,
        'modal'=>true,
        'height'=>'auto',
        // 'close'=>'js:function(){  $.fn.yiiGridView.update("equipment-grid"); }',
        //'resizable'=>true,
    ),
));
$this->endWidget('zii.widgets.jui.CJuiDialog');
$this->menu=array(
	array('label'=>'List Equipment', 'url'=>array('index')),
	array('label'=>'Create Equipment', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#equipment-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Equipment</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'equipment-grid',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		// 'ID',
		'name',
        'equipmentID',
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
		// 'specification',
        'date_purchased',
		'date_received',
		array(
			'name'=>'fundings',
            'header'=>'Project',
			'value'=>'$data->fund->name'
			),
		array(
			'name'=>'Depreciated Cost',
			'type'=>'raw',
			'filter'=>false,
			'value'=> function($data){
					$cost=$data['depreciation'];
					return $cost ;
				},
			'htmlOptions'=>array('style'=>'text-align:center'),
		),
		array(
			'name'=>'fullName',
			'header'=>'ARE',
			'value'=>'$data->user->profile->fullName'
			),
		// 'are',
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
		// 'tags',
		
		array(
                
                'class'=>'MyButtonColumn',
               
                            
            ),
		// array(
		// 	'class'=>'CButtonColumn',
		// ),
	),
)); 

?>
<div class="row-fluid">
    <?php
    echo CHtml::link('Create Group','#mydialog',array('class'=>'btn btn-info btn-small','onclick'=>'$("#mydialog").dialog("open"); createGroup();'));
    echo CHtml::link('Print','',array('class'=>'btn btn-info btn-small','id'=>'btnprint','target'=>'_blank','onclick'=>'$("#mydialog").dialog("open"); '));
// $PrintBarcode = Chtml::link(
// 		'<span class="icon-white icon-print"></span> Print Barcode'
// 	, $this->createUrl('request/printBarcode',array('id'=>$model->id)), array( 
// 			'target'=>"_blank",
// 			'style'=>'cursor:pointer;',
// 			'class'=>'btn btn-success',
// 			))
    ?>
    <span id="generatelink"></span>
</div>



<script type="text/javascript">

$('#btnprint').click(function(){
		var equipmentID= $('#equipment-grid input[name=\"Equipment[equipmentID]\"').val();
		var name= $('#equipment-grid input[name=\"Equipment[name]\"').val();
		var lab= $('#equipment-grid select[name=\"Equipment[lab]\"').val();
		var date_received= $('#equipment-grid input[name=\"Equipment[date_received]\"').val();
		var date_purchased= $('#equipment-grid input[name=\"Equipment[date_purchased]\"').val();
		var fundings= $('#equipment-grid input[name=\"Equipment[fundings]\"').val();
		var mr= $('#equipment-grid input[name=\"Equipment[fullName]\"').val();



         //alert(equipmentID + name  + date_received + date_purchased + fundings + mr);


          <?php echo CHtml::ajax(array(
            'url'=>$this->createUrl('/inventory/equipment/download'),
            'data'=> "js:{'equipmentID' : equipmentID , 'name' : name , 'date_received' : date_received , 'date_purchased' : date_purchased , 'fundings' : fundings , 'mr' : mr, 'lab': lab}",
            'type'=>'post',
            // 'dataType'=>'json',
            'success'=>"function(data)
            {
            	 $('#mydialog').html(data);
            	// $('#generatelink').html(data);
            	//window.open(data.file,'_blank' );
            	//alert(data.file);
       //       	var $a = $('<a>');
       //       	$a.attr('href',data.file);
			    // $('body').append($a);
			    // $a.attr('download','file.xls');
			    // $a[0].click();
			    // $a.remove();


            	//alert(data);
                //  if (data.status == 'failure')
                // {
                //     $('#mydialog').html(data.div);
                //     // Here is the trick: on submit-> once again this function!
                //    $('#mydialog form').submit(createGroup);
                // }
                // else
                // {
                //     $.fn.yiiGridView.update('equipment-grid');
                //     $('#mydialog').html(data.div);
                //     setTimeout(\"$('#mydialog').dialog('close') \",1000);
                // }
            }",
            'beforeSend'=>'function(jqXHR, settings){
                    // $("#mydialog").html(
                    //     \'<div class="loader"><br\><br\>Generating form.<br\> Please wait...</div>\'
                    // );
             }',
             'error'=>"function(request, status, error){
                    // $('#mydialog').html(status+'('+error+')'+': '+ request.responseText );
                    }",
            ))?>;





    });

function createGroup()
{
    <?php echo CHtml::ajax(array(
            'url'=>$this->createUrl('/inventory/equipment/group'),
            'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                 if (data.status == 'failure')
                {
                    $('#mydialog').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                   $('#mydialog form').submit(createGroup);
                }
                else
                {
                    $.fn.yiiGridView.update('equipment-grid');
                    $('#mydialog').html(data.div);
                    setTimeout(\"$('#mydialog').dialog('close') \",1000);
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
            ))?>;
    return false; 
}



</script>