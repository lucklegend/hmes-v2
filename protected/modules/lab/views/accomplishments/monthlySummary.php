<?php 
Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl . '/css/font-awesome/css/font-awesome.min.css');

Yii::app()->clientScript->registerScript('dataProvider', "
	
	/*$('.genReport form').submit(function(){
		
		$('#accomplishment-grid').yiiGridView('update', {
			data: $(this).serialize(),
			beforeSend: function(){
				  var overlay = new ItpOverlay('accomplishment-grid');
	        	  overlay.show();
			}
		});
		return false;
	});*/
	
	$('.accomplishment-form form').submit(function(){
		
		$('#accomplishment-grid').yiiGridView('update', {
			data: $(this).serialize(),
			beforeSend: function(){
				  var overlay = new ItpOverlay('accomplishment-grid');
	        	  overlay.show();
			}
		});
		return false;
	});
");
?>
<div class="summary-form">
<?php $image=Yii::app()->baseUrl.('/images/ajax-loader.gif');?>
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
						var lab = $("#lab").val();
                        var month = $("#month").val();
                        var year = $("#year").val();
						$("a.export").prop("onclick", null).off("click");
						$("a.export").attr("href","accomplishments/export/lab/"+lab+"/month/"+month+"/year/"+year);
                        
                        $(".summary-form form").submit();
				}',
			)
	);
?>

<b>&nbsp;<?php echo CHtml::encode('MONTH :'); ?>&nbsp;</b>
<?php	
	echo CHtml::dropDownList('month', abs($month),	
				CHtml::listData($this->getMonth(), 'index', 'month'),
				array(
			   		'onchange'=>'js:{
                        var labId = $("#lab").val();
                        var month = $("#month").val();
                        var year = $("#year").val();
                        $("a.export").prop("onclick", null).off("click");
                        $("a.export").attr("href","accomplishments/export/labId/"+labId+"/month/"+month+"/year/"+year);

                        $(".summary-form form").submit();
                        }',
				)
	);
?>

<b>&nbsp;<?php echo CHtml::encode('YEAR :'); ?>&nbsp;</b>
<?php
	echo CHtml::dropDownList('year', $year,	
				CHtml::listData($this->getYear(), 'index', 'year'),
				array(
			   		'onchange'=>'js:{
                        var labId = $("#lab").val();
                        var month = $("#month").val();
                        var year = $("#year").val();
                        $("a.export").prop("onclick", null).off("click");
                        $("a.export").attr("href","accomplishments/export/labId/"+labId+"/month/"+month+"/year/"+year);

                        $(".summary-form form").submit();
                        }',
				)
	);
?>


<?php /*$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		'name'=>'from_date',
		'value'=>$model->from_date ? date('Y-m-d',strtotime($model->from_date)) : date('Y-m-d'),
		//'value'=>$model->from_date ? date('Y-m-d',strtotime($model->effective_date)) : date('Y-m-d'),
		// additional javascript options for the date picker plugin
		
		'options'=>array(
			'showAnim'=>'fold',
			'dateFormat'=>'yy-mm-dd',
			'changeMonth' => 'true',
			'changeYear' => 'true',
			'onSelect'=>'js:function(){
				
				if($("#from_date").val() > $("#to_date").val()){
					alertMsg();
					//$("a.export").attr("href","accomplishments/excel/lab/"+lab+"/from_date/"+fromDate+"/to_date/"+toDate+"/rstlId/"+rstl);
					$("a.export").attr("onclick","alertMsg()");
					$("a.export").attr("href","javascript:void(0)");
					//$("h3.title").text("'.Yii::app()->Controller->showRstl(Yii::app()->Controller->getRstlId()).' Summary of Accomplishment "+fromDate+" to "+toDate);
				} else {
					
					$(".accomplishment-form form").submit();
					var lab = $("#lab").val();
					var fromDate = $("#from_date").val();
					var toDate = $("#to_date").val();
					var rstl = '.Yii::app()->Controller->getRstlId().';
					$("h3.title").text("'.Yii::app()->Controller->showRstl(Yii::app()->Controller->getRstlId()).' Summary of Accomplishment "+fromDate+" to "+toDate);
					$("a.export").prop("onclick", null).off("click");
					$("a.export").attr("href","accomplishments/excel/lab/"+lab+"/from_date/"+fromDate+"/to_date/"+toDate+"/rstlId/"+rstl);
				}
				
			}',
		),
	));*/
?>


