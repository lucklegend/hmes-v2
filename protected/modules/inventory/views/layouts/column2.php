<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

  <div class="row-fluid">
	<div class="span3">
		<div class="sidebar-nav">
        
		  <?php $this->widget('zii.widgets.CMenu', array(
			/*'type'=>'list',*/
			'encodeLabel'=>false,
			'items'=>array(
        array('label'=>'<i class="icon icon-file"></i> Dashboard - Supply', 'url'=>array('/inventory/supplies/index')  ),
				array('label'=>'<i class="icon icon-book"></i> Physical Supplies', 'url'=>array('/inventory/supplies/admin')	),
				array('label'=>'<i class="icon icon-list-alt"></i> Stocks', 'url'=>array('/inventory/stocks/admin')	),
        array('label'=>'<i class="icon icon-tint"></i> Consumption', 'url'=>array('/inventory/consumptions/admin')  ),
				array('label'=>'<i class="icon icon-minus"></i> Withdrawal', 'url'=>array('/inventory/consumptions/index')	),
				//array('label'=>'<i class="icon icon-flag"></i> Locations', 'url'=>array('/inventory/locations/admin')	),
				array('label'=>'<i class="icon icon-user"></i> Suppliers', 'url'=>array('/inventory/suppliers/admin')	),
         array('label'=>'<i class="icon icon-file"></i> Dashboard - Equipment', 'url'=>array('/inventory/equipment/index')  ),
         array('label'=>'<i class="icon icon-file"></i> Equipment', 'url'=>array('/inventory/equipment/admin')  ),
         // array('label'=>'<i class="icon icon-file"></i> Calendar', 'url'=>array('/inventory/equipment/calendar')  ),
        array('label'=>'<i class="icon icon-book"></i> Calibration', 'url'=>array('/inventory/equipmentcalibration/admin')  ),
        array('label'=>'<i class="icon icon-list-alt"></i> Maintenance', 'url'=>array('/inventory/equipmentmaintenance/admin')  ),
        array('label'=>'<i class="icon icon-tint"></i> Usage', 'url'=>array('/inventory/equipmentusage/admin')  ),
         array('label'=>'<i class="icon icon-tint"></i> Reports', 'url'=>array('/inventory/report/index')  ),
				//array('label'=>'<i class="icon icon-star"></i> Manufacturers/Brands', 'url'=>array('/inventory/manufacturers/admin')	),
				//array('label'=>'<i class="icon icon-glass"></i> Container Types', 'url'=>array('/inventory/containertypes/admin')	),
				//array('label'=>'<i class="icon icon-filter"></i> Unit of Measures', 'url'=>array('/inventory/unittypes/admin')	),
			    // array('label'=>'<i class="icon icon-refresh"></i> Reorder Rules', 'url'=>array('/inventory/reorderrules/admin')	),
           //     array('label'=>'<i class="icon icon-barcode"></i> Print Barcodes', 'url'=>array('/inventory/unittypes/admin')	),
            ),
			));?>
		</div>
        <br>
        <!-- table class="table table-striped table-bordered">
          <tbody>
            <tr>
              <td width="50%">Bandwith Usage</td>
              <td>
              	<div class="progress progress-danger">
                  <div class="bar" style="width: 80%"></div>
                </div>
              </td>
            </tr>
            <tr>
              <td>Disk Spage</td>
              <td>
             	<div class="progress progress-warning">
                  <div class="bar" style="width: 60%"></div>
                </div>
              </td>
            </tr>
            <tr>
              <td>Conversion Rate</td>
              <td>
             	<div class="progress progress-success">
                  <div class="bar" style="width: 40%"></div>
                </div>
              </td>
            </tr>
            <tr>
              <td>Closed Sales</td>
              <td>
              	<div class="progress progress-info">
                  <div class="bar" style="width: 20%"></div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
		<div class="well">
        
            <dl class="dl-horizontal">
              <dt>Account status</dt>
              <dd>$1,234,002</dd>
              <dt>Open Invoices</dt>
              <dd>$245,000</dd>
              <dt>Overdue Invoices</dt>
              <dd>$20,023</dd>
              <dt>Converted Quotes</dt>
              <dd>$560,000</dd>
              
            </dl>
      </div-->
		<div id="sidebar">
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'<span class="icon icon-sitemap_color">Operations</span>',
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'operations'),
			));
			$this->endWidget();
		?>
        <!--/div-->
        <?php //echo CHtml::image(Yii::app()->baseUrl.'/images/iso9001-2008certified.png','iso9001:2008');?>
        <?php $defaultImgCashier=CHtml::image(Yii::app()->request->baseUrl.'/images/iso9001-2008certified.png','cashier-sidebar-image');echo Yii::app()->params['Cashier']['sidebarImage']?CHtml::image(Yii::app()->request->baseUrl .'/images/'.Yii::app()->params['Cashier']['sidebarImage'],'cashier-sidebar-image',array('class'=>'sidebar-image')):$defaultImgCashier;?>
        </div>
		
    </div><!--/span-->
    <div class="span9">
    
    <?php /*if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links'=>$this->breadcrumbs,
			'homeLink'=>CHtml::link('Dashboard'),
			'htmlOptions'=>array('class'=>'breadcrumb')
        )); */?><!-- breadcrumbs -->
    <?php //endif?>
    
    <!-- Include content pages -->
    <?php echo $content; ?>

	</div><!--/span-->
  </div><!--/row-->


<?php $this->endContent(); ?>
