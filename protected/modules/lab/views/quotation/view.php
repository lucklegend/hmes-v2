<?php
/* @var $this QuotationController */
/* @var $model Quotation */

$this->breadcrumbs=array(
	'Quotations'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Quotation', 'url'=>array('index')),
	array('label'=>'Create Quotation', 'url'=>array('create')),
	array('label'=>'Update Quotation', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Quotation', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Quotation', 'url'=>array('admin')),
);
?>

<h1>View Quotation # <?php echo $model->quotationCode; ?></h1>

<?php $this->widget('ext.widgets.DetailView4Col', array(
	'cssFile'=>false,
	'htmlOptions'=>array('class'=>'detail-view table table-striped table-condensed'),
	'data'=>$model,
	'attributes'=>array(
		array(
            'name'=>'requestDate',
            'oneRow'=>true,
            'type'=>'raw',
            'value'=>date('F d, Y',strtotime($model->requestDate)),
        ),	
		'company','contact_person',
		'address','designation',
		'contact_number','email',
		'remarks','noted_by',
	),
)); ?>

<div class="addSample">
<?php
	$this->beginWidget('zii.widgets.CPortlet', array(
		'title'=>"<b>Testing or Calibration Services</b>",
	));	
?>

<h4 class="paddingLeftRight10">Samples
<small>
<?php
	$linkSample = Chtml::link('<span class="icon-white icon-plus-sign"></span> Add Sample', '', array( 
			'style'=>'cursor:pointer;',
			'class'=>'btn btn-info btn-small',
			'onClick'=>'js:{addSample(); $("#dialogSample").dialog("open");}',
			'disabled'=>''
			));
	echo $linkSample;
?>
</small>
</h4>
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
    	'id'=>'sample-grid',
	    'summaryText'=>false,
		'emptyText'=>'No samples.',
		'htmlOptions'=>array('class'=>'grid-view padding0 paddingLeftRight10'),
        // 'rowCssClassExpression'=>'$data->status',
		'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
		'rowHtmlOptionsExpression' => 'array("title" => "Click to update", "class"=>"link-hand")', 
        'dataProvider' => $sampleDataProvider,
        'columns' => array(
        	array(
				'name'=>'#',
				'value'=>'$row+1',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'text-align: center; width:30px;'),
			),
    		array(
				'name'=>'Sample',
				'value'=>'$data->sampleName',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'padding-left: 10px; text-align: center;'),
			),
    		array(
				'name'=>'Quantity',
				'value'=>'$data->qty',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 100px; padding-left: 10px; text-align: center;'),
			),
    		
			array(
			'header'=>'Delete',
			'class'=>'bootstrap.widgets.TbButtonColumn',
				'deleteConfirmation'=>"js:'Do you really want to delete sample: '+$.trim($(this).parent().parent().children(':nth-child(2)').text())+'?'",
				'template'=>'{delete}',
				'buttons'=>array
				(
					'delete' => array(
						'label'=>'Delete Sample',
						'url'=>'Yii::app()->createUrl("lab/quotation/deletesample/id/$data->id")',
					),
				),
			),
        ),
    ));
    ?>

<h4 class="paddingLeftRight10">Analysis
<small>
<?php
	$linkAnalysis = Chtml::link('<span class="icon-white icon-plus-sign"></span> Add Analyses', '', array( 
			'style'=>'cursor:pointer;',
			'onClick'=>'js:{addAnalysis(); $("#dialogAnalysis").dialog("open");}',
			'class'=>'btn btn-info btn-small',
			));
	$linkPackage = Chtml::link('<span class="icon-white icon-plus-sign"></span> Add Package', '', array( 
			'style'=>'cursor:pointer;',
			'onClick'=>'js:{addPackage(); $("#dialogPackage").dialog("open");}',
			'class'=>'btn btn-info btn-small',
			));	
	
	echo $linkAnalysis." ".$linkPackage;