<?php /*$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		'name'=>'to_date',
		'value'=>$model->to_date ? date('Y-m-d',strtotime($model->to_date)) : date('Y-m-d'),
		// additional javascript options for the date picker plugin
		
		'options'=>array(
			'showAnim'=>'fold',
			'dateFormat'=>'yy-mm-dd',
			'changeMonth' => 'true',
			'changeYear' => 'true',
			'onSelect'=>'js:function(){
				
				if($("#from_date").val() > $("#to_date").val()){
					alertMsg();
					//$("a.export").attr("href","accomplishments/excel/lab/"+lab+"/from_date/"+fromDate+"/to_date/"+toDate+"/rstlId/"+rstl);
					$("a.export").attr("onclick","alertMsg()");
					$("a.export").attr("href","javascript:void(0)");
					//$("h3.title").text("'.Yii::app()->Controller->showRstl(Yii::app()->Controller->getRstlId()).' Summary of Accomplishment "+fromDate+" to "+toDate);
				} else {
					$(".accomplishment-form form").submit();
					var lab = $("#lab").val();
					var fromDate = $("#from_date").val();
					var toDate = $("#to_date").val();
					var rstl = '.Yii::app()->Controller->getRstlId().';
					$("h3.title").text("'.Yii::app()->Controller->showRstl(Yii::app()->Controller->getRstlId()).' Summary of Accomplishment "+fromDate+" to "+toDate);
					$("a.export").prop("onclick", null).off("click");
					$("a.export").attr("href","accomplishments/excel/lab/"+lab+"/from_date/"+fromDate+"/to_date/"+toDate+"/rstlId/"+rstl);
				}
			}',
		),
	));*/
?>
<?php //echo ' '.CHtml::link('<i class="fa fa-refresh fa-lg"></i>&nbsp;</i>Generate',$this->createUrl('accomplishments/excel',array('lab'=>$lab,'from_date'=>$from_date,'to_date'=>$to_date, 'rstlId'=>Yii::app()->Controller->getRstlId())), array('class'=>'export btn btn-success btn-small','title'=>'Generate Accomplishment'));?>


<?php echo ' '.CHtml::link('<i class="fa fa-file-excel-o fa-lg fa-fw">&nbsp;</i>Export',$this->createUrl('accomplishments/export',array('labId'=>$labId,'month'=>$month,'year'=>$year)), array('class'=>'export btn btn-success btn-small','title'=>'Export to excel'));?>

<?php $this->endWidget(); ?>
</div>
<h3 class="title" style="text-align: center; margin-top: -20px; margin-bottom: -20px;"><?php echo Yii::app()->Controller->showRstl(Yii::app()->Controller->getRstlId()).' '.date('Y').' Monthly Summary of Requests';?> </h3>
<br />

<?php 
	 $this->widget('ext.groupgridview.GroupGridView', array(
	      'id' => 'grid-summary',
 	      'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
		  //'rowHtmlOptionsExpression' => 'array("title" => "Click to update", "class"=>"link-hand")',
	      'dataProvider' => $dataProvider,
		  'summaryText' => '',
          'mergeColumns' => array('requestRefNum', 'sample.request.customer.customerName'),
	      'columns' => array(
              //'id', 
              //'sample.request.id',
              array(
                'name'=>'requestRefNum',
				'header'=>'Technical Service Request',
				'type'=>'raw',
                'value'=>'$data->sample->request->requestRefNum',
				'htmlOptions' => array('style' => 'width: 150px; text-align: left; v-align: top; padding-left: 15px; font-weight: bold;'),
              ),
              //'sample.request.customer.id',
              array(
                'name'=>'sample.request.customer.customerName',
				'header'=>'Name of Client',
				'type'=>'raw',
                'value'=>'"<b>".$data->sample->request->customer->customerName."</b><br/>".$data->sample->request->customer->address',
				'htmlOptions' => array('style' => 'width: 250px; text-align: left; padding-left: 15px;'),
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
				'header'=>'Setup Non Core',
				//'type'=>'raw',
                'value'=>'($data->sample->request->customer->typeId == 3) ? 1 : 0',
				'htmlOptions' => array('style' => 'width: 10px; text-align: center;'),
              ),
              array(
                'name'=>'sampleCode',
				'header'=>'Sample Code',
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


<?php $image = CHtml::image(Yii::app()->request->baseUrl . '/images/ajax-loader.gif');?>
<!-- Loader Dialog : Start-->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogLoader',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Generating Report...' ,
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>'40%',
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));
	echo '<div class="loader">'.$image.'<br \><br \>Processing.<br \>Please wait...</div>';
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- Loader Dialog : End -->
<script type="text/javascript">
/*<![CDATA[*/
/*jQuery(document).ready(function() {
             jQuery('#yt0').click(function( {
				 
                            jQuery.yii.submitForm(
                                     this,
                                     'controller/action',{}
                                          );return false;});
                                  });*/
/*]]>*/
function alertMsg() {
    alert('From Date should be <= to To Date');
	
	return false;
}
</script>
<style type="text/css">
	.table-striped tbody tr td.extrarowgroupfooter {
		background-color: #FFE4B5;
		color: #222222;
		/*color: #000000;*/
		text-align:center;
		text-decoration:none;
		font-weight:600;
	}
</style>
