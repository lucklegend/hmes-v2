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
   'extraHeaders'=>array(
   		array(
   			array('text'=>'', 'options'=>array('class'=>'sample-header-class')),
            array('text'=>'Samples','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Parameters','options'=>array('class'=>'sample-header-class')),
            
            array('text'=>'Samples','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Parameters','options'=>array('class'=>'sample-header-class')),
            
            array('text'=>'Samples','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Parameters','options'=>array('class'=>'sample-header-class')),
    )),
   'columns' => array(
        array(
            'name'=>'requestDate',
            'header'=>'Date',
            'headerHtmlOptions'=>array('rowspan'=>2),
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
        array(
            'name'=>'dailySampleCountChem',
            'header'=>'CHEMLAB',
            'headerHtmlOptions'=>array('colspan'=>2),
            'value'=>'$data->dailySampleCount($data->requestDate, 1)',
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
        array(
            'name'=>'dailyAnalysisCountChem',
            'header'=>'MICROLAB',
            'headerHtmlOptions'=>array('colspan'=>2),
            'value'=>'$data->dailyAnalysisCount($data->requestDate, 1)',
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
        array(
            'name'=>'dailySampleCountMicro',
            'header'=>'METROLAB',
            'headerHtmlOptions'=>array('colspan'=>2),
            'value'=>'$data->dailySampleCount($data->requestDate, 2)',
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
        array(
            'name'=>'dailyAnalysisCountMicro',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->dailyAnalysisCount($data->requestDate, 2)',
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
        array(
            'name'=>'dailySampleCountMetro',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->dailySampleCount($data->requestDate, 3)',
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
        array(
            'name'=>'dailyAnalysisCountMetro',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->dailyAnalysisCount($data->requestDate, 3)',
            'htmlOptions'=>array('style'=> 'text-align: center')
        ),
   ),
));
?>