?>
</small>
<small style="float:right;">
<?php 
	$linkDiscount = Chtml::link('<span class="icon-white icon-plus-sign"></span> Discount', '', array( 
			'style'=>'cursor:pointer;',
			'onClick'=>'js:{addDiscount(); $("#dialogDiscount").dialog("open");}',
			'class'=>'btn btn-danger btn-small',
			));
	$linkOnsiteCharge = Chtml::link('<span class="icon-white icon-plus-sign"></span> Add On-site Charge', '', array( 
			'style'=>'cursor:pointer;',
			'onClick'=>'js:{addOnsiteCharges(); $("#dialogOnsite").dialog("open");}',
			'class'=>'btn btn-danger btn-small',
			));	
	echo $linkDiscount." ".$linkOnsiteCharge ;
?>
</small></h4>

<?php
	if($model->discount_rate != NULL && $model->discount_rate != ''){
		$discRate = '('.(int)$model->discount_rate.'%)';
	}else{
		$discRate = '';
	}
	
    $this->widget('zii.widgets.grid.CGridView', array(
    	'id'=>'analysis-grid',
	    'summaryText'=>false,
		'emptyText'=>'No test/calibration.',
		'htmlOptions'=>array('class'=>'grid-view padding0 paddingLeftRight10'),
		'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
		'rowHtmlOptionsExpression' => 'array("title" => "Click to update", "class"=>"link-hand")', 
        //It is important to note, that if the Table/Model Primary Key is not "id" you have to
        //define the CArrayDataProvider's "keyField" with the Primary Key label of that Table/Model.
        'dataProvider' => $testDataProvider,
        'columns' => array(
            //'sample.sampleName',
            array(
				'name'=>'#',
				'value'=>'$row+1',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'text-align: center;'),
			),
    		array(
				'name'=>'SAMPLE',
				'value'=>'$data->sample->sampleName',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 200px; padding-left: 10px;'),
			),
    		//'testName',
			array(
				'name'=>'TEST / CALIBRATION REQUESTED',
				'value'=>'$data->testName',
				'type'=>'raw',
				'htmlOptions' => array('style' => 'padding-left: 10px;'),
			),
    		
    		//'quantity',
    		array(
				'name'=>'QUANTITY',
				'value'=>'$data->sample->qty',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
			),
			
    		//'fee'
    		array(
				'name'=>'UNIT PRICE',
				//'value'=>'Yii::app()->format->formatNumber($data->fee)',
				'value'=>'Yii::app()->format->formatNumber($data->fee)',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 105px; text-align: right; padding-right: 10px;'),
    			'footer'=>'SUBTOTAL<br/>DISCOUNT<br/>On-site Charge<br/><b>TOTAL</b>',
    			'footerHtmlOptions'=>array('style'=>'text-align: right; padding-right: 10px;'),
			),
			array(
				'name'=> 'AMOUNT',
				'value'=> 'Yii::app()->format->formatNumber($data->sample->qty*$data->fee)',
				'type'=>'raw',
				'htmlOptions' => array('style' => 'width: 65px; text-align: right; padding-right: 10px;'),
				'footer'=>
    					Yii::app()->format->formatNumber($model->getTestTotal($testDataProvider->getKeys())).
    					'<br/>'.$discRate.
    					Yii::app()->format->formatNumber($model->getDiscount($testDataProvider->getKeys(), $model->discount_rate, $model->id)).
    					'<br/>'.
    					Yii::app()->format->formatNumber($model->onsite_charge).
    					'<br/><b>'.
    					Yii::app()->format->formatNumber($model->getQutationTotal($testDataProvider->getKeys(), $model->discount_rate, $model->onsite_charge, $model->id)).
    					'</b>',
    			'footerHtmlOptions'=>array('style'=>'text-align: right; padding-right: 10px;'),
			),
			array(
			//'class'=>'CButtonColumn',
			'header'=>'Actions',
			'class'=>'bootstrap.widgets.TbButtonColumn',
						'deleteConfirmation'=>"js:'Do you really want to delete the  '+$.trim($(this).parent().parent().children(':nth-child(3)').text())+'?'",
						'template'=>'{delete}',
						'buttons'=>array
						(
							'delete' => array(
								'label'=>'Delete test/calibration',
								'url'=>'Yii::app()->createUrl("lab/quotation/deletetest/id/$data->id")',
								
							),
						),
			),
        ),
    ));
