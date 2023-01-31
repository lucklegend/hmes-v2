<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1>Quotation View</h1>

<div class="searchQuotation" style="margin:15x; width: 480px; vertical-align:middle; " >

<?php $getQuotationButton = CHtml::link('<span class="icon-white icon-search"></span> Search Quotation', '', array( 
			'style'=>'cursor:pointer;',
			'class'=>'btn btn-success',
			'ajax'=>array( 
						'type'=>'POST',
				 		'url'=>$this->createUrl('quotation/getQuotation'),
				 		'update'=>'#quotation-result',
				    ),
			// 'onClick'=>'js:{
			// 			//alert("hahahahhaha");
			// 			getQuotation("QT-042016-b5cf8"); 
			// 	}',
			));
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'search-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<table style="margin: 10px;">
	<tr>
		<td style="padding-top: 10px;padding-right: 3px;"><?php echo Chtml::textfield('quotationCode','QT-042016-b5cf8', array('style'=>'height:20px; width: 275px;')); ?></td>
		<td><?php echo $getQuotationButton; ?></td>
	</tr>
</table>

<?php $this->endWidget(); ?>
</div>

<div id="quotation-result">

<script type="text/javascript">
function getQuotation()
{
	<?php
	echo CHtml::ajax(array(
			'url'=>$this->createUrl('quotation/getQuotation'),
			'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
            	//$.fn.yiiGridView.update('lab-service-grid');
            }",
			 'error'=>"function(request, status, error){
				 	$('#dialogSampleCode').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
					}",
            ))?>;
    return false;
}
</script>
