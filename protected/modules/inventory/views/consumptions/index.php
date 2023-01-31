<?php
/* @var $this ConsumptionsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Consumptions',
);

$this->menu=array(
	array('label'=>'Create Consumptions', 'url'=>array('create')),
	array('label'=>'Manage Consumptions', 'url'=>array('admin')),
);
?>



<div class="row-fluid">
  <div class="span3">
       <?php
          $this->beginWidget('zii.widgets.CPortlet', array(
              'title'=>"Withdraw something?",
          ));

          //get current month
          $month = date('m');
      ?>
          <div class="form">
            <?php $form=$this->beginWidget('CActiveForm', array(
		        'id'=>'order-form',
		        'enableClientValidation'=>true,
		        'clientOptions'=>array(
		            'validateOnSubmit'=>true,
		        ),
		    ));  ?>
		     <p class="note">Fields with <span class="required">*</span> are required.</p>
            <?php echo $form->errorSummary($ordermodel); ?>
            <div class="row">
	            <?php echo $form->labelEx($ordermodel,'Item'); ?>
              <?php echo $form->textField($ordermodel,'Item',array('style'=>'width:150px;')); ?>
	            <?php echo $form->error($ordermodel,'Item'); ?>
	          </div>

	           <div class="row">
	            <?php echo $form->labelEx($ordermodel,'Quantity'); ?>
	            <?php echo $form->textField($ordermodel,'Quantity',array('style'=>'width:150px;')); ?>
	            <?php echo $form->error($ordermodel,'Quantity'); ?>
	          </div>

             
	           <div class="row buttons">
              <?php
              echo CHtml::submitButton(
                  'Add to Cart',
                  array(
                      'class'=>'btn btn-success',
                  )
              );
              ?>
              </div>
               <?php $this->endWidget(); ?>
           </div><!-- form -->

              <?php $this->endWidget();
                echo CHtml::link('Destroy order',$this->createUrl('/inventory/Consumptions/destroy'),array('class'=>'btn btn-warning btn-small text-center'));

              ?>

  </div><!--/span-->

  <div class="span9" id="orderview">
  <?php
      foreach(Yii::app()->user->getFlashes() as $key => $message) {
          echo '<div class="alert alert-'.$key.'">' . $message . "</div>\n";
      }
  ?>


  <div class="row-fluid">
    <?php echo CHtml::label("TOTAL",array('class'=>'pull-left')); ?>
     <?php echo CHtml::textField("TOTAL",$total,array('id'=>'txttotal','class'=>'pull-left','style'=>'width:500px;height:auto;font:arial;font-weight:bolder;font-size:100px; text-align: right;','readonly'=>true)); ?>
    <?php
    if($unserialize!=null){
      echo CHtml::link('CHECK-OUT','#',array('class'=>'btn btn-success btn-large text-center pull-right','onclick'=>'$("#mydialog").dialog("open");'));
    }
    ?>
  </div>
  <br />
  <div>
  <?php 


	$this->widget('zii.widgets.grid.CGridView', array(
	 	'id'=>'grid-import',
	 	'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
	 	'htmlOptions'=>array('class'=>''),
	 	'dataProvider'=>$importDataProvider,//$importDataProvider,
	 	'columns'=>array(
      'Item',
      'Name',
      'Quantity',
      'Cost',
      'Subtotal',
      array(
                'header'=>'',
                'class'=>'CButtonColumn',
                'template' => '{remove}',
                'htmlOptions' => array('style'=>'width:50px'),
                'buttons'=>array(
                        
                        'remove' => array(

                                'label'=>'<i class="view icon icon-trash"></i>',
                                //'imageUrl'=>'images/icn/status.png',  // make sure you have an image
                                'url'=>'Yii::app()->createUrl("/inventory/consumptions/deleteitem",array("item"=>$data["Item"]))',
                                'options' => array(
                                'title'=>'Remove',
                                'class'=>'btn btn-danger btn-small',
                                'confirm'=>'Are you sure you wish to delete this item?',
                                //'onclick'=>'alert("'.$data['item'].'");',
                                'ajax' => array(
                                    'type' => 'POST',
                                    'url'=>'js:$(this).attr("href")',
                                    'success' => 'js:function(data) {
                                        $.fn.yiiGridView.update("grid-import");
                                        $("#txttotal").val(data);
                                     }',
                                     )

                                ),

                         ),

                    ),            
            ),
      // 'keyField'
	 		//'ID',
	 		//'stockCode',
	 		// 'supplyID',
	 		// array(
	 		// 	'name'=>'supplierID',
	 		// 	'value'=>$data->supplierID,
	 		// 	),
	 	),
	 )); 

	?>
  </div>
  
     
  </div>
</div>



<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'mydialog',
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'Withdraw Item/s',
        'autoOpen'=>false,
        'show'=>'scale',
        'hide'=>'scale',                
        'width'=>'300',
        'min-width'=>500,
        'modal'=>true,
        'height'=>'auto',
        'resizable'=>false,
    ),
));
?>
<h1>Are you sure?</h1>
<div class="row-fluid">


<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'CheckOut-Form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
  ));  ?>
    <?php //echo $form->errorSummary($CheckOutForm); ?>
      

     <div class="row">
      <?php echo $form->labelEx($CheckOutForm,'user_id'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
                 'model'=>$CheckOutForm,
                 'attribute' =>'user_id',
                 'data'=>CHtml::listData(Users::model()->findAll(),'id','username'),
                 'options'  => array(
                     'placeholder'=>'Select Officer',
                     'width'=>'auto',
                 ),
          ));

      ?>
      <?php echo $form->error($CheckOutForm,'user_id'); ?>
    
      <?php
      echo CHtml::submitButton(
          'It\'s me',
          array(
              'class'=>'btn btn-warning pull-right',
          )
      );
      ?>
      </div>
       <?php $this->endWidget(); ?>
   </div><!-- form -->



</div>

<?php
 $this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<script type="text/javascript">
  $(window).ready(function(){

    $("#OrderForm_Item").focus();

  });
</script>