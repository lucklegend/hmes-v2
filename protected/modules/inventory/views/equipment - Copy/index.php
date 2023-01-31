<?php
/* @var $this EquipmentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Equipments',
);

$this->menu=array(
	array('label'=>'Create Equipment', 'url'=>array('create')),
	array('label'=>'Manage Equipment', 'url'=>array('admin')),
);
?>

<h1>Equipments</h1>

<div class="row-fluid">
	<div class="span4">
		<?php $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_view',
		)); ?>
	</div>
	<div class="span8">
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
		    'title'=>"<i class='icon-wrench'></i><strong>Equipment Maintenance</strong>",
				),array('class'=>'portletbold '));
				  $this->widget('zii.widgets.grid.CGridView', array(
							      'dataProvider'=>$maintenance,
							      'id'=>'maintenance-grid',
							      'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
							      'rowCssClassExpression'=>'$data->color',
							      'columns'=>array(
							      	'equipmentID',
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
							  //       array(
									// 	'name'=>'Maintenance Data',
									// 	'type'=>'raw',
									// 	'value'=>function($data){
									// 		return  CHtml::link(
									// 			    'Import/View',
									// 			    Yii::app()->createUrl("/equipment/equipmentmaintenance/view",array('id'=>"$data->ID")),
												    
									// 			    array(
									// 			        'class'=>'btn btn-default btn-small',
									// 			        'id'=>'btmrefreshreq',
									// 			    )
									// 			);
									// 	},
									// 	'htmlOptions'=>array('style' => 'width: 120px; text-align: center;'),
									// ),

							      ),
							    )); 
			$this->endWidget();

			$this->beginWidget('zii.widgets.CPortlet', array(
			    'title'=>"<i class='icon-wrench'></i><strong>Equipment Calibration</strong>",
					),array('class'=>'portletbold '));

			$this->widget('zii.widgets.grid.CGridView', array(
						      'dataProvider'=>$calibration,
						      'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
						      'id'=>'calibration-grid',
						      'rowCssClassExpression'=>'$data->color',
						      'columns'=>array(
						      	'equipmentID',
						        'date',
						        array(
						        	"header"=>"Status",
						        	'name'=>"isdone",
						        	'value'=> function($model){
						        		echo $model->getstatus();
						        	}
					        	),
					   //      	array(
								// 	'name'=>'Calibration Data',
								// 	'type'=>'raw',
								// 	'value'=>function($data){
								// 		return  CHtml::link(
								// 			    'Import/View',
								// 			    Yii::app()->createUrl("/equipment/equipmentcalibration/view",array('id'=>"$data->ID")),
											    
								// 			    array(
								// 			        'class'=>'btn btn-default btn-small',
								// 			        'id'=>'btmrefreshreq',
								// 			    )
								// 			);
								// 	},
								// 	'htmlOptions'=>array('style' => 'width: 120px; text-align: center;'),
								// ),
							),
				       ));

			$this->endWidget();
		 ?>




	</div>
</div>