<?php 
Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl . '/css/font-awesome/css/font-awesome.min.css');

Yii::app()->clientScript->registerScript('dataProvider', "
    
    /*$('.genReport form').submit(function(){
        
        $('#accomplishment-grid').yiiGridView('update', {
            data: $(this).serialize(),
            beforeSend: function(){
                  var overlay = new ItpOverlay('accomplishment-grid');
                  overlay.show();
            }
        });
        return false;
    });*/
    
    $('.accomplishment-form form').submit(function(){
        
        $('#accomplishment-grid').yiiGridView('update', {
            data: $(this).serialize(),
            beforeSend: function(){
                  var overlay = new ItpOverlay('accomplishment-grid');
                  overlay.show();
            }
        });
        return false;
    });
");
?>
<div class="summary-form">
<?php $image=Yii::app()->baseUrl.('/images/ajax-loader.gif');?>
<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

<b>&nbsp;<?php echo CHtml::encode('LAB :'); ?>&nbsp;</b>
<?php 
    echo CHtml::dropDownList('lab', $labId,    
            Lab::listDataByLabName(),
            array(
                'onchange'=>'js:{
                        var lab = $("#lab").val();
                        //var month = $("#month").val();
                        var year = $("#year").val();
                        $("a.export").prop("onclick", null).off("click");
                        $("a.export").attr("href","accomplishments/export/lab/"+lab+"/year/"+year);
                        $(".summary-form form").submit();
                }',
            )
    );
?>

<b>&nbsp;<?php echo CHtml::encode('YEAR :'); ?>&nbsp;</b>
<?php
    echo CHtml::dropDownList('year', $year, 
                CHtml::listData($this->getYear(), 'index', 'year'),
                array(
                    'onchange'=>'js:{
                        var labId = $("#lab").val();
                        //var month = $("#month").val();
                        var year = $("#year").val();
                        $("a.export").prop("onclick", null).off("click");
                        $("a.export").attr("href","accomplishments/export/labId/"+labId+"/year/"+year);
                        $(".summary-form form").submit();
                        }',
                )
    );
?>

<?php //echo ' '.CHtml::link('<i class="fa fa-file-excel-o fa-lg fa-fw">&nbsp;</i>Export',$this->createUrl('accomplishments/export',array('labId'=>$labId,'month'=>$month,'year'=>$year)), array('class'=>'export btn btn-success btn-small','title'=>'Export to excel'));?>

<?php $this->endWidget(); ?>
</div>
<h3 class="title" style="text-align: center; margin-top: -20px; margin-bottom: -20px;"><?php echo Yii::app()->Controller->showRstl(Yii::app()->Controller->getRstlId()).' '.date('Y').' Monthly Summary of Test';?> </h3>
<br />

<?php 

     $this->widget('ext.groupgridview.GroupGridView', array(
          'id' => 'grid-summary',
          'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
          //'rowHtmlOptionsExpression' => 'array("title" => "Click to update", "class"=>"link-hand")',
          'dataProvider' => $dataProvider,
          'summaryText' => '',
          'mergeColumns' => '',
          'columns' => array(
              //'id',
               array(
                'name'=>'method',
                'header'=>'Method',
                'type'=>'raw',
                'value'=>'$data->method',
                'htmlOptions' => array('style' => 'text-align: left; padding-left: 15px;'),
              ), 
              array(
                'name'=>'testName',
                'header'=>'Test Name',
                'type'=>'raw',
                'value'=>'$data->testName',
                'htmlOptions' => array('style' => 'text-align: left; padding-left: 15px;'),
              ),
              array(
                'name'=>'fee',
                'header'=>'Fee',
                'type'=>'raw',
                'value'=>'Yii::app()->format->formatNumber($data->fee)',
                'htmlOptions' => array('style' => 'width: 70px; text-align: right; padding-left: 15px;'),
              ),
              array(
                'name'=>'countMonthlysamples',
                'header'=>'JAN',
                'type'=>'raw',
                'value'=>'$data->countMonthlysamples(1, $_GET["year"], $data->testId)',
                'htmlOptions' => array('style' => 'text-align: center;'),
              ),
              array(
                'name'=>'feb',
                'header'=>'FEB',
                'type'=>'raw',
                'value'=>'$data->countMonthlysamples(2, $_GET["year"], $data->testId)',
                'htmlOptions' => array('style' => 'text-align: center; '),
              ),
              array(
                'name'=>'mar',
                'header'=>'MAR',
                'type'=>'raw',
                'value'=>'$data->countMonthlysamples(3, $_GET["year"], $data->testId)',
                'htmlOptions' => array('style' => 'text-align: center;'),
              ),
              array(
                'name'=>'apr',
                'header'=>'APR',
                'type'=>'raw',
                'value'=>'$data->countMonthlysamples(4, $_GET["year"], $data->testId)',
                'htmlOptions' => array('style' => 'text-align: center;'),
              ),
              array(
                'name'=>'may',
                'header'=>'MAY',
                'type'=>'raw',
                'value'=>'$data->countMonthlysamples(5, $_GET["year"], $data->testId)',
                'htmlOptions' => array('style' => 'text-align: center;'),
              ),
              array(
                'name'=>'jun',
                'header'=>'JUN',
                'type'=>'raw',
                'value'=>'$data->countMonthlysamples(6, $_GET["year"], $data->testId)',
                'htmlOptions' => array('style' => 'text-align: center;'),
              ),
              array(
                'name'=>'jul',
                'header'=>'JUL',
                'type'=>'raw',
                'value'=>'$data->countMonthlysamples(7, $_GET["year"], $data->testId)',
                'htmlOptions' => array('style' => 'text-align: center;'),
              ),
              array(
                'name'=>'aug',
                'header'=>'AUG',
                'type'=>'raw',
                'value'=>'$data->countMonthlysamples(8, $_GET["year"], $data->testId)',
                'htmlOptions' => array('style' => 'text-align: center;'),
              ),
              array(
                'name'=>'sep',
                'header'=>'SEP',
                'type'=>'raw',
                'value'=>'$data->countMonthlysamples(9, $_GET["year"], $data->testId)',
                'htmlOptions' => array('style' => 'text-align: center;'),
              ),
              array(
                'name'=>'oct',
                'header'=>'OCT',
                'type'=>'raw',
                'value'=>'$data->countMonthlysamples(10, $_GET["year"], $data->testId)',
                'htmlOptions' => array('style' => 'text-align: center;'),
              ),
              array(
                'name'=>'nov',
                'header'=>'NOV',
                'type'=>'raw',
                'value'=>'MonthlyTest::countMonthlysamples(11, $_GET["year"], $data->testId)',
                'htmlOptions' => array('style' => 'text-align: center;'),
              ),
               array(
                'name'=>'dec',
                'header'=>'DEC',
                'type'=>'raw',
                'value'=>'$data->countMonthlysamples(12, $_GET["year"], $data->testId)',
                'htmlOptions' => array('style' => 'text-align: center;'),
              ),
              array(
                'name'=>'totalFees',
                'header'=>'Total Fees',
                'type'=>'raw',
                'value'=>'Yii::app()->format->formatNumber($data->countSampletotal($_GET["year"], $data->testId) * $data->fee)',
                'htmlOptions' => array('style' => 'width: 70px; text-align: right; padding-left: 15px;'),
              ),
               
        ))); 
?>


<?php $image = CHtml::image(Yii::app()->request->baseUrl . '/images/ajax-loader.gif');?>
<!-- Loader Dialog : Start-->
<?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id'=>'dialogLoader',
            // additional javascript options for the dialog plugin
            'options'=>array(
                'title'=>'Generating Report...' ,
                'show'=>'scale',
                'hide'=>'scale',                
                'width'=>'40%',
                'modal'=>true,
                'resizable'=>false,
                'autoOpen'=>false,
                ),
        ));
    echo '<div class="loader">'.$image.'<br \><br \>Processing.<br \>Please wait...</div>';
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- Loader Dialog : End -->
<script type="text/javascript">
/*<![CDATA[*/
/*jQuery(document).ready(function() {
             jQuery('#yt0').click(function( {
                 
                            jQuery.yii.submitForm(
                                     this,
                                     'controller/action',{}
                                          );return false;});
                                  });*/
/*]]>*/
function alertMsg() {
    alert('From Date should be <= to To Date');
    
    return false;
}
</script>
<style type="text/css">
    .table-striped tbody tr td.extrarowgroupfooter {
        background-color: #FFE4B5;
        color: #222222;
        /*color: #000000;*/
        text-align:center;
        text-decoration:none;
        font-weight:600;
    }
</style>
