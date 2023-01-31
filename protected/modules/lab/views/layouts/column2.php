<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

  <div class="row-fluid">
	<div class="span3">
		<div class="sidebar-nav">
        
		  <?php $this->widget('zii.widgets.CMenu', array(
			/*'type'=>'list',*/
			'encodeLabel'=>false,
			'items'=>array(
				array('label'=>'<i class="icon icon-home"></i> Dashboard', 'url'=>array('/lab/default/index')),
				array('label'=>'<i class="icon icon-globe"></i> Requests', 'url'=>array('/lab/request/admin')),
		        // array('label'=>'<i class="icon icon-globe"></i> Referrals', 'url'=>array('/lab/referral/admin')),
		        // array('label'=>'<i class="icon icon-inbox"></i> Sample Register', 'url'=>array('/lab/sample/admin')),
		        // array('label'=>'<i class="icon icon-tasks"></i> Test Report', 'url'=>array('/lab/testreport/admin')),
				// array('label'=>'<i class="icon icon-file"></i> Order of Payment', 'url'=>array('/lab/orderofpayment/admin')	),
				array('label'=>'<i class="icon icon-briefcase"></i> Packages', 'url'=>array('/lab/package/admin')	),
				array('label'=>'<i class="icon icon-filter"></i>Services', 'url'=>array('/lab/test/admin')	),
				array('label'=>'<i class="icon icon-user"></i> Customers', 'url'=>array('/lab/customer/admin')	),
				// array('label'=>'<i class="icon icon-book"></i> Sample Templates', 'url'=>array('/lab/samplename/admin')	),
				array('label'=>'<i class="icon icon-list-alt"></i> Reports', 'url'=>array('/lab/report/index')),
				array('label'=>'<i class="icon icon-list-alt"></i> Quotations', 'url'=>array('/lab/quotation/index')),
			),
			));?>
		</div>
        <hr>
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
        <?php //$defaultImgLab=CHtml::image(Yii::app()->request->baseUrl.'/images/iso17025-accredited.png','lab-sidebar-image');echo Yii::app()->params['Lab']['sidebarImage']?CHtml::image(Yii::app()->request->baseUrl .'/images/'.Yii::app()->params['Lab']['sidebarImage'],'lab-sidebar-image',array('class'=>'sidebar-image')):$defaultImgLab;?>
        </div>
		
    </div><!--/span-->
    
    <div class="span9">
    
    <!-- Include content pages -->
    <?php echo $content; ?>

	</div><!--/span-->
  </div><!--/row-->


<?php $this->endContent(); ?>
