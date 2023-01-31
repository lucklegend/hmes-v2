<?php
/* @var $this RequestController */
/* @var $model Request */

$this->breadcrumbs=array(
	'Requests'=>array('index'),
	'Manage',
);

$this->menu=array(
	//array('label'=>'Create Order of Payment', 'url'=>'#', 'onClick'=>'js:{createOPFromRequests(); $("#dialogCreateOPFromRequests").dialog("open");}',),
	//array('label'=>'Create Order of Payment', 'url'=>'', 'linkOptions'=>array('onclick'=>'javascript:{ createOPFromRequests(); $("#dialogCreateOPFromRequests").dialog("open");}','style' => 'cursor:pointer;')),
	array('label'=>'List Request', 'url'=>array('index')),
	array('label'=>'Create Request', 'url'=>array('create')),
);

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

<h1>Manage Requests</h1>

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
	'id'=>'request-grid',
	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	'rowHtmlOptionsExpression' => 'array("class"=>$data->status["class"])',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		'requestRefNum',
		array(
				'name'=>'requestDate',
				'value'=>'date("Y-m-d", strtotime($data->requestDate))',
    			'htmlOptions' => array('style' => 'width: 75px; text-align: right; padding-right: 25px;'),
			),
		array( 
				'name'=>'customer_search', 
				'value'=>'$data->customer->customerName',
				'htmlOptions' => array('style' => 'width: 300px; text-align: left; ')
		),
		array( 
				'name'=>'orId', 
				'value'=>'Request::getORsAdminView($data->receipts)',
				'type'=>'raw',
				'htmlOptions' => array('style' => 'text-align: right; padding-right: 25px;')
		),
		array(
				'name'=>'total',
				'value'=>'Yii::app()->format->formatNumber($data->total)',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'text-align: right; padding-right: 25px;'),
			),
		array(
				'name'=>'reportDue',
    			'htmlOptions' => array('style' => 'text-align: right; padding-right: 25px;')
			),
		array(
			'name'=>'paymentStatus',
			'type'=>'raw',
			'filter'=>false,
			'value' => function($data){
				$paymentStatus = $data["paymentStatus"];
				
				return CHtml::link(
					'<span class="'.$paymentStatus['class'].'">'.$paymentStatus['label'].'</span>',
					'#',
					array(
						'id'=>$data['id'],
						'onclick'=>"js:{ viewPaymentDetail({$data['id']}); $('#dialogPaymentDetails').dialog('open');}",
					)
				);
			},
			'htmlOptions'=>array('style'=>'text-align:center','title'=>'Click to view payment details'),
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{view} {update}'
		),
	),
	'selectableRows'=>1,
	//'selectionChanged'=>'function(id){location.href = "'.$this->createUrl('request/view/id').'/"+$.fn.yiiGridView.getSelection(id);}',
)); ?>

<!-- CreateOP Dialog : Start -->
<?php
	/*$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogCreateOPFromRequests',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'OP from Requests',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>960,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));

	$this->endWidget('zii.widgets.jui.CJuiDialog');*/
?>
<!-- CreateOP Dialog : End -->

<!-- script type="text/javascript">
function createOPFromRequests()
{
	
    <?php /*echo CHtml::ajax(array(
			'url'=>$this->createUrl('/accounting/orderofpayment/createOPFromRequests'),
			'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogCreateOPFromRequests').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogCreateOPFromRequests form').submit(createOPFromRequests);
                }
                else
                {
					$('#dialogCreateOPFromRequests').html(data.div);
					setTimeout(\"$('#dialogReceiptFromOP').dialog('close') \",1000);
                }
 
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#dialogCreateOPFromRequests").html(
						\'<div class="loader">'.$image.'<br\><br\>Processing...<br\> Please wait...</div>\'
					);
             }',
			 'error'=>"function(request, status, error){
				 	$('#dialogCreateOPFromRequests').html(status+'('+error+')'+': '+ request.responseText );
					}",
			
            ))*/?>;
    return false; 
}
</script-->
<?php
	/*$file_path = "F:\Unified LMS\Customers\IVA-L1_OneLab.csv";
	//$file_path = "F:\Unified LMS\Customers\ZAMBOANGA_DEL_SUR-CARIDAD-RAIN2-20140810.csv";
	$file = fopen($file_path,"r");
	
	echo "<table>";
	while(! feof($file))
	  {
	  //print_r(fgetcsv($file))."<br/>";
	  $row = fgetcsv($file);
	  echo "<tr>";
	  	for($i=0; $i<=11;$i++){
	  		echo "<td>".$row[$i]."</td>";	
	  	}
	  	
	  echo "</tr>";
	  }
	  
	echo "</table>";
	fclose($file);*/
?>

