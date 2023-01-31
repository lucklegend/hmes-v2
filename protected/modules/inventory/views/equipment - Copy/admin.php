<?php
/* @var $this EquipmentController */
/* @var $model Equipment */

$this->breadcrumbs=array(
	'Equipments'=>array('index'),
	'Manage',
);

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

<h1>Manage Equipments</h1>

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
		'ID',
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
		// 'tags',
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
                                        'url'=>'"#"',
                                        'click'=>'function(){var rowIdx=$(this).closest("tr").attr("sectionRowIndex");var rowId=$("#"+$(this).closest("div.grid-view").attr("id")+" > div.keys > span:eq("+rowIdx+")").text();alert(rowId);}', 
                                ),
                       

                    ),            
            ),
		// array(
		// 	'class'=>'CButtonColumn',
		// ),
	),
)); 

?>
