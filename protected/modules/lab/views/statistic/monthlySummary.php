<?php
/* @var $this StatisticController */

$this->breadcrumbs=array(
	'Statistic',
);
?>
<?php 
Yii::app()->clientScript->registerScript('customs', "
	
	$('.summary-form form').submit(function(){
		
		$('#grid-summary').yiiGridView('update', {
			data: $(this).serialize(),
//            beforeSend: function(){
//				  var overlay = new ItpOverlay('grid-collection');
//	        	  overlay.show();
//			}
		});
		
		return false;
	});
	
");
?>

<div class="summary-form form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<b>&nbsp;<?php echo CHtml::encode('LAB :'); ?>&nbsp;</b>
<?php 
	echo CHtml::dropDownList('lab', array(),	
			Lab::listDataByLabName(),
			array(
				'onchange'=>'js:{
                    $(".summary-form form").submit();
				}',
			)
	);
?>

<b>&nbsp;<?php echo CHtml::encode('MONTH :'); ?>&nbsp;</b>
<?php	
	echo CHtml::dropDownList('month', array(date('m')),	
				CHtml::listData($this->getMonth(), 'index', 'month'),
				array(
			   		'onchange'=>'js:{
			   					$(".summary-form form").submit();
			   					}',
				)
	);
?>

<b>&nbsp;<?php echo CHtml::encode('YEAR :'); ?>&nbsp;</b>
<?php
	echo CHtml::dropDownList('year', array(date('Y')),	
				CHtml::listData($this->getYear(), 'index', 'year'),
				array(
			   		'onchange'=>'js:{
			   					$(".summary-form form").submit();
			   					}',
				)
	);
?>

<?php echo ' '.CHtml::link('Export',$this->createUrl('accomplishments/export', array('labId'=>$labId, 'month'=>$month, 'year'=>$year)), array('class'=>'export btn btn-success btn-small','title'=>'Export to excel'));?>
<?php $this->endWidget(); ?>
</div>


<?php 
	 $this->widget('ext.groupgridview.GroupGridView', array(
	      'id' => 'grid-summary',
 	      'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
		  //'rowHtmlOptionsExpression' => 'array("title" => "Click to update", "class"=>"link-hand")',
	      'dataProvider' => $dataProvider,
		  'summaryText' => '',
          'mergeColumns' => array('requestRefNum'),
	      'columns' => array(
              'id', 
              //'sample.request.id',
              array(
                'name'=>'requestRefNum',
				'header'=>'Request Ref Num',
				'type'=>'raw',
                'value'=>'$data->sample->request->requestRefNum',
				'htmlOptions' => array('style' => 'width: 60px; text-align: left; padding-left: 15px;'),
              ),
              //'sample.request.customer.id',
              array(
                'name'=>'sample.request.customer.customerName',
				'header'=>'Name of Client',
				//'type'=>'raw',
                'value'=>'$data->sample->request->customer->customerName',
				'htmlOptions' => array('style' => 'width: 60px; text-align: left; padding-left: 15px;'),
              ),
              array(
                'name'=>'sample.request.customer.address',
				'header'=>'Address',
				'type'=>'raw',
                'value'=>'$data->sample->request->customer->address',
				'htmlOptions' => array('style' => 'width: 60px; text-align: left; padding-left: 15px;'),
              ),
              array(
                'name'=>'sample.request.customer.typeId',
				'header'=>'Non-Setup',
				//'type'=>'raw',
                'value'=>'($data->sample->request->customer->typeId == 2) ? 1 : 0',
				'htmlOptions' => array('style' => 'width: 10px; text-align: center;'),
              ),
              array(
                'name'=>'sample.request.customer.typeId',
				'header'=>'Setup',
				//'type'=>'raw',
                'value'=>'($data->sample->request->customer->typeId == 1) ? 1 : 0',
				'htmlOptions' => array('style' => 'width: 10px; text-align: center;'),
              ),
              array(
                'name'=>'sampleCode',
				'header'=>'Name',
				'type'=>'raw',
                'value'=>'$data->sample->sampleCode',
				'htmlOptions' => array('style' => 'width: 60px; text-align: center;'),
              ),
              array(
                'name'=>'sample.id',
				'header'=>'Sample',
				'type'=>'raw',
                'value'=>'$data->sample->sampleName',
				'htmlOptions' => array('style' => 'width: 60px; text-align: left; padding-left: 15px;'),
              ),
              
              array(
                'name'=>'testName',
				'header'=>'Test Name',
				'type'=>'raw',
                'value'=>'$data->testName',
				'htmlOptions' => array('style' => 'width: 60px; text-align: left; padding-left: 15px;'),
              ),
              array(
                  'name'=>'requestTotal',
                  'header'=>'Request Total',
                  'value'=>'Yii::app()->format->formatNumber($data->requestTotal)',
                  'htmlOptions' => array('style' => 'width: 7%; text-align: right; padding-right: 10px;'),
              ),
              
              //'paidNonSetup',
              array(
                  'name'=>'paidNonSetup',
                  'header'=>'Paid Non-Setup',
                  'value'=>'Yii::app()->format->formatNumber($data->paidNonSetup)',
                  'htmlOptions' => array('style' => 'width: 7%; text-align: right; padding-right: 10px;'),
              ),
              //'paidSetup',
              array(
                  'name'=>'paidSetup',
                  'header'=>'Paid Setup',
                  'value'=>'Yii::app()->format->formatNumber($data->paidSetup)',
                  'htmlOptions' => array('style' => 'width: 7%; text-align: right; padding-right: 10px;'),
              ),
              //'gratisNonSetup',
              array(
                  'name'=>'gratisNonSetup',
                  'header'=>'Gratis Non-Setup',
                  'value'=>'Yii::app()->format->formatNumber($data->gratisNonSetup)',
                  'htmlOptions' => array('style' => 'width: 7%; text-align: right; padding-right: 10px;'),
              ),
              //'gratisSetup',
              array(
                  'name'=>'gratisSetup',
                  'header'=>'Gratis Setup',
                  'value'=>'Yii::app()->format->formatNumber($data->gratisSetup)',
                  'htmlOptions' => array('style' => 'width: 7%; text-align: right; padding-right: 10px;'),
              ),
              //'discount',
              array(
                  'name'=>'discount',
                  'header'=>'Discount',
                  'value'=>'Yii::app()->format->formatNumber($data->discount)',
                  'htmlOptions' => array('style' => 'width: 7%; text-align: right; padding-right: 10px;'),
              ),
              //'balance',
              array(
                  'name'=>'totalFeesCollected',
                  'header'=>'Total Fees Collected',
                  'value'=>'Yii::app()->format->formatNumber($data->totalFeesCollected)',
                  'htmlOptions' => array('style' => 'width: 7%; text-align: right; padding-right: 10px;'),
              ),
	    ))); 
?>