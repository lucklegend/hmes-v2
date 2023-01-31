<?php
/* @var $this StatisticController */

$this->breadcrumbs=array(
	'Statistic',
);
?>
<?php 
Yii::app()->clientScript->registerScript('customs', "
	
	$('.filters-form form').submit(function(){
		
		$('#grid-summary').yiiGridView('update', {
			data: $(this).serialize(),
			beforeSend: function(){
				  var overlay = new ItpOverlay('grid-summary');
	        	  overlay.show();
			}		
		});		
		return false;
	});

	$('#applyDateRange').click(function(){
		$('.filters-form form').submit();
	});
	
");
?>


<fieldset class="legend-border" style="margin-bottom:0;">
<legend class="legend-border">Filters</legend>
<div class="filters-form form wide"> 
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions'=>array('style'=>'margin-bottom:0;')
)); ?>

<?php	
	echo CHtml::dropDownList('year', date("Y"),	
				CHtml::listData($this->getYear(), 'index', 'year'),
				array(
			   		'onchange'=>'js:{
			   					//$(".customers-form form").submit()
								//var year = $("#year").val();
			   					//$("h3.title").text("Customers Served for "+year);
			   					//$("a.export").attr("href","exportCustomer/year/"+year);
			   					}',
				)
	);
?>

<?php	
	echo CHtml::dropDownList('month', abs(date("m")),	
				CHtml::listData($this->getMonth(), 'index', 'month'),
				array(
			   		'onchange'=>'js:{
			   					//$(".customers-form form").submit()
								//var year = $("#year").val();
			   					//$("h3.title").text("Customers Served for "+year);
			   					//$("a.export").attr("href","exportCustomer/year/"+year);
			   					}',
				)
	);
?>

<?php   
    echo CHtml::dropDownList('lab', array(),   
                Lab::listDataByLabName(),
                array(
                    'onchange'=>'js:{
                                //$(".customers-form form").submit()
                                //var lab = $("#lab").val();
                                //$("h3.title").text("Customers Served for "+year);
                                //$("a.export").attr("href","exportCustomer/year/"+year);
                                }',
                )
    );
?>

<?php echo CHtml::link('<span class="icon-white icon-search"></span> Apply', '', array('id'=>'applyDateRange', 'class'=>'btn btn-info'));?>

<?php $this->endWidget(); ?>
</div>    
</fieldset>

<h3 class="title" style="text-align: center; margin-top: -20px; margin-bottom: -20px;"><?php echo 'Summary of Samples';?> </h3>
<?php 
	 $this->widget('ext.groupgridview.GroupGridView', array(
   'id' => 'grid-summary',
   'dataProvider' => $sampleStats,
   'itemsCssClass'=>'table table-striped table-bordered table-condensed',
   'extraRowHeaderColSpan' => array('chem'),
   'summaryText'=>'',
   'columns' => array(
        array(
            'name'=>'requestRefNum',
            'header'=>'TSR Number',
            'headerHtmlOptions'=>array(),
            'htmlOptions'=>array('style'=> 'text-align: center; width:150px;')
        ),
        array(
            'name'=>'request.customer.customerName',
            'header'=>'Name of Client',
            'headerHtmlOptions'=>array(),
            'value'=>'$data->customer->customerName',
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
        array(
            'name'=>'perSampleCount',
            'header'=>'Sample',
            'headerHtmlOptions'=>array(),
            'value'=>'$data->perSampleCount($data->requestRefNum)',
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
        array(
            'name'=>'perAnalysisCount',
            'header'=>'Analysis',
            'headerHtmlOptions'=>array(),
            'value'=>'$data->perAnalysisCount($data->requestRefNum)',
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
        array(
            'name'=>'valueAssistance',
            'header'=>'Value of Assistance',
            'headerHtmlOptions'=>array(),
            'value'=>'Yii::app()->format->formatNumber($data->perDiscounted($data->total, $data->discount, $data->requestRefNum))',
            'htmlOptions'=>array('style'=> 'text-align: right')
        ),
        array(
            'name'=>'total',
            'header'=>'Fees',
            'headerHtmlOptions'=>array(),
            'value'=>'Yii::app()->format->formatNumber($data->total)',
            'htmlOptions'=>array('style'=> 'text-align: right')
        ),
        array(
            'name'=>'request.customer.customertype.type',
            'header'=>'Cus Type',
            'headerHtmlOptions'=>array(),
            'value'=>'$data->customer->customertype->type',
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
        array(
            'name'=>'request.customer.province.name',
            'header'=>'Province',
            'headerHtmlOptions'=>array(),
            'value'=>'$data->customer->province->name',
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
        array(
            'name'=>'request.customer.municipality.district',
            'header'=>'District',
            'headerHtmlOptions'=>array(),
            'value'=>'$data->customer->municipality->district',
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
        array(
            'name'=>'request.customer.completeAddress',
            'header'=>'Complete Address',
            'headerHtmlOptions'=>array(),
            'value'=>'$data->customer->completeAddress',
            'htmlOptions'=>array('style'=> 'text-align: center; width:250px;')
        ),
   ),
));
?>