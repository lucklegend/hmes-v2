<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
  <div class="row-fluid">
	<div class="span3">
		<div class="sidebar-nav">    
		  <?php $this->widget('zii.widgets.CMenu', array(
			'encodeLabel'=>false,
			'items'=>array(
			  	array('label'=>'<i class="icon icon-home"></i> Dashboard', 'url'=>array('/sampletagging/sample/scheduling')),
        	array('label'=>'<i class="icon icon-tags"></i> Sample Tagging', 'url'=>array('/sampletagging/default/index')),
          array('label'=>'<i class="icon icon-inbox"></i> Sample Register', 'url'=>array('/sampletagging/sample/admin')),
			  //	array('label'=>'<i class="icon icon-globe"></i> Requests', 'url'=>array('/lab/request/admin')),
				//	array('label'=>'<i class="icon icon-list-alt"></i> Schedule of Samples', 'url'=>array('/sampletagging/sample/samplescheduling')),
			//		array('label'=>'<i class="icon icon-wrench"></i> Analyst Workloads', 'url'=>array('/sampletagging/sample/workloads')),
		//			array('label'=>'<i class="icon icon-folder-open"></i> Booking of Samples', 'url'=>array('/sampletagging/sample/booking')),
		//			array('label'=>'<i class="icon icon-user"></i> Analyst Schedule', 'url'=>array('/sampletagging/sample/scheduling')),
    //      array('label'=>'<i class="icon icon-globe"></i> Referrals', 'url'=>array('/lab/referral/admin')),		
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
        <?php $defaultImgLab=CHtml::image(Yii::app()->request->baseUrl.'/images/iso17025-accredited.png','lab-sidebar-image');echo Yii::app()->params['Lab']['sidebarImage']?CHtml::image(Yii::app()->request->baseUrl .'/images/'.Yii::app()->params['Lab']['sidebarImage'],'lab-sidebar-image',array('class'=>'sidebar-image')):$defaultImgLab;?>
          
        </div>
		
    </div><!--/span-->
    <div class="span9">
    <?php /*if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links'=>$this->breadcrumbs,
			'homeLink'=>CHtml::link('Dashboard'),
			'htmlOptions'=>array('class'=>'breadcrumb')
        )); ?><!-- breadcrumbs -->
    <?php endif*/?>
    
    <!-- Include content pages -->
    <?php echo $content; ?>
	</div><!--/span-->
  </div><!--/row-->
<?php $this->endContent(); ?>
