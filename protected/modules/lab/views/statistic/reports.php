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
                                $(".filters-form form").submit();
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

<h3 class="title" style="text-align: center; margin-top: -20px; margin-bottom: -20px;"><?php //echo 'Number of Accurate Results Issued';?> </h3>
<?php 
$this->widget('ext.groupgridview.GroupGridView', array(
   'id' => 'grid-summary',
   'dataProvider' => $reportStatsAccurateResults,
   'itemsCssClass'=>'table table-striped table-bordered table-condensed',
   //'extraRowHeaderColSpan' => array('chem'),
   'summaryText'=>'',
   'extraHeaders'=>array(
   		array(
   			array('text'=>'', 'options'=>array('class'=>'sample-header-class', 'rowspan'=>2)),
            array('text'=>'Jan','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Feb','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Mar','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Apr','options'=>array('class'=>'sample-header-class')),
            array('text'=>'May','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Jun','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Jul','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Aug','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Sep','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Oct','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Nov','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Dec','options'=>array('class'=>'sample-header-class')),
            array('text'=>'','options'=>array('class'=>'sample-header-class')),
    )),
   'columns' => array(
        array(
            'name'=>'lab_id',
            'header'=>'Laboratory',
            'value'=>'$data->lab->labName',
            
            'htmlOptions'=>array('style'=> 'width: 250px; text-align: left; font-weight: bold; padding-left: 10px;'),
            'footer'=>'TOTAL',
            'footerHtmlOptions'=>array('style'=>'text-align: right; font-weight: bold;'),
        ),
        array(
            'name'=>'jan',
            'header'=>'Number of Accurate Results Issued',
            'headerHtmlOptions'=>array('colspan'=>12),
            'value'=>'$data->monthReportCount(1, "'.$year.'", $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(1, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'feb',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(2, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(2, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'mar',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(3, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(3, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'apr',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(4, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(4, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'may',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(5, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(5, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'jun',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(6, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(6, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'jul',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(7, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(7, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'aug',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(8, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(8, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'sep',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(9, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(9, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'oct',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(10, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(10, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'nov',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(11, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(11, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'dec',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(12, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(12, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'total',
            'header'=>'Total',
            'value'=>'$data->totalYearReportCountPerLab("'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center; font-weight: bold'),
            'footer'=>$model->totalYearReportCount($year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
   ),
));
?>


<?php 
$this->widget('ext.groupgridview.GroupGridView', array(
   'id' => 'grid-summary',
   'dataProvider' => $reportStatsReissue,
   'itemsCssClass'=>'table table-striped table-bordered table-condensed',
   //'extraRowHeaderColSpan' => array('chem'),
   'summaryText'=>'',
   'extraHeaders'=>array(
   		array(
   			array('text'=>'', 'options'=>array('class'=>'sample-header-class', 'rowspan'=>2)),
            array('text'=>'Jan','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Feb','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Mar','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Apr','options'=>array('class'=>'sample-header-class')),
            array('text'=>'May','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Jun','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Jul','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Aug','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Sep','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Oct','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Nov','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Dec','options'=>array('class'=>'sample-header-class')),
            array('text'=>'','options'=>array('class'=>'sample-header-class')),
    )),
   'columns' => array(
        array(
            'name'=>'lab_id',
            'header'=>'Laboratory',
            'value'=>'$data->lab->labName',
            
            'htmlOptions'=>array('style'=> 'width: 250px; text-align: left; font-weight: bold; padding-left: 10px;'),
            'footer'=>'TOTAL',
            'footerHtmlOptions'=>array('style'=>'text-align: right; font-weight: bold;'),
        ),
        array(
            'name'=>'jan',
            'header'=>'Number of Results Reissued',
            'headerHtmlOptions'=>array('colspan'=>12),
            'value'=>'$data->monthReportReissueCount(1, "'.$year.'", $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportReissueCount(1, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'feb',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportReissueCount(2, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportReissueCount(2, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'mar',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportReissueCount(3, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportReissueCount(3, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'apr',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportReissueCount(4, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportReissueCount(4, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'may',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportReissueCount(5, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportReissueCount(5, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'jun',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportReissueCount(6, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportReissueCount(6, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'jul',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportReissueCount(7, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportReissueCount(7, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'aug',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportReissueCount(8, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportReissueCount(8, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'sep',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportReissueCount(9, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportReissueCount(9, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'oct',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportReissueCount(10, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportReissueCount(10, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'nov',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportReissueCount(11, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportReissueCount(11, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'dec',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportReissueCount(12, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportReissueCount(12, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'total',
            'header'=>'Total',
            'value'=>'$data->totalYearReportReissueCountPerLab("'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center; font-weight: bold'),
            'footer'=>$model->totalYearReportReissueCount($year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
   ),
));
?>

<?php 
/*$this->widget('ext.groupgridview.GroupGridView', array(
   'id' => 'grid-summary',
   'dataProvider' => $reportStats,
   'itemsCssClass'=>'table table-striped table-bordered table-condensed',
   //'extraRowHeaderColSpan' => array('chem'),
   'summaryText'=>'',
   'extraHeaders'=>array(
   		array(
   			array('text'=>'', 'options'=>array('class'=>'sample-header-class', 'rowspan'=>2)),
            array('text'=>'Jan','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Feb','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Mar','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Apr','options'=>array('class'=>'sample-header-class')),
            array('text'=>'May','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Jun','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Jul','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Aug','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Sep','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Oct','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Nov','options'=>array('class'=>'sample-header-class')),
            array('text'=>'Dec','options'=>array('class'=>'sample-header-class')),
            array('text'=>'','options'=>array('class'=>'sample-header-class')),
    )),
   'columns' => array(
        array(
            'name'=>'lab_id',
            'header'=>'Laboratory',
            'value'=>'$data->lab->labName',
            
            'htmlOptions'=>array('style'=> 'width: 250px; text-align: left; font-weight: bold; padding-left: 10px;'),
            'footer'=>'TOTAL',
            'footerHtmlOptions'=>array('style'=>'text-align: right; font-weight: bold;'),
        ),
        array(
            'name'=>'jan',
            'header'=>'Number of Late Results Issued',
            'headerHtmlOptions'=>array('colspan'=>12),
            'value'=>'$data->monthReportReissueCount(1, "'.$year.'", $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(1, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'feb',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(2, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(2, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'mar',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(3, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(3, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'apr',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(4, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(4, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'may',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(5, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(5, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'jun',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(6, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(6, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'jul',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(7, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(7, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'aug',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(8, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(8, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'sep',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(9, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(9, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'oct',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(10, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(10, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'nov',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(11, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(11, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'dec',
            'header'=>'',
            'headerHtmlOptions'=>array('style'=>'display: none;'),
            'value'=>'$data->monthReportCount(12, "'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center'),
            'footer'=>$model->totalMonthReportCount(12, $year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
       array(
            'name'=>'total',
            'header'=>'Total',
            'value'=>'$data->totalYearReportCountPerLab("'.$year.'",  $data->lab_id)',
            'htmlOptions'=>array('style'=> 'text-align: center; font-weight: bold'),
            'footer'=>$model->totalYearReportCount($year),
            'footerHtmlOptions'=>array('style'=>'text-align: center; font-weight: bold;'),
        ),
   ),
));*/
?>