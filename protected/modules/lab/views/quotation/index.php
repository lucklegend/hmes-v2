<?php
/* @var $this RequestController */
/* @var $model Request */

$this->breadcrumbs=array(
	'Quotations'=>array('index'),
	'Manage',
);

$this->menu=array(
	//array('label'=>'List Request', 'url'=>array('index')),
	// array('label'=>'Import Data', 'url'=>array('importData')),
	array('label'=>'Create Quotation', 'url'=>array('create')),
);

Yii::app()->user->getState('pageSize');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#request-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
//echo Yii::app()->user->rstlId;
?>
<div class="span6">
<h1>Manage Quotations</h1>
</div>
<div class="span6">
	<div class="row buttons">
		<?php echo CHtml::Link('Create Quotation',Yii::app()->Controller->createUrl("quotation/create"), array('class'=>'btn btn-info pull-right')); ?>
	</div>
</div>

<div class="span12" style="margin-left:0px;">
	<p>
	You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
	or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
	</p>
</div>

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'request-grid',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'htmlOptions'=>array('class'=>'grid-view padding0'),
	//'rowHtmlOptionsExpression' => 'array("title" => "Click to view request", "class"=>"link-hand ".$data->status["class"])',
	'rowHtmlOptionsExpression' => 'array("title"=>"Click on quotation view details","class"=>"alert gray alert-info")',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		array(
			'name'=>'quotationCode',
			'type'=>'raw',
			'value'=>'Chtml::link($data->quotationCode,Yii::app()->Controller->createUrl("quotation/view",array("id"=>$data->id)))',
			'htmlOptions'=>array('title'=>'Click to view details', 'style'=>'font-weight:bold; width:120px;')
		),
		array(
			'name'=>'requestDate',
			'value'=>'date("Y-m-d", strtotime($data->requestDate))',
			'htmlOptions' => array('style' => 'width: 75px; text-align: right; padding-right: 10px;'),
		),
		array(
			'name'=>'company',
			'type'=>'raw',
			'value'=>'Chtml::link($data->company,Yii::app()->Controller->createUrl("quotation/view",array("id"=>$data->id)))',
			'htmlOptions'=>array('title'=>'Click to view details', 'style'=>'font-weight:bold;')
		),
		array(
			'name'=>'address',
			'type'=>'raw',
			'value'=>'Chtml::link($data->address,Yii::app()->Controller->createUrl("quotation/view",array("id"=>$data->id)))',
			'htmlOptions' => array('style' => 'text-align: left; padding-right: 10px;'),
		),
		array(
			'name'=>'contact_person',
			'type'=>'raw',
			'value'=>'Chtml::link($data->contact_person,Yii::app()->Controller->createUrl("quotation/view",array("id"=>$data->id)))',
			'htmlOptions' => array('style' => 'text-align: left; padding-right: 10px;'),
		),
		array(
			'name'=>'contact_number',
			'type'=>'raw',
			'value'=>'Chtml::link($data->contact_number,Yii::app()->Controller->createUrl("quotation/view",array("id"=>$data->id)))',
			'htmlOptions' => array('style' => 'text-align: left; padding-right: 10px;'),
		),
		array(
 			//'class'=>'CButtonColumn',
 			'class'=>'bootstrap.widgets.TbButtonColumn',
 			'template'=>'{view} {update} {delete}',
            'header'=>CHtml::dropDownList('pageSize',Yii::app()->user->getState('pageSize'),                    
                  array('page_size'=>'Size',10=>10,20=>20,50=>50,100=>100),
                  array(
                        'onchange'=>"$.fn.yiiGridView.update('request-grid',{ data:{pageSize: $(this).val() }})",
                        'style'=>'width: 60px;')
            ),
            'headerHtmlOptions'=>array('style'=>'text-align: left; padding-right: 10px;'),
            
 		),
	),
	'selectableRows'=>1,
	//'selectionChanged'=>'function(id){location.href = "'.$this->createUrl('request/view/id').'/"+$.fn.yiiGridView.getSelection(id);}',
)); ?>
