<!--import maintenance dialogue-->


<!--Start body of the popupload -->



 <div id="pdftarget" style="height:900px">

<?php

	if($pdf){
		$basePath=Yii::app()->baseUrl.'/upload/';
		echo $basePath.$pdf;
		$this->widget('ext.pdfJs.QPdfJs',array(
			'id'=>'pdfviewer',
			//'url'=>$basePath."hhh.pdf",
			'url'=>$basePath.$pdf,
			//'url'=>Yii::getPathOfAlias('webroot').'/upload/mytest.pdf',
		));


	 }
	else{
		echo "No PDF Data";
	}
	
?>

</div> 




	

<!--End body of the popupload -->
