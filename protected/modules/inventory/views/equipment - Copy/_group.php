<?php
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
echo "<h3>Equipment : $equipment->equipmentID $equipment->name</h3>";
//sample
// $models=Animals::model()->findAll(array(
//     'select'=>'t.Genus, t.Id',
//     'distinct'=>true,
// ));
// $this->widget('ext.select2.ESelect2',array(
//            'model'=>Profile::model(),
//            'attribute' =>'user_id',
//            'data'=>CHtml::listData(
//                Equipment::model()->findAll(array('select'=>'t.tags, t.ID','distinct'=>true,)), 'tags', 'tags'),
//            'options'  => array(
//                'placeholder'=>'Select Employee',
//                'width'=>'200px',
             
	             
//            ),
//     ));



if($equipment->tags!=""){
	//$equipments = Equipment::model()->findAllByAttributes(array('tags'=>$equipment->tags));
	$equipments=new CActiveDataProvider('Equipment', array(
			'criteria'=>array(
		        'condition'=>'tags="'.$equipment->tags.'" AND ID!='.$equipment->ID.'',
		    ),
		    'pagination'=>false,
		));

	echo "<h4>Similar Tag : '".$equipment->tags."'</h4>";
	
}else{
	?>
	<div id="res" style="color:red"></div>
	<div class="form">
			<?php echo CHtml::beginForm(); ?>
			<div class="row">
				<?php echo CHtml::label('Tag Code<small style="color:red;">incase the equipment is bid in group</small>', 'tag'); ?>
				<?php echo CHtml::hiddenField('equipmentID',$equipment->equipmentID); ?>
				<?php echo CHtml::textField('tag',"",array('required'=>true)); ?>
			</div>

			<?php
			echo CHtml::ajaxSubmitButton(
			    'TAG',
			    "",
			    array(
			        //'update'=>'#xyz',
			        'success'=>'js:function(data){
			        		if(data==="success"){
			        			 $("#mydialog").dialog("close");
			        			
			        		}else{
			        			$("#res").html(data);
			        		}
		  			 }',
			    ),
			    array(
			        'class'=>'btn btn-warning',
			        'id'=>'btntag',
			         'confirm'=>'Are you sure you want to put this equipment in group? grouping with existing tag will make this amount empty',
			    )
			);

			?>

			 <?php echo CHtml::endForm(); ?>
		</div><!-- form -->
	<?php
	$equipments=new CActiveDataProvider('Equipment', array(
			'criteria'=>array(
		       'condition'=>'tags!=""',
		        'select'=>'t.tags,equipmentID,name',
		        'group'=>'t.tags',
		        'distinct'=>true
		    ),
		    'pagination'=>false,
		));

	echo "<h4>Tags to select</h4>";
}

 $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$equipments,
    'id'=>'sametag-grid',
    'columns'=>array(
    	// 'ID',
        'equipmentID',
        'name',
        'tags',
        'amount'
    ),
)); 

Yii::app()->clientScript->registerScript('clkrowgrid', "
			$('#sametag-grid table tbody tr').click(function()
       {
           

            var firstColVal= $(this).find('td:first-child').text();
            var secondColVal= $(this).find('td:nth-child(3)').text();
            // var lastColVal= $(this).find('td:last-child').text();
            $('#tag').val(secondColVal);
          
       });
			");

?>