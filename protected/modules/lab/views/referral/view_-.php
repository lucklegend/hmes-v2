<div style="position:relative">
<?php
/* @var $this RequestController */
/* @var $model Request */

//if($model->cancelled)
	//$this->renderPartial('_cancelled',array('model'=>$model->cancelDetails));

$this->menu=array(
	array('label'=>'Create Request', 'url'=>array('create')),
	//array('label'=>'Update Request', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Request', 'url'=>array('admin')),
);  
$image = CHtml::Image(Yii::app()->theme->baseUrl . '/img/page_white_excel.png', 'Print');
?>

<h1>View Referral: <?php echo $referral['referralCode']; ?></h1>
<?php $this->widget('ext.widgets.DetailView4Col', array(
	'cssFile'=>false,
	'htmlOptions'=>array('class'=>'detail-view table table-striped table-condensed'),
	'data'=>$referral,
	'attributes'=>array(
		array(
            'name'=>'referralDetails',
            'oneRow'=>true,
			'cssClass'=>'title-row',
            'type'=>'raw',
            'value'=>'',
        ),	
		'referralCode', 
        array(
            'name' => 'customerName',
            'type'=>'raw',
            'value' => $referral["customer"]["customerName"]
        ),
		'referralDate', 
        array(
            'name' => 'address',
            'type'=>'raw',
            'value' => $referral["customer"]["houseNumber"]
        ),
		'referralTime',
        array(
            'name' => 'tel',
            'type'=>'raw',
            'value' => $referral["customer"]["tel"]
        ),
        
		'reportDue', 
        array(
            'name' => 'fax',
            'type'=>'raw',
            'value' => $referral["customer"]["fax"]
        ),
		array(
            'name'=>'paymentDetails',
			'cssClass'=>'title-row',
            'oneRow'=>true,
            'type'=>'raw',
            'value'=>'',
        ),
        'receivedBy',
		'conforme',
		
	),
));
?>


<?php
	$this->beginWidget('zii.widgets.CPortlet', array(
		'title'=>"<b>Test / Calibration Service(s)</b>",
	));

?>

<h4 style="margin-bottom: -10px;">Samples</h4> 

<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
    	'id'=>'sample-grid',
	    'summaryText'=>false,
		'emptyText'=>'No samples.',
		'htmlOptions'=>array('class'=>'grid-view paddingLeftRight10'),
        'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
		'rowHtmlOptionsExpression' => 'array("title" => "Click to update", "class"=>"link-hand")', 
        //It is important to note, that if the Table/Model Primary Key is not "id" you have to
        //define the CArrayDataProvider's "keyField" with the Primary Key label of that Table/Model.
        'dataProvider' => $sampleDataProvider,
        'columns' => array(
            array(
				'name'=>'sampleCode',
				'header'=>'Sample Code',
				//'value'=>'CHtml::encode($data["sampleName"])',
				'htmlOptions' => array('style' => 'width: 200px; text-align: center; font-weight: bold;'),
			),
			array(
				'name'=>'sampleName',
				'header'=>'Sample Name',
				//'value'=>'CHtml::encode($data["sampleName"])',
				'htmlOptions' => array('style' => 'width: 200px; padding-left: 15px; text-align: left; font-weight: bold;'),
			),
    		array(
				'name'=>'description',
				'header'=>'Description',
				'type'=>'raw',
				//'value'=>'CHtml::encode($data["description"])'
			),
        )
    ));

   
?>

<?php
 // echo CHtml::link('<span class="icon-white icon-print"></span> Print Label', $this->createUrl('referral/printBarcode', array('id'=>20)), array('class'=>'btn btn-success', 'target'=>'_blank'));