?>
</div>
<?php $this->endWidget(); //End Portlet ?>    
<div class="generated">
	<?php
	$this->widget('bootstrap.widgets.TbButtonGroup', array(
	        'type'=>'success', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
	        'buttons'=>array(
	            array(
	            	'label'=>'Print Quotation', 
	            	'url'=>$this->createUrl('quotation/print/', array('id'=>$model->id)), 
	            	'htmlOptions'=>array('target'=>'_blank'),
	            ),
	        ),
	    ));
    ?>
</div>
<?php
$image = CHtml::image(Yii::app()->request->baseUrl . '/images/ajax-loader.gif');
Yii::app()->clientScript->registerScript('clkrowgrid', "
$('#sample-grid table tbody tr').live('click',function()
{
	    var id = $.fn.yiiGridView.getKey(
        'sample-grid',
        $(this).prevAll().length 
    	);
		if($(this).children(':nth-child(1)').text()=='No samples.'){
			alert($(this).children(':nth-child(1)').text());
	   		//alert(id);
		}else{
			// alert(id);
			updateSample(id);
			$('#dialogSample').dialog('open');
		}
});
");

Yii::app()->clientScript->registerScript('clkrowgrid2', "
$('#analysis-grid table tbody tr').live('click',function()
{
	    var id = $.fn.yiiGridView.getKey(
        'analysis-grid',
        $(this).prevAll().length 
    	);
    	//alert(id);
		if($(this).children(':nth-child(1)').text()=='No analyses.'){
			alert($(this).children(':nth-child(1)').text());
	   		//alert(id);
		}else{
			updateAnalysis(id);
			$('#dialogAnalysis').dialog('open');
		}
});
");
?> 
<!-- Sample Dialog : Start -->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogSample',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Sample',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>300,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));

	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- Sample Dialog : End -->
<!-- Analysis Dialog : Start -->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogAnalysis',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Analysis',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>400,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));

	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- Analysis Dialog : End -->
<!-- Package Dialog : Start -->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogPackage',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Package',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>400,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));

	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- package Dialog : End -->
<!-- Onsite Dialog : Start -->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogOnsite',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'On-ste Charge',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>400,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));
	echo 'dialogbox';
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- Onsite Dialog : End -->

<!-- Additional Dialog : Start -->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogDiscount',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Discount',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>400,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));
	echo 'dialogbox';
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- Additional Dialog : End -->


<script type="text/javascript">
function addSample()
{
    <?php echo CHtml::ajax(array(
			'url'=>$this->createUrl('quotation/createsample',array('id'=>$model->id,)),
			'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogSample').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogSample form').submit(addSample);
                }
                else
                {
                    $.fn.yiiGridView.update('sample-grid');
					$('#dialogSample').html(data.div);
                    setTimeout(\"$('#dialogSample').dialog('close') \",1000);
					
                }
 
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogSample").html(
						\'<div class="loader">'.$image.'<br\><br\>Generating form.<br\> Please wait...</div>\'
					);
             }',
			 'error'=>"function(request, status, error){
				 	$('#dialogSample').html(status+'('+error+')'+': '+ request.responseText );
					}",
			
            ))?>;
    return false; 
}

function updateSample(id)
{
	<?php 
	echo CHtml::ajax(array(
			'url'=>$this->createUrl('quotation/updatesample'),
			'data'=> "js:$(this).serialize()+ '&id='+id",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogSample').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogSample form').submit(updateSample);
                }
                else
                {
                    $.fn.yiiGridView.update('sample-grid');
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogSample').html(data.div);
                    setTimeout(\"$('#dialogSample').dialog('close') \",1000);
                }
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogSample").html(
						\'<div class="loader">'.$image.'<br\><br\>Retrieving record.<br\> Please wait...</div>\'
					);
            }',
			 'error'=>"function(request, status, error){
				 	$('#dialogSample').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
					}",
            ))?>;
    return false; 
 
}

