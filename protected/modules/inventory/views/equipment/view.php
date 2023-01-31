<?php
/* @var $this EquipmentController */
/* @var $model Equipment */

$this->breadcrumbs=array(
	'Equipments'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Equipment', 'url'=>array('index')),
	array('label'=>'Create Equipment', 'url'=>array('create')),
	array('label'=>'Update Equipment', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Equipment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Equipment', 'url'=>array('admin')),
);
?>

<h1>View Equipment #<?php echo $model->ID; ?></h1>

<?php
 $this->beginWidget('zii.widgets.CPortlet', array(
    'title'=>"<i class='icon-wrench'></i><strong>Equipment # $model->ID</strong>",
		),array('class'=>'portletbold announcewindow'));?>
<div class="row-fluid">
<div class="span4">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		// 'ID',
		'equipmentID',
		'name',
		'description',
		array(
			'name'=>'lab',
			'value'=>$model->labaccess->labName,
			),
		'specification',
		'date_received',
		'date_purchased',
		'are',
		'received_by',
		'amount',
		//'supplier',
		array(
			'name'=>'supplier',
			'value'=>$model->suppliers->name,
			),
		array(
			'name'=>'classificationID',
			'value'=>$model->classification->name,
			),
		array(
			'name'=>'status',
			'value'=>$model->equipstatus->name,
			),
		//'lengthofuse',
		'remarks',
		'brand',
		'model',
		'serialno',
		array(
			'name'=>'Depreciated Cost',
			'type'=>'raw',
			'value'=> function($data){
					$cost=$data['depreciation'];
					return $cost ;
				},
			'htmlOptions'=>array('style'=>'text-align:center'),
		),

	),
));
?>
</div>


<div class="span8">
	<div class="row-fluid pull-right">
		<?php if($model->image!=''){ ?>
	    <?php echo CHtml::image(Yii::app()->request->baseUrl.'/equipment_uploads/pics/'.$model->image,"image",array("width"=>300,'height'=>200));   // Image shown here if page is update page ?>
		<?php } ?>
		<?php if($model->image2!=''){ ?>
	    <?php echo CHtml::image(Yii::app()->request->baseUrl.'/equipment_uploads/pics/'.$model->image2,"image2",array("width"=>300,'height'=>200));   // Image shown here if page is update page ?>
		<?php } ?>
	</div>
</div>

</div>
<?php $this->endWidget(); ?>

<?php
echo CHtml::link('<span class="icon-bin icon-white"></span> Set Usage Schedule', Yii::app()->createUrl('inventory/equipmentusage/create',array('item'=>$model->equipmentID,'id'=>$model->ID)), array('class'=>'btn btn-success', 'style'=>'margin: 0.2em 0 0.5em 0; float:right;','title'=>'Set schedule'));
 $this->beginWidget('zii.widgets.CPortlet', array(
    'title'=>"<i class='icon-wrench'></i><strong>Equipment Usages</strong>",
		),array('class'=>'portletbold '));

  $this->widget('zii.widgets.grid.CGridView', array(
			      'dataProvider'=>$usage,
			      'id'=>'usage-grid',
			     // 'rowCssClassExpression'=>'$data->color',
			      'columns'=>array(
			        // 'status',
			        'startdate',
			        'enddate',
			        'remarks',
			      ),
			    )); 
$this->endWidget();

echo CHtml::link('<span class="icon-bin icon-white"></span> Set Maintenance Schedule', Yii::app()->createUrl('inventory/equipmentmaintenance/create',array('item'=>$model->equipmentID,'id'=>$model->ID)), array('class'=>'btn btn-success', 'style'=>'margin: 0.2em 0 0.5em 0; float:right;','title'=>'Set schedule'));
 $this->beginWidget('zii.widgets.CPortlet', array(
    'title'=>"<i class='icon-wrench'></i><strong>Equipment Maintenance</strong>",
		),array('class'=>'portletbold '));

  $this->widget('zii.widgets.grid.CGridView', array(
			      'dataProvider'=>$maintenance,
			      'id'=>'maintenance-grid',
			      'rowCssClassExpression'=>'$data->color',
			      'columns'=>array(
			        'date',
			        //'type',
			        array(
			        	'name'=>"type",
			        	'value'=> function($model){
			        		switch($model->type){
			        			case "0":
			        				echo "Standard Maintenance";
			        			break;
			        			case "1":
			        				echo "Preventive / Corrective Maintenance";
			        			break;
			        		}
			        	}
			        	),
			        array(
			        	"header"=>"Status",
			        	'name'=>"isdone",
			        	'value'=> function($model){
			        		switch($model->isdone){
			        			case "0":
			        				if(date('Y-m-d') > $model->date)
					                	echo "Over Due";
					                else
					                	echo "Not yet";
			        			break;
			        			case "1":
			        				echo "Done";
			        			break;
			        		}
			        	}
			        	),

			        array(
						'name'=>'Maintenance Data',
						'type'=>'raw',
						'value'=>function($data){
							return  CHtml::link(
								    'Import/View',
								    Yii::app()->createUrl("/inventory/equipmentmaintenance/update",array('id'=>"$data->ID")),
								    
								    array(
								        'class'=>'btn btn-default btn-small',
								        'id'=>'btmrefreshreq',
								    )
								);
						},
						'htmlOptions'=>array('style' => 'width: 120px; text-align: center;'),
					),

			      ),
			    )); 