?>
<?php 
    $this->widget('zii.widgets.grid.CGridView', array(
    	'id'=>'analysis-grid',
	    'summaryText'=>false,
		'emptyText'=>'No analyses.',
		'htmlOptions'=>array('class'=>'grid-view paddingLeftRight10'),
		'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
		'rowHtmlOptionsExpression' => 'array("title" => "Click to update", "class"=>"link-hand")', 
        //It is important to note, that if the Table/Model Primary Key is not "id" you have to
        //define the CArrayDataProvider's "keyField" with the Primary Key label of that Table/Model.
        'dataProvider' => $analysisDataProvider,
        'columns' => array(
    		array(
				'name'=>'SAMPLE',
				'value'=>'$data["sample"]["sampleName"]',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 100px; padding-left: 10px;'),
			),
           	//'sampleCode',
			array(
				'name'=>'SAMPLE CODE',
				'value'=>'$data["sample"]["sampleCode"]',
				'type'=>'raw',
				'htmlOptions' => array('style' => 'width: 100px; text-align: center;'),
			),
    		//'testName',
			array(
				'name'=>'TEST / CALIBRATION REQUESTED',
				//'value'=>'($data->package == 1) ? "&nbsp;&nbsp;".$data->testName : $data->testName',
                'value'=>'$data["testname"]["testName"]',
				'type'=>'raw',
				'htmlOptions' => array('style' => 'padding-left: 10px;'),
			),
    		//'method',
    		array(
				'name'=>'TEST METHOD',
				'value'=>'$data["methodreference"]["method"]',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'padding-left: 10px;'),
			),
    		//'quantity',
    		array(
				'name'=>'QTY',
				//'value'=>'($data->package == 1) ? "-" : $data->quantity',
                'value'=>'1',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
    			'footer'=>'SUBTOTAL<br/>DISCOUNT<br/><b>TOTAL</b>',
    			'footerHtmlOptions'=>array('style'=>'text-align: right; padding-right: 10px;'),
			),
    		//'fee'
    		array(
				'name'=>'UNIT PRICE',
				'value'=>'Yii::app()->format->formatNumber($data["fee"] )',
				
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 65px; text-align: right; padding-right: 10px;'),
    			/*'footer'=>
    					Yii::app()->format->formatNumber($model->getTestTotal($analysisDataProvider->getKeys())).
    					'<br/>'.
    					Yii::app()->format->formatNumber($model->getDiscount($analysisDataProvider->getKeys(), $model->discount_id)).
    					'<br/><b>'.
    					Yii::app()->format->formatNumber($model->getReferralTotal($analysisDataProvider->getKeys(), $model->discount)).
    					'</b>'
    					,
    			'footerHtmlOptions'=>array('style'=>'text-align: right; padding-right: 10px;'),*/
			),
        ),
    ));

    echo Chtml::link('<span class="icon-white icon-list"></span> Generate Sample Code', '', array(
			'id'=>'cancel-button',
			'title'=>'Generate Sample Code',
			'class'=>'btn btn-success',
			'style'=>'display:'.($generated ? 'none;' : ';'),
			"onclick"=>"if (!confirm('Do you really want to GENERATE Sample Codes with the current number of samples?')){return}else{ generateSampleCode(); $(this).prop('onclick',null); $('#dialogSampleCode').dialog('open'); }",	
			)); 
?>
<?php $this->endWidget(); //End Portlet ?>
<!-- SampleCode Dialog : Start -->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogSampleCode',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Generate Sample Code',
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
<!-- SampleCode Dialog : End -->

<script>
function generateSampleCode()
{
	<?php
	echo CHtml::ajax(array(
			'url'=>$this->createUrl('sample/generateSampleCodeReferral', array('id'=>$_GET['id'])),
			'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogSampleCode').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    //$('#dialogSampleCode form').submit(generateSampleCode);
                }
                else
                {
                    $.fn.yiiGridView.update('sample-grid');
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogSampleCode').html(data.div);
                    //setTimeout(\"$('#dialogSampleCode').dialog('close') \",1000);
					location.reload();
                }
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogSampleCode").html(
						\'<div class="loader">'.$image.'<br\><br\>Processing.<br\> Please wait...</div>\'
					);
            }',
			 'error'=>"function(request, status, error){
				 	$('#dialogSampleCode').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
					}",
            ))?>;
    return false;
}

function confirmGenerateSampleCode()
{
	<?php
			/*echo CHtml::ajax(array(
					'url'=>$this->createUrl('sample/confirm'),
		            'type'=>'post',
		            'dataType'=>'json',
		            'success'=>"function(data)
		            {
		                if (data.status == 'failure')
		                {
		                    $('#dialogConfirmGenerate').html(data.div);
		                    // Here is the trick: on submit-> once again this function!
		                    $('#dialogConfirmGenerate form').submit(confirmGenerateSampleCode);
		                }
		                else
		                {
							$('#dialogConfirmGenerate').html(data.div);
		                    setTimeout(\"$('#dialogConfirmGenerate').dialog('close') \",1000);
		                }
		            }",
					'beforeSend'=>'function(jqXHR, settings){
		                    $("#dialogConfirmGenerate").html(
								\'<div class="loader">'.$image.'<br\><br\>Retrieving record.<br\> Please wait...</div>\'
							);
		            }',
					 'error'=>"function(request, status, error){
						 	$('#dialogConfirmGenerate').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
							}",
		            ))*/?>;
		    //return false; 
}    
</script>