function addAnalysis()
{
    <?php echo CHtml::ajax(array(
			'url'=>$this->createUrl('quotation/createtest',array('id'=>$model->id)),
			'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogAnalysis').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogAnalysis form').submit(addAnalysis);
                }
                else
                {
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogAnalysis').html(data.div);
                    setTimeout(\"$('#dialogAnalysis').dialog('close') \",1000);
					
                }
 
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogAnalysis").html(
						\'<div class="loader">'.$image.'<br\><br\>Generating form.<br\> Please wait...</div>\'
					);
             }',
			 'error'=>"function(request, status, error){
				 	$('#dialogAnalysis').html(status+'('+error+')'+': '+ request.responseText );
					}",
			
            ))?>;
    return false; 
}

function updateAnalysis(id)
{
	<?php 
	echo CHtml::ajax(array(
			'url'=>$this->createUrl('quotation/updatetest'),
			'data'=> "js:$(this).serialize()+ '&id='+id",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogAnalysis').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogAnalysis form').submit(updateAnalysis);
                }
                else
                {
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogAnalysis').html(data.div);
                    setTimeout(\"$('#dialogAnalysis').dialog('close') \",1000);
                }
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogAnalysis").html(
						\'<div class="loader">'.$image.'<br\><br\>Retrieving record.<br\> Please wait...</div>\'
					);
            }',
			 'error'=>"function(request, status, error){
				 	$('#dialogAnalysis').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
					}",
            ))?>;
    return false; 
}

function addPackage()
{
    <?php echo CHtml::ajax(array(
			'url'=>$this->createUrl('quotation/package',array('id'=>$model->id)),
			'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogPackage').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogPackage form').submit(addPackage);
                }
                else
                {
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogPackage').html(data.div);
                    setTimeout(\"$('#dialogPackage').dialog('close') \",1000);
					
                }
 
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogPackage").html(
						\'<div class="loader">'.$image.'<br\><br\>Generating form.<br\> Please wait...</div>\'
					);
             }',
			 'error'=>"function(request, status, error){
				 	$('#dialogPackage').html(status+'('+error+')'+': '+ request.responseText );
					}",
			
            ))?>;
    return false; 
}

function addOnsiteCharges(){
	<?php echo CHtml::ajax(array(
			'url'=>$this->createUrl('quotation/onsitecharge',array('id'=>$model->id)),
			'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data){
                if (data.status == 'failure'){
                    $('#dialogOnsite').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogOnsite form').submit(addOnsiteCharges);
                }
                else{
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogOnsite').html(data.div);
                    setTimeout(\"$('#dialogOnsite').dialog('close') \",1000);
					
                }
 
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogOnsite").html(
						\'<div class="loader">'.$image.'<br\><br\>Generating form.<br\> Please wait...</div>\'
					);
         	}',
		 	'error'=>"function(request, status, error){
				 	$('#dialogOnsite').html(request.responseText );
					console.log(request);
			}",
            ))?>;
	return false;
}
function addDiscount(){
	<?php echo CHtml::ajax(array(
			'url'=>$this->createUrl('quotation/discount',array('id'=>$model->id)),
			'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data){
                if (data.status == 'failure'){
                    $('#dialogDiscountl').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogDiscount form').submit(addDiscount);
                }
                else{
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogDiscount').html(data.div);
                    setTimeout(\"$('#dialogDiscount').dialog('close') \",1000);
					
                }
 
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogDiscount").html(
						\'<div class="loader">'.$image.'<br\><br\>Generating form.<br\> Please wait...</div>\'
					);
         	}',
		 	'error'=>"function(request, status, error){
				 	$('#dialogDiscount').html(request.responseText );
					console.log(request);
			}",
            ))?>;
	return false;
}

</script>	