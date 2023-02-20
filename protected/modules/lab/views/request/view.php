<div style="position:relative">
    <?php
    /* @var $this RequestController */
    /* @var $model Request */

    if ($model->cancelled)
        $this->renderPartial('_cancelled', array('model' => $model->cancelDetails));

    $this->breadcrumbs = array(
        'Requests' => array('index'),
        $model->id,
    );

    $this->menu = array(
        array('label' => 'Create Request', 'url' => array('create')),
        array('label' => 'Update Request', 'url' => array('update', 'id' => $model->id)),
        array('label' => 'Manage Request', 'url' => array('admin')),
    );
    ?>

    <?php
    $linkCancelWithReason = CHtml::link('<span class="icon-white icon-minus-sign"></span> Cancel Request', '', array(
        'style' => 'cursor:pointer;',
        'class' => 'btn btn-danger btn-small',
        'onClick' => 'js:{
                if(' . $generated . '>0){
                    alert("Cannot cancel this Request. If you set the wrong Laboratory when creating this Request, \nleave this Request for now and create a new one with the correct Laboratory.");
                }else{
                    cancelRequest(); 
                    $("#dialogCancel").dialog("open");					
                }
            }',
    ));
    $linkDuplicateSR = CHtml::link('<span class="icon-white icon-repeat"></span> Duplicate Request', '', array(
        'style' => 'cursor:pointer;',
        'class' => 'btn btn-warning btn-small',
        'onClick' => 'js:{
            if(' . $generated . '>0){
                alert("Cannot duplicate this Request. If you set the wrong Laboratory when creating this Request, \nleave this Request for now and create a new one with the correct Laboratory.");
            }else{
                duplicateRequest(); 
                $("#dialogDuplicate").dialog("open");
            }
        }',
    ))
    ?>
    <h1>Service Request: <?php echo $model->requestRefNum; ?>
        <small style="float:right;">
            <?php 
                echo $model->cancelled ? '' : (Yii::app()->getModule('lab')->isLabAdmin() ? $linkDuplicateSR : '');
                echo '&nbsp;';
                echo $model->cancelled ? '' : (Yii::app()->getModule('lab')->isLabAdmin() ? $linkCancelWithReason : ''); 
            ?>
        </small>
    </h1>

    <?php $this->widget('ext.widgets.DetailView4Col', array(
        'cssFile' => false,
        'htmlOptions' => array('class' => 'detail-view table table-striped table-condensed'),
        'data' => $model,
        'attributes' => array(
            array(
                'name' => 'requestDetails',
                'oneRow' => true,
                'cssClass' => 'title-row',
                'type' => 'raw',
                'value' => '',
            ),
            'requestRefNum', 'customer.customerName',
            array(
                'name' => 'requestDate',
                'type' => 'raw',
                'value' => Yii::app()->dateFormatter->format('MMMM d, yyyy', strtotime($model->requestDate)),
            ), 'customer.completeAddress',
            //'requestTime', 
            //'customer.tel',
            array(
                'name' => 'reportDue',
                'type' => 'raw',
                'value' => Yii::app()->dateFormatter->format("EEE, MMMM d, yyyy", strtotime($model->reportDue)),
            ),
            array(
                'name' => 'contact_number',
                'type' => 'raw',
                'value' => ($model->contact_number == '') ? $model->customer->tel : $model->contact_number,
            ),
            //'customer.fax',
            // 'customer.head', 
            array(
                'name' => 'addforcert',
                'type' => 'raw',
                'value' => ($model->addforcert == '') ? $model->customer->head : $model->addforcert,
            ),
            'customer.email',
            array(
                'name' => 'paymentDetails',
                'cssClass' => 'title-row',
                'oneRow' => true,
                'type' => 'raw',
                'value' => '',
            ),
            array(
                'name' => 'orId',
                'type' => 'raw',
                'value' => Request::getORs($model->receipts)
            ),
            'collection',
            array(
                'name' => 'orDate',
                'type' => 'raw',
                'value' => Request::getORDates($model->receipts)
            ),
            //'value'=>$model->analysisTotal),
            array(
                'name' => 'balance',
                'type' => 'raw',
                'value' => Request::getBalance($model->total, $model->collection)
            ),
            array(
                'name' => 'transactionDetails',
                'oneRow' => true,
                'cssClass' => 'title-row',
                'type' => 'raw',
                'value' => '',
            ),
            'receivedBy',
            //'conforme',
            'transmission',

        ),
    )); ?>

    <div class="addSample">
        <?php
        $this->beginWidget('zii.widgets.CPortlet', array(
            'title' => "<b>Testing or Calibration Services</b>",
        ));
        ?>
        <h4 class="paddingLeftRight10">Samples
            <small>
                <?php
                $linkSample = Chtml::link('<span class="icon-white icon-plus-sign"></span> Add Sample', '', array(
                    'style' => 'cursor:pointer;',
                    'class' => 'btn btn-info btn-small',
                    'onClick' => $model->cancelled ? 'return false' : 'js:{addSample(); $("#dialogSample").dialog("open");}',
                    'disabled' => $model->cancelled
                ));
                echo ($generated >= 1) ? $linkSample : (Yii::app()->getModule('lab')->isAdmin() ? $linkSample : '');
                ?>
            </small>

            <small>
                <?php
                $linkSample = Chtml::link('<span class="icon-white icon-plus-sign"></span> Add Bulk Sample', '', array(
                    'style' => 'cursor:pointer;',
                    'class' => 'btn btn-info btn-small',
                    'onClick' => $model->cancelled ? 'return false' : 'js:{addBulkSample(); $("#dialogBulkSample").dialog("open");}',
                    'disabled' => $model->cancelled
                ));
                //echo ($generated >= 1) ? $linkSample : (Yii::app()->getModule('lab')->isAdmin() ? $linkSample : '');
                ?>
            </small>
        </h4>
    </div>

    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'sample-grid',
        'summaryText' => false,
        'emptyText' => 'No samples.',
        'htmlOptions' => array('class' => 'grid-view padding0 paddingLeftRight10'),
        'rowCssClassExpression' => '$data->status',
        'itemsCssClass' => 'table table-hover table-striped table-bordered table-condensed',
        'rowHtmlOptionsExpression' => 'array("title" => "Click to update", "class"=>"link-hand")',
        //It is important to note, that if the Table/Model Primary Key is not "id" you have to
        //define the CArrayDataProvider's "keyField" with the Primary Key label of that Table/Model.
        'dataProvider' => $sampleDataProvider,
        'columns' => array(
            array(
                'name' => '#',
                'value' => '$row+1',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'text-align: center;'),
            ),
            array(
                'name' => 'SAMPLE CODE',
                'value' => '$data->sampleCode',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'width: 125px; padding-left: 10px; text-align: center;'),
            ),
            array(
                'name' => 'SAMPLE NAME',
                'value' => '$data->sampleName',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'width: 250px; padding-left: 10px;'),
            ),
            array(
                'name' => 'DESCRIPTION',
                'value' => function ($data) {
                    if ($data->jobType != '') {
                        $desc = 'Type of Job: ' . $data->jobType . $desc;
                    }
                    if ($data->serial_no != '') {
                        $desc = $desc . ' / Serial No.: ' . $data->serial_no;
                    }
                    if ($data->brand != '') {
                        $desc = $desc . ' / Brand or Manufacture: ' . $data->brand;
                    }
                    if ($data->capacity_range != '') {
                        $desc = $desc . ' / Capacity or Range: ' . $data->capacity_range;
                    }
                    if ($data->resolution != '') {
                        $desc = $desc . ' / Resolution: ' . $data->resolution;
                    }
                    echo $data->description . " / " . $desc;
                },
                'type' => 'raw',
                'htmlOptions' => array('style' => 'padding-left: 10px;'),
            ),
            // array(
            // 	'name'=>'REMARKS',
            // 	'value'=>'$data->remarks',
            // 	'type'=>'raw',
            //  'htmlOptions' => array('style' => 'width: 200px; padding-left: 10px;'),
            // ),

            array(
                'header' => 'Actions',
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'deleteConfirmation' => "js:'Do you really want to delete sample: '+$.trim($(this).parent().parent().children(':nth-child(2)').text())+'?'",
                'template' => ($generated >= 1) ? '{delete} {worksheet}' : (Yii::app()->getModule('lab')->isAdmin() ? '{delete} {worksheet}' : (Yii::app()->getModule('lab')->isLabAdmin() == 'Lab - System Manager' ? ' {worksheet}' : '{cancel}')),
                'buttons' => array(
                    'delete' => array(
                        'label' => 'Delete Sample',
                        'url' => 'Yii::app()->createUrl("lab/sample/delete/id/$data->id")',
                    ),
                    'worksheet' => array(
                        'label' => 'Print Worksheet',
                        'icon' => 'print',
                        'url' => 'Yii::app()->createUrl("lab/sample/printworksheet/id/$data->id")',
                        'options' => array('target' => '_blank'),
                        'onClick' => 'js:preventDefault()',
                    ),
                    'cancel' => array(
                        'label' => 'Cancel',
                        'url' => 'Yii::app()->createUrl("lab/sample/cancel/id/$data->id")',
                        'options' => array(
                            'confirm' => 'Are you want to cancel Sample?',
                            'ajax' => array(
                                'type' => 'get',
                                'url' => 'js:$(this).attr("href")',
                                'success' => 'js:function(data) { $.fn.yiiGridView.update("sample-grid")}'
                            )
                        ),
                    ),
                ),
            ),
        ),
    ));
    //echo 'Here-';
    //var_dump(Yii::app()->getModule('lab')->isLabAdmin()=="Lab - Analyst");
    ?>
    <div class="grid-view padding0 paddingLeftRight10">
        <?php
        $this->widget('bootstrap.widgets.TbButtonGroup', array(
            'type' => 'success',
            'buttons' => array(
                array('label' => 'Print Label', 'url' => $this->createUrl('request/printLabel', array('id' => $model->id)), 'htmlOptions' => array('target' => '_blank')),
                array('items' => array(
                    array('label' => 'QR', 'url' => '#', 'active' => Yii::app()->params['FormRequest']['printFormat'] == 1 ? true : false, 'linkOptions' => array('onclick' => 'setPrintFormat("FormRequest", 1)')),
                    array('label' => 'Barcode', 'url' => '#', 'active' => Yii::app()->params['FormRequest']['printFormat'] == 2 ? true : false, 'linkOptions' => array('onclick' => 'setPrintFormat("FormRequest", 2)')),
                )),
            ),
        ));
        ?>
    </div>
    <h4 class="paddingLeftRight10">Analyses
        <small>
            <?php
            $linkAnalysis = Chtml::link('<span class="icon-white icon-plus-sign"></span> Add Analyses', '', array(
                'style' => 'cursor:pointer;',
                'onClick' => $model->cancelled ? 'return false' : 'js:{addAnalysis(); $("#dialogAnalysis").dialog("open");}',
                'class' => 'btn btn-info btn-small',
                'disabled' => $model->cancelled
            ));
            $linkPackage = Chtml::link('<span class="icon-white icon-plus-sign"></span> Add Package', '', array(
                'style' => 'cursor:pointer;',
                'onClick' => $model->cancelled ? 'return false' : 'js:{addPackage(); $("#dialogPackage").dialog("open");}',
                'class' => 'btn btn-info btn-small',
                'disabled' => $model->cancelled
            ));

            echo ($generated >= 1) ? $linkAnalysis : (Yii::app()->getModule('lab')->isLabAdmin() == 'Lab - System Manager' ? $linkAnalysis : '');
            echo " ";
            echo ($generated >= 1) ? $linkPackage : (Yii::app()->getModule('lab')->isLabAdmin() == 'Lab - System Manager' ? $linkPackage : '');
            ?>
        </small>
        <small style="float:right;">
            <?php

            $linkRemarks = Chtml::link('<span class="icon-white icon-plus-sign"></span> Add Remarks', '', array(
                'style' => 'cursor:pointer;',
                'onClick' => $model->cancelled ? 'return false' : 'js:{addRemarks(); $("#dialogRemarks").dialog("open");}',
                'class' => 'btn btn-danger btn-small',
                'disabled' => $model->cancelled
            ));

            $linkAdditional = Chtml::link('<span class="icon-white icon-plus-sign"></span> Add Pick-up/Delivery Charge', '', array(
                'style' => 'cursor:pointer;',
                'onClick' => $model->cancelled ? 'return false' : 'js:{addAdditional(); $("#dialogAdditional").dialog("open");}',
                'class' => 'btn btn-danger btn-small',
                'disabled' => $model->cancelled
            ));

            $linkInplantCharge = Chtml::link('<span class="icon-white icon-plus-sign"></span> Add On-site Charge', '', array(
                'style' => 'cursor:pointer;',
                'onClick' => $model->cancelled ? 'return false' : 'js:{addInplantCharges(); $("#dialogInplant").dialog("open");}',
                'class' => 'btn btn-danger btn-small',
                'disabled' => $model->cancelled
            ));

            // echo ($generated >= 1) ? (Yii::app()->getModule('lab')->isLabAdmin() ? $linkRemarks : '') : '';
            // echo " ";
            // echo ($generated >= 1) ? (Yii::app()->getModule('lab')->isLabAdmin() ? $linkAdditional : '') : '';
            // echo " ";
            // echo ($generated >= 1) ? (Yii::app()->getModule('lab')->isLabAdmin() ? $linkInplantCharge : '') : '';

            echo $linkRemarks . " " . $linkAdditional . " " . $linkInplantCharge;
            ?>
        </small>
    </h4>
    <?php
    if ($model->vat == 1) {

        $testMethods = 'SUBTOTAL<br/>On-site Charge<br/>Pick-up/Delivery Charge<br/>DISCOUNT<br/>Total Fees<br/>VAT(12%)<br/><b>GRAND TOTAL</b>';
        $newFee = Yii::app()->format->formatNumber($model->getTestTotal($analysisDataProvider->getKeys())) .
            '<br/>' .
            Yii::app()->format->formatNumber($model->getInplantCharge()) .
            '<br/>' .
            Yii::app()->format->formatNumber($model->getAdditional()) .
            '<br/>' .
            Yii::app()->format->formatNumber($model->getDiscountFee($analysisDataProvider->getKeys(), $model->discount)) .
            '<br/>' .
            Yii::app()->format->formatNumber($model->getDiscountedFee($analysisDataProvider->getKeys(), $model->discount)) .
            '<br/>' .
            //vat here
            Yii::app()->format->formatNumber($model->getSubTotal($analysisDataProvider->getKeys(), $model->discount, $model->vat))
            . '<br/><b>' .
            Yii::app()->format->formatNumber($model->getRequestTotal($analysisDataProvider->getKeys(), $model->discount, $model->vat)) .
            '</b>';
    } else {
        $testMethods = 'SUBTOTAL<br/>On-site Charge<br/>Pick-up/Delivery Charge<br/>DISCOUNT<br/><b>GRAND TOTAL</b>';
        $newFee = Yii::app()->format->formatNumber($model->getTestTotal($analysisDataProvider->getKeys())) .
            '<br/>' .
            Yii::app()->format->formatNumber($model->getInplantCharge()) .
            '<br/>' .
            Yii::app()->format->formatNumber($model->getAdditional()) .
            '<br/>' .
            Yii::app()->format->formatNumber($model->getDiscountFee($analysisDataProvider->getKeys(), $model->discount)) .
            '<br/><b>' .
            Yii::app()->format->formatNumber($model->getRequestTotal($analysisDataProvider->getKeys(), $model->discount, $model->vat)) .
            '</b>';
    }
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'analysis-grid',
        'summaryText' => false,
        'emptyText' => 'No analyses.',
        'htmlOptions' => array('class' => 'grid-view padding0 paddingLeftRight10'),
        'itemsCssClass' => 'table table-hover table-striped table-bordered table-condensed',
        'rowHtmlOptionsExpression' => 'array("title" => "Click to update", "class"=>"link-hand")',
        //It is important to note, that if the Table/Model Primary Key is not "id" you have to
        //define the CArrayDataProvider's "keyField" with the Primary Key label of that Table/Model.
        'dataProvider' => $analysisDataProvider,
        'columns' => array(
            //'sample.sampleName',
            array(
                'name' => '#',
                'value' => '$row+1',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'text-align: center;'),
            ),
            array(
                'name' => 'SAMPLE',
                'value' => '$data->sample->sampleName',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'width: 200px; padding-left: 10px;'),
            ),
            //'sampleCode',
            array(
                'name' => 'SAMPLE CODE',
                'value' => '$data->sampleCode',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'width: 100px; text-align: center;'),
            ),
            //'testName',
            array(
                'name' => 'SERVICE REQUESTED',
                'value' => '($data->package == 1) ? "&nbsp;&nbsp;".$data->testName : $data->testName',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'padding-left: 10px;'),
            ),
            //'method',    		
            array(
                'name' => 'METHODS/REFERENCES',
                'value' => '$data->method',
                'type' => 'raw',
                //'htmlOptions' => array('style' => 'padding-left: 10px;'),
                //'footerHtmlOptions'=>array('style'=>'display:none;'),
                'htmlOptions' => array('style' => 'width: 250px; text-align: center;'),
                'footer' => $testMethods,
                'footerHtmlOptions' => array('style' => 'text-align: right; padding-right: 10px;'),
            ),
            //'quantity',
            /*
    		array(
				'name'=>'QUANTITY',
				'value'=>'($data->package == 1) ? "-" : $data->quantity',
				'type'=>'raw',
    			'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
    			//'footer'=>'SUBTOTAL<br/>DISCOUNT<br/>On-site Charge<br/>Additional<br/><b>TOTAL</b>',
    			//'footerHtmlOptions'=>array('colspan'=>'2','style'=>'text-align: right; padding-right: 10px;'),
			),
			*/
            //'fee'
            array(
                'name' => 'Service Fee',
                //'value'=>'Yii::app()->format->formatNumber($data->fee)',
                'value' => '($data->package == 1) ? "-" : Yii::app()->format->formatNumber($data->fee)',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'width: 65px; text-align: right; padding-right: 10px;'),
                'footer' => $newFee,
                'footerHtmlOptions' => array('style' => 'text-align: right; padding-right: 10px;'),
            ),
            array(
                //'class'=>'CButtonColumn',
                'header' => 'Actions',
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'deleteConfirmation' => "js:'Do you really want to delete analysis: '+$.trim($(this).parent().parent().children(':nth-child(3)').text())+'?'",
                'template' => ($generated >= 1) ? '{delete}' : (Yii::app()->getModule('lab')->isAdmin() ? '{delete}' : ''),
                'buttons' => array(
                    'delete' => array(
                        'label' => 'Delete Sample',
                        'url' => 'Yii::app()->createUrl("lab/analysis/delete/id/$data->id")',
                        'visible' => '$data->package >= 0'
                    ),
                    'deletePackage' => array(
                        'label' => 'Delete Package',
                        'url' => 'Yii::app()->createUrl("lab/analysis/deletePackage/id/$data->id")',
                        //'options'=>array('class'=>'Add 1 more'),
                        'imageUrl' => '',
                        'imageUrl' => Yii::app()->request->baseUrl . '/images/customer_add.png',
                        'visible' => '$data->package == 1'
                    ),
                    'cancel' => array(
                        'label' => 'Cancel',
                        'url' => 'Yii::app()->createUrl("lab/analysis/cancel/id/$data->id")',
                        'options' => array(
                            'confirm' => 'Are you want to cancel Analysis?',
                            'ajax' => array('type' => 'get', 'url' => 'js:$(this).attr("href")', 'success' => 'js:function(data) { $.fn.yiiGridView.update("analysis-grid")}')
                        ),
                    ),
                ),
            ),
        ),
    ));
    ?>
    <?php $this->endWidget(); //End Portlet 
    ?>

    <div class="generated">
        <?php /*echo $generated ? 'Print' : CHtml::ajaxLink(
		Yii::t('default','Generate Sample Code'),
		$this->createUrl('sample/generateSampleCode/',array('id'=>$model->id)), 
		array('success'=>'function(data){
				$.fn.yiiGridView.update("sample-grid");
                $.fn.yiiGridView.update("analysis-grid");
			}') 
		);*/
        $image = CHtml::Image(Yii::app()->theme->baseUrl . '/img/page_white_excel.png', 'Print');
        switch ($generated) {
            case 0:
                echo '<div class="buttons" style="display:flex;">';
                // $this->widget('bootstrap.widgets.TbButtonGroup', array(
                //        'type'=>'success', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                //        'stacked'=>false,
                //        'buttons'=>array(
                //            array('label'=>'Print Request', 'url'=>$this->createUrl('request/print',array('id'=>$model->id)), 'htmlOptions'=>array('target'=>(Yii::app()->params['FormRequest']['printFormat'] == 2) ? '' : '_blank')),
                //            array('items'=>array(
                //                array(	'label'=>'Excel', 'url'=>'#', 'active'=>Yii::app()->params['FormRequest']['printFormat'] == 1 ? true : false, 'linkOptions'=>array('onclick'=>'setPrintFormat("FormRequest", 1)')),
                //                array(	'label'=>'PDF', 'url'=>'#', 'active'=>Yii::app()->params['FormRequest']['printFormat'] == 2 ? true : false, 'linkOptions'=>array('onclick'=>'setPrintFormat("FormRequest", 2)')),
                //            )),
                //        ),
                //    ));
                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'type' => 'success', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'buttons' => array(
                        array(
                            'label' => 'Print Request',
                            'url' => $this->createUrl('request/printPDF/', array('id' => $model->id)),
                            'htmlOptions' => array('target' => '_blank'),
                        ),
                    ),
                ));
                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'type' => 'success', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'buttons' => array(
                        array(
                            'label' => 'Create O.R.',
                            'url' => $this->createUrl('orderofpayment/createOP/', array('id' => $model->customer->id)),
                            // 'htmlOptions'=>array('target'=>(Yii::app()->params['FormRequest']['printFormat'] == 1) ? '' : '_blank'),
                        ),
                    ),
                ));
                echo '</div>';
                break;
            case ($generated < 1):
                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'type' => 'success', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'buttons' => array(
                        array('label' => 'Print Request', 'url' => $this->createUrl('request/print', array('id' => $model->id)), 'htmlOptions' => array('target' => (Yii::app()->params['FormRequest']['printFormat'] == 2) ? '_blank' : '')),
                        array('items' => array(
                            array('label' => 'Excel', 'url' => '#', 'active' => Yii::app()->params['FormRequest']['printFormat'] == 1 ? true : false, 'linkOptions' => array('onclick' => 'setPrintFormat("FormRequest", 2)')),
                            array('label' => 'PDF', 'url' => '#', 'active' => Yii::app()->params['FormRequest']['printFormat'] == 2 ? true : false, 'linkOptions' => array('onclick' => 'setPrintFormat("FormRequest", 1)')),
                        )),
                    ),
                ));

                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'type' => 'success', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'buttons' => array(
                        array(
                            'label' => 'Create Order of Payment',
                            'url' => $this->createUrl('orderofpayment/createOP/', array('id' => $model->customer->id)),
                            // 'htmlOptions'=>array('target'=>(Yii::app()->params['FormRequest']['printFormat'] == 1) ? '' : '_blank'),
                        ),
                    ),
                ));

                break;
            case 1:
                echo Chtml::link('<span class="icon-white icon-list"></span> Generate Sample Code', '', array(
                    'id' => 'cancel-button',
                    'title' => 'Generate Sample Code',
                    'class' => 'btn btn-success',
                    //'onClick'=>'js:{ generateSampleCode(); $("#dialogCancel").dialog("open");}',
                    "onclick" => $model->cancelled ? 'return false' : "if (!confirm('Do you really want to GENERATE Sample Codes with the current number of samples?')){return}else{ generateSampleCode(); $(this).prop('onclick',null); $('#dialogSampleCode').dialog('open'); }",
                ));

                break;
            case $generated > 1:
                echo '<p style="font-style: italic; font-weight: bold; color: red;">Generate Sample Codes from previous requests and refresh this page!</p>';
                break;
        }
        ?>
        <br /><br /><br />
    </div>

    <!-- Cancel Dialog : Start -->
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogCancel',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Cancel Request',
            'show' => 'scale',
            'hide' => 'scale',
            'width' => 450,
            'modal' => true,
            'resizable' => false,
            'autoOpen' => false,
        ),
    ));

    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <!-- Cancel Dialog : End -->

    <!-- Duplicate Dialog : Start -->
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogDuplicate',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Duplicate Request',
            'show' => 'scale',
            'hide' => 'scale',
            'width' => 770,
            'modal' => true,
            'resizable' => false,
            'autoOpen' => false,
        ),
    ));

    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <!-- Cancel Dialog : End -->
    <!-- Sample Dialog : Start -->
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogSample',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Sample',
            'show' => 'scale',
            'hide' => 'scale',
            'width' => 770,
            'modal' => true,
            'resizable' => false,
            'autoOpen' => false,
        ),
    ));

    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <!-- Sample Dialog : End -->
    <!-- Bulk Sample Dialog : Start -->
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogBulkSample',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Bulk Sample',
            'show' => 'scale',
            'hide' => 'scale',
            'width' => 300,
            'modal' => true,
            'resizable' => false,
            'autoOpen' => false,
        ),
    ));

    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <!-- Sample Dialog : End -->
    <!-- Analysis Dialog : Start -->
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogAnalysis',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Analysis',
            'show' => 'scale',
            'hide' => 'scale',
            'width' => 400,
            'modal' => true,
            'resizable' => false,
            'autoOpen' => false,
        ),
    ));

    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <!-- Analysis Dialog : End -->

    <!-- Package Dialog : Start -->
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogPackage',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Package',
            'show' => 'scale',
            'hide' => 'scale',
            'width' => 400,
            'modal' => true,
            'resizable' => false,
            'autoOpen' => false,
        ),
    ));

    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <!-- Package Dialog : End -->

    <!-- Inplant Dialog : Start -->
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogInplant',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Add On-site Charge',
            'show' => 'scale',
            'hide' => 'scale',
            'width' => 400,
            'modal' => true,
            'resizable' => false,
            'autoOpen' => false,
        ),
    ));

    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <!-- Inplant Dialog : End -->

    <!-- Additional Dialog : Start -->
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogAdditional',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Add Pick up/Delivery Charge',
            'show' => 'scale',
            'hide' => 'scale',
            'width' => 400,
            'modal' => true,
            'resizable' => false,
            'autoOpen' => false,
            //'position'=>array(483, 50),
        ),
    ));

    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <!-- Additional Dialog : End -->
    <!-- Remarks Dialog : Start -->
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogRemarks',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Add Remarks',
            'show' => 'scale',
            'hide' => 'scale',
            'width' => 400,
            'modal' => true,
            'resizable' => false,
            'autoOpen' => false,
        ),
    ));
    echo 'dialogbox';
    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <!-- Remarks Dialog : End -->

    <!-- SampleCode Dialog : Start -->
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogSampleCode',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Generate Sample Code',
            'show' => 'scale',
            'hide' => 'scale',
            'width' => 400,
            'modal' => true,
            'resizable' => false,
            'autoOpen' => false,
        ),
    ));

    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <!-- SampleCode Dialog : End -->

    <!-- ConfirmGenerateSampleCode Dialog : Start -->
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogConfirmGenerate',
        // additional javascript options for the dialog plugin
        'options' => array(
            'title' => 'Confirm Generate',
            'show' => 'scale',
            'hide' => 'scale',
            'width' => 400,
            'modal' => true,
            'resizable' => false,
            'autoOpen' => false,
        ),
    ));
    $this->endWidget('zii.widgets.jui.CJuiDialog');
    ?>
    <!-- ConfirmGenerateSampleCode Dialog : End -->
    <?php
    $image = CHtml::image(Yii::app()->request->baseUrl . '/images/ajax-loader.gif');
    Yii::app()->clientScript->registerScript('clkrowgrid', "
        $('#sample-grid table tbody tr').live('click',function()
        {
            var id = $.fn.yiiGridView.getKey(
            'sample-grid',
            $(this).prevAll().length 
            );
            if($(this).children(':nth-child(1)').text()=='No samples.'){
                alert($(this).children(':nth-child(1)').text());
                //alert(id);
            }else{
                updateSample(id);
                $('#dialogSample').dialog('open');
            }
        });
    ");

    Yii::app()->clientScript->registerScript('clkrowgrid2', "
        $('#analysis-grid table tbody tr').live('click',function()
        {
            var id = $.fn.yiiGridView.getKey(
            'analysis-grid',
            $(this).prevAll().length 
            );
            if($(this).children(':nth-child(1)').text()=='No analyses.'){
                alert($(this).children(':nth-child(1)').text());
                //alert(id);
            }else{
                updateAnalysis(id);
                $('#dialogAnalysis').dialog('open');
            }
        });
    ");
    if (isset($_GET['error_msg'])) {
        echo '<script type="text/javascript">alert("One or more analysis is missing. Please CHECK!");</script>';
    }
    ?>

</div>
<script type="text/javascript">
    function cancelRequest() {
        <?php echo CHtml::ajax(array(
            'url' => $this->createUrl('cancelledrequest/create', array('id' => $model->id, 'requestRefNum' => $model->requestRefNum)),
            'data' => "js:$(this).serialize()",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogCancel').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogCancel form').submit(cancelRequest);
                }
                else
                {
                    $.fn.yiiGridView.update('sample-grid');
	                $.fn.yiiGridView.update('analysis-grid');
	                location.reload();
					$('#dialogCancel').html(data.div);
                    setTimeout(\"$('#dialogSample').dialog('close') \",1000);
					
                }

            }",
            'beforeSend' => 'function(jqXHR, settings){
                $("#dialogCancel").html(
                    \'<div class="loader">' . $image . '<br\><br\>Processing...<br\> Please wait...</div>\'
                );
            }',
            'error' => "function(request, status, error){
                $('#dialogCancel').html(status+'('+error+')'+': '+ request.responseText );
                }",

        )) ?>;
        return false;
    }

    function cancelDetails(id) {
        <?php
        echo CHtml::ajax(array(
            'url' => $this->createUrl('cancelledrequest/update'),
            'data' => "js:$(this).serialize()+ '&id='+id",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogCancel').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogCancel form').submit(cancelDetails);
                }
                else
                {
                    //$.fn.yiiGridView.update('sample-grid');
                    //$.fn.yiiGridView.update('analysis-grid');
					$('#dialogCancel').html(data.div);
                    setTimeout(\"$('#dialogSample').dialog('close') \",1000);
                }
            }",
            'beforeSend' => 'function(jqXHR, settings){
                    $("#dialogCancel").html(
						\'<div class="loader">' . $image . '<br\><br\>Processing...<br\> Please wait...</div>\'
					);
            }',
            'error' => "function(request, status, error){
                $('#dialogCancel').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
                }",
        )) ?>;
        return false;

    }

    function addSample() {
        <?php echo CHtml::ajax(array(
            'url' => $this->createUrl('sample/create', array('id' => $model->id, 'requestRefNum' => $model->requestRefNum)),
            'data' => "js:$(this).serialize()",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogSample').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogSample form').submit(addSample);
                }
                else
                {
                    $.fn.yiiGridView.update('sample-grid');
					$('#dialogSample').html(data.div);
                    setTimeout(\"$('#dialogSample').dialog('close') \",1000);
					
                }
 
            }",
            'beforeSend' => 'function(jqXHR, settings){
                    $("#dialogSample").html(
						\'<div class="loader">' . $image . '<br\><br\>Generating form.<br\> Please wait...</div>\'
					);
             }',
            'error' => "function(request, status, error){
				 	$('#dialogSample').html(status+'('+error+')'+': '+ request.responseText );
					}",

        )) ?>;
        return false;
    }

    function addBulkSample() {
        <?php echo CHtml::ajax(array(
            'url' => $this->createUrl('sample/createbulk', array('id' => $model->id, 'requestRefNum' => $model->requestRefNum)),
            'data' => "js:$(this).serialize()",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogBulkSample').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogBulkSample form').submit(addBulkSample);
                }
                else
                {
                    $.fn.yiiGridView.update('sample-grid');
					$('#dialogBulkSample').html(data.div);
                    setTimeout(\"$('#dialogBulkSample').dialog('close') \",1000);
					
                }
 
            }",
            'beforeSend' => 'function(jqXHR, settings){
                    $("#dialogBulkSample").html(
						\'<div class="loader">' . $image . '<br\><br\>Generating form.<br\> Please wait...</div>\'
					);
             }',
            'error' => "function(request, status, error){
				 	$('#dialogBulkSample').html(status+'('+error+')'+': '+ request.responseText );
					}",

        )) ?>;
        return false;
    }

    function updateSample(id) {
        <?php
        echo CHtml::ajax(array(
            'url' => $this->createUrl('sample/update'),
            'data' => "js:$(this).serialize()+ '&id='+id",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogSample').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogSample form').submit(updateSample);
                }
                else
                {
                    $.fn.yiiGridView.update('sample-grid');
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogSample').html(data.div);
                    setTimeout(\"$('#dialogSample').dialog('close') \",1000);
                }
            }",
            'beforeSend' => 'function(jqXHR, settings){
                    $("#dialogSample").html(
						\'<div class="loader">' . $image . '<br\><br\>Retrieving record.<br\> Please wait...</div>\'
					);
            }',
            'error' => "function(request, status, error){
				 	$('#dialogSample').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
					}",
        )) ?>;
        return false;

    }

    function addAnalysis() {
        <?php echo CHtml::ajax(array(
            'url' => $this->createUrl('analysis/create', array('id' => $model->id)),
            'data' => "js:$(this).serialize()",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogAnalysis').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogAnalysis form').submit(addAnalysis);
                }
                else
                {
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogAnalysis').html(data.div);
                    setTimeout(\"$('#dialogAnalysis').dialog('close') \",1000);
					
                }
 
            }",
            'beforeSend' => 'function(jqXHR, settings){
                    $("#dialogAnalysis").html(
						\'<div class="loader">' . $image . '<br\><br\>Generating form.<br\> Please wait...</div>\'
					);
             }',
            'error' => "function(request, status, error){
				 	$('#dialogAnalysis').html(status+'('+error+')'+': '+ request.responseText );
					}",

        )) ?>;
        return false;
    }

    function updateAnalysis(id) {
        <?php
        echo CHtml::ajax(array(
            'url' => $this->createUrl('analysis/update'),
            'data' => "js:$(this).serialize()+ '&id='+id",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogAnalysis').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogAnalysis form').submit(updateAnalysis);
                }
                else
                {
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogAnalysis').html(data.div);
                    setTimeout(\"$('#dialogAnalysis').dialog('close') \",1000);
                }
            }",
            'beforeSend' => 'function(jqXHR, settings){
                    $("#dialogAnalysis").html(
						\'<div class="loader">' . $image . '<br\><br\>Retrieving record.<br\> Please wait...</div>\'
					);
            }',
            'error' => "function(request, status, error){
				 	$('#dialogAnalysis').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
					}",
        )) ?>;
        return false;

    }

    function addPackage() {
        <?php echo CHtml::ajax(array(
            'url' => $this->createUrl('analysis/package', array('id' => $model->id)),
            'data' => "js:$(this).serialize()",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogPackage').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogPackage form').submit(addPackage);
                }
                else
                {
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogPackage').html(data.div);
                    setTimeout(\"$('#dialogPackage').dialog('close') \",1000);
					
                }
 
            }",
            'beforeSend' => 'function(jqXHR, settings){
                    $("#dialogPackage").html(
						\'<div class="loader">' . $image . '<br\><br\>Generating form.<br\> Please wait...</div>\'
					);
             }',
            'error' => "function(request, status, error){
				 	$('#dialogPackage').html(status+'('+error+')'+': '+ request.responseText );
					}",

        )) ?>;
        return false;
    }

    function addInplantCharges() {
        <?php echo CHtml::ajax(array(
            'url' => $this->createUrl('request/inplantcharge', array('id' => $model->id)),
            'data' => "js:$(this).serialize()",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data){
                if (data.status == 'failure'){
                    $('#dialogInplant').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogInplant form').submit(addInplantCharges);
                }
                else{
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogInplant').html(data.div);
                    setTimeout(\"$('#dialogInplant').dialog('close') \",1000);
					
                }
 
            }",
            'beforeSend' => 'function(jqXHR, settings){
                    $("#dialogInplant").html(
						\'<div class="loader">' . $image . '<br\><br\>Generating form.<br\> Please wait...</div>\'
					);
         	}',
            'error' => "function(request, status, error){
                $('#dialogInplant').html(request.responseText );
                console.log(request);
			}",
        )) ?>;
        return false;
    }

    function addAdditional() {
        <?php echo CHtml::ajax(array(
            'url' => $this->createUrl('request/additional', array('id' => $model->id)),
            'data' => "js:$(this).serialize()",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data){
                if (data.status == 'failure'){
                    $('#dialogAdditional').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogAdditional form').submit(addAdditional);
                }
                else{
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogAdditional').html(data.div);
                    setTimeout(\"$('#dialogAdditional').dialog('close') \",1000);
					
                }
 
            }",
            'beforeSend' => 'function(jqXHR, settings){
                    $("#dialogAdditional").html(
						\'<div class="loader">' . $image . '<br\><br\>Generating form.<br\> Please wait...</div>\'
					);
         	}',
            'error' => "function(request, status, error){
				 	$('#dialogAdditional').html(request.responseText );
					console.log(request);
			}",
        )) ?>;
        return false;
    }

    function addRemarks() {
        <?php echo CHtml::ajax(array(
            'url' => $this->createUrl('request/remarks', array('id' => $model->id)),
            'data' => "js:$(this).serialize()",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data){
                if (data.status == 'failure'){
                    $('#dialogRemarks').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogRemarks form').submit(addRemarks);
                }
                else{
                    $.fn.yiiGridView.update('analysis-grid');
					$('#dialogRemarks').html(data.div);
                    setTimeout(\"$('#dialogRemarks').dialog('close') \",1000);
					
                }
 
            }",
            'beforeSend' => 'function(jqXHR, settings){
                    $("#dialogRemarks").html(
						\'<div class="loader">' . $image . '<br\><br\>Generating form.<br\> Please wait...</div>\'
					);
         	}',
            'error' => "function(request, status, error){
                $('#dialogRemarks').html(request.responseText );
                console.log(request);
			}",
        )) ?>;
        return false;
    }
    function duplicateRequest() {
        <?php 
            echo CHtml::ajax(array(
                'url' => $this->createUrl('request/duplicate', array('id' => $model->id, 'requestRefNum' => $model->requestRefNum)),
                'data' => "js:$(this).serialize()",
                'type' => 'post',
                'dataType' => 'json',
                'success' => "function(data) {
                    if (data.status == 'failure') {
                        $('#dialogDuplicate').html(data.div);
                        // Here is the trick: on submit-> once again this function!
                        $('#dialogDuplicate form').submit(duplicateRequest);
                    }
                    else {
                        $.fn.yiiGridView.update('sample-grid');
                        $.fn.yiiGridView.update('analysis-grid');
                        location.reload();
                        $('#dialogDuplicate').html(data.div);
                        setTimeout(\"$('#dialogSample').dialog('close') \",1000);
                        
                    }
                }",
                'beforeSend' => 'function(jqXHR, settings){
                    $("#dialogDuplicate").html(
                        \'<div class="loader">'.$image.'<br\><br\>Processing...<br\> Please wait...</div>\'
                    );
                }',
                'error' => "function(request, status, error){
                    $('#dialogDuplicate').html(request.responseText );
                    console.log(request);
                }",

            )) 
        ?>;
        return false;
    }
    function generateSampleCode() {
        <?php
        echo CHtml::ajax(array(
            'url' => $this->createUrl('sample/generateSampleCode', array('id' => $model->id)),
            //'data'=> "js:$(this).serialize()+ '&id='+id",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data)
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
                    setTimeout(\"$('#dialogSampleCode').dialog('close') \",1000);
					location.reload();
                }
            }",
            'beforeSend' => 'function(jqXHR, settings){
                    $("#dialogSampleCode").html(
						\'<div class="loader">' . $image . '<br\><br\>Processing.<br\> Please wait...</div>\'
					);
            }',
            'error' => "function(request, status, error){
				 	$('#dialogSampleCode').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
					}",
        )) ?>;
        return false;
    }

    function confirmGenerateSampleCode() {
        <?php
        echo CHtml::ajax(array(
            'url' => $this->createUrl('sample/confirm', array('id' => $model->id)),
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data)
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
            'beforeSend' => 'function(jqXHR, settings){
		                    $("#dialogConfirmGenerate").html(
								\'<div class="loader">' . $image . '<br\><br\>Retrieving record.<br\> Please wait...</div>\'
							);
		            }',
            'error' => "function(request, status, error){
						 	$('#dialogConfirmGenerate').html(status+'('+error+')'+': '+ request.responseText+ ' {'+error.code+'}' );
							}",
        )) ?>;
        return false;
    }

    function setPrintFormat(form, format) {

        <?php
        echo CHtml::ajax(array(
            'url' => $this->createUrl('/config/setPrintFormat'),
            'data' => "js:$(this).serialize()+ '&form='+form+ '&format='+format",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data)
		            	{
		            		location.reload();
		            	}"
        )) ?>;
        return false;

    }
</script>