$this->endWidget();



echo CHtml::link('<span class="icon-bin icon-white"></span> Set Calibration Schedule', Yii::app()->createUrl('inventory/equipmentcalibration/create',array('item'=>$model->equipmentID)), array('class'=>'btn btn-success', 'style'=>'margin: 0.2em 0 0.5em 0; float:right;','title'=>'Set schedule'));
 $this->beginWidget('zii.widgets.CPortlet', array(
    'title'=>"<i class='icon-wrench'></i><strong>Equipment Calibration</strong>",
		),array('class'=>'portletbold '));

$this->widget('zii.widgets.grid.CGridView', array(
			      'dataProvider'=>$calibration,
			      'id'=>'calibration-grid',
			      'rowCssClassExpression'=>'$data->color',
			      'columns'=>array(
			        'date',
			        array(
			        	"header"=>"Status",
			        	'name'=>"isdone",
			        	'value'=> function($model){
			        		echo $model->getstatus();
			        	}
		        	),
		        	array(
						'name'=>'Calibration Data',
						'type'=>'raw',
						'value'=>function($data){
							return  CHtml::link(
								    'Import/View',
								    Yii::app()->createUrl("/inventory/equipmentcalibration/update",array('id'=>"$data->ID")),
								    
								    array(
								        'class'=>'btn btn-default btn-small',
								        'id'=>'btmrefreshreq',
								    )
								);
						},
						'htmlOptions'=>array('style' => 'width: 120px; text-align: center;'),
					),
				),
	       ));

$this->endWidget();
?> 


<!--import maintenance dialogue-->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogImportDetail',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Import Maintenance Data',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>1200,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));
?>
	
	<div class="form">
		<?php $image=Yii::app()->baseUrl.('/images/ajax-loader.gif');?>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'htmlOptions' => array('enctype' => 'multipart/form-data'),
		)); ?>
	
	 	<div class="row-fluid">
			<?php echo CHtml::label("sample","",array("id"=>"thelabel","size"=>300,"height"=>"250px")); ?>
			<label>Data:</label>
			<pre id="thelabeldata"></pre>
			<?php echo CHtml::hiddenField("theid","",array("id"=>"theid")); ?>
		</div> 
		<div class="row">
			<?php echo CHtml::fileField('import_path',''); ?>
	        <?php echo CHtml::submitButton('Import File', array('class'=>'btn btn-info')); ?>
	    </div>
	    <?php $this->endWidget(); ?>
	</div>
	

<?php $this->endWidget(); ?>


<!--import calibration dialogue-->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogImportDetail2',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Import Calibration Data',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>500,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));
?>
	<div class="form">
		<?php $image=Yii::app()->baseUrl.('/images/ajax-loader.gif');?>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'htmlOptions' => array('enctype' => 'multipart/form-data'),
		)); ?>
	 	<div class="row-fluid">
			<?php echo CHtml::label("sample","",array("id"=>"thelabel2","size"=>300,"height"=>"250px")); ?>
			<label>Data:</label>
			<pre id="thelabeldata2"></pre>
			<?php echo CHtml::hiddenField("theid","",array("id"=>"theid2")); ?>
		</div> 
		<div class="row">
			<?php echo CHtml::fileField('import_path2',''); ?>
	        <?php echo CHtml::submitButton('Import File', array('class'=>'btn btn-info')); ?>
	    </div>
	    <?php $this->endWidget(); ?>
	</div>

<?php
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>







<script>
function viewMaintenanceDetail(id)
{
	<?php 
	echo CHtml::ajax(array(
			'url'=>$this->createUrl('equipment/viewMaintenanceRecord'),
			//'data'=> new FormData(this),
			'data'=> "js:$(this).serialize()+ '&id='+id",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
            		$('#thelabel').text(data['title']); 
            		$('#thelabeldata').html(data['data']);
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#thelabeldata").html(
						\'<div class="loader">'.$image.'<br\><br\>Retrieving record.<br\> Please wait...</div>\'
					);
            }',
			 'error'=>"function(request, status, error){
				 	$('#thelabeldata').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
					}",
            ))?>;
    return false; 
}


function viewCalibrationDetail(id)
{
	<?php 
	echo CHtml::ajax(array(
			'url'=>$this->createUrl('equipment/viewCalibrationRecord'),
			//'data'=> new FormData(this),
			'data'=> "js:$(this).serialize()+ '&id='+id",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
            		$('#thelabel2').text(data['title']); 
            		$('#thelabeldata2').html(data['data']);
            }",
			'beforeSend'=>'function(jqXHR, settings){
                    $("#thelabeldata2").html(
						\'<div class="loader">'.$image.'<br\><br\>Retrieving record.<br\> Please wait...</div>\'
					);
            }',
			 'error'=>"function(request, status, error){
				 	$('#thelabeldata2').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
					}",
            ))?>;
    return false; 
}
</script>



