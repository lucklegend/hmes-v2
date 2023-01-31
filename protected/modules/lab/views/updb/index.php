<?php
/* @var $this UpdbController */


class Tblform extends CFormModel
{
    public $name;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            // name, email, subject and body are required
            array('name', 'required'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'name'=>'Table Name',
        );
    }
}
$model = new Tblform();

//intitialization
//checks if there is a new table send by the form AND save it on the xml file
if($newtable!=""){
	   $xml = new DOMDocument();
		$xml->load(__DIR__.'/updb.xml');
		$tables = $xml->getElementsByTagName("tables"); 
		// print_r($tables);exit();
		if($tables->length > 0)
		{
			$value = $xml->createElement( 'table' );
		    $value->nodeValue = 0;
		    $value->setAttribute("name",$newtable);
	    	$tables[0]->appendChild( $value );
		  	// $tables->appendChild();
		}else{
			$nodes = $xml->createElement('tables');
		    $value = $xml->createElement( 'table' );
		    $value->nodeValue = 0;
		    $value->setAttribute("name",$newtable);
		    $nodes->appendChild( $value );
		    $xml->appendChild( $nodes );
		}


		
		//re-save
		$xml->save(__DIR__."/updb.xml");
		// $xml->saveXml();
}



//check if there is a table to update in xml file
if(!$table==""){

	$xml = new DOMDocument();
	$xml->load(__DIR__.'/updb.xml');
	$tables = $xml->getElementsByTagName("table");

	foreach ($tables as $tbl) {
		if($tbl->getAttribute("name")==$table){
			$tbl->nodeValue=$tbl->nodeValue + $number;
		}
	}
	$xml->save(__DIR__."/updb.xml");

	?>
	<script type="text/javascript">
$(document).ready(function(){
	location.href="/lab/updb/index/";
});


</script>
	<?php

}

?>
<h1>Migration</h1>
<hr>
<?php
$xml = new DOMDocument();
$xml->load(__DIR__.'/updb.xml');
$tables = $xml->getElementsByTagName("table"); 

foreach ($tables as $table) {
	echo "<h2><i>".$table->getAttribute("name")."</i></h2>";
	echo '<b>'.$table->nodeValue.'</b> Out of ';
	echo "<div>";
	$numrecords = 0;
	switch($table->getAttribute("name")){
		case "customer":
		$numrecords=Customer::model()->count();
		echo $numrecords." Records <br/>";
		echo CHtml::link('Sync',array('synccustomer','start'=>$table->nodeValue),['class'=>'btn btn-primary btn-small']);
		break;

		case "request":
		$numrecords=Request::model()->count();
		echo $numrecords." Records <br/>";
		echo CHtml::link('Sync',array('syncrequest','start'=>$table->nodeValue),['class'=>'btn btn-primary btn-small']);
		break;

		case "sample":
		$numrecords=Sample::model()->count();
		echo $numrecords." Records <br/>";
		echo CHtml::link('Sync',array('syncsample','start'=>$table->nodeValue),['class'=>'btn btn-primary btn-small']);
		break;

		case "analysis":
		$numrecords=Analysis::model()->count();
		echo $numrecords." Records <br/>";
		echo CHtml::link('Sync',array('syncanalysis','start'=>$table->nodeValue),['class'=>'btn btn-primary btn-small']);
		break;

		default :
		echo "<strong>NOT FOUND!</strong>" ;
		$numrecords = 0; 
	}
	if($numrecords==0)
		$value = 0;
	else
		$value = (100 * $table->nodeValue)/$numrecords;

	$this->widget('zii.widgets.jui.CJuiProgressBar', array(
        'value'=>$value, //value in percent
        'htmlOptions'=>array(
                'style'=>'height:20px;'
        ),
	));
	echo "</div>";
}
?>

<h1>Add new Table</h1>

<div class="form">
	
	<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'tbl-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name'); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

<?php $this->endWidget(); ?>
</div>

