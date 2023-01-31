<?php
/* @var $this SuppliesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Supplies',
);

$this->menu=array(
	array('label'=>'Create Supplies', 'url'=>array('create')),
	array('label'=>'Manage Supplies', 'url'=>array('admin')),
);
?>


<div class="row-fluid">
	<div class="span4">
			<?php
			$this->beginWidget('zii.widgets.CPortlet',
			 	array('title'=>"<i class='icon-bell'></i><strong>sample STOCKS chart</strong>"),
				array('class'=>'portletbold announcewindow'));
				
			$this->widget('ext.highcharts.HighchartsWidget', array(
		   //'dataProvider'=>$pieProgramDataProvider,
		   //'summaryText'=>false,
		   //'template'=>'{items}',
			'options'=> array(
					'chart' => array(
						'defaultSeriesType' => 'line',
						'style' => array(
							'fontFamily' => 'Verdana, Arial, Helvetica, sans-serif',
					),
				),
		  'plotOptions'=> array(
                'column'=>array(
                    'stacking'=> 'false',
					//'minPointLength'=>1,
					//'treshold'=> 1,
                    'dataLabels'=> array(
                        //'enabled'=> true,
                        //'color'=> (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    ),                              
                ),
                'bar'=>array(
                	'dataLabels'=>array(
                		'enabled'=>true
                		)
                	),
                'line'=> array(
		            'dataLabels'=> array(
		                'enabled'=> true
		            ),
		            'enableMouseTracking'=> false
		        )

            ),
			'credits' => array('enabled' => false),
			'title' => array('text' => 'To be determine'),
			'xAxis' => array(
				 //'categories' => 'name'
				 'categories' =>array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"),
			),
			'yAxis' => array(
	             'title' => array('text' => 'No. of Scholars'),
				 //'tickInterval'=>50,
				 //'endOnTick'=> false,
				 //'min'=> 0,
				 'maxPadding'=> 0.0,
				 'stackLabels'=> array(
	                    'enabled'=> true,
	                    'style'=> array(
	                        'fontWeight'=> 'bold',
	                        'color'=> 'gray'
	                    )
	                ),			 
				 
			),
			'series' =>array(array('name'=>"aaa",'data'=>array(20,30,40)),array('name'=>"aca",'data'=>array(15,23,32))),		 
	       )

	  
	    ));

		$this->endWidget();?> 
	</div>	
	<div class="span4">
			<?php
			$this->beginWidget('zii.widgets.CPortlet',
			 	array('title'=>"<i class='icon-bell'></i><strong>sample STOCKS chart</strong>"),
				array('class'=>'portletbold announcewindow'));
				
			$this->widget('ext.highcharts.HighchartsWidget', array(
		   //'dataProvider'=>$pieProgramDataProvider,
		   //'summaryText'=>false,
		   //'template'=>'{items}',
			'options'=> array(
						'chart' => array(
							'defaultSeriesType' => 'bar',
							'style' => array(
								'fontFamily' => 'Verdana, Arial, Helvetica, sans-serif',
						),
					),
			  'plotOptions'=> array(
	                'column'=>array(
	                    'stacking'=> 'false',
						//'minPointLength'=>1,
						//'treshold'=> 1,
	                    'dataLabels'=> array(
	                        //'enabled'=> true,
	                        //'color'=> (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
	                    ),
	                ),
	                'bar'=>array(
	                	'dataLabels'=>array(
	                		'enabled'=>true
	                		)
	                	),
	                'line'=> array(
			            'dataLabels'=> array(
			                'enabled'=> true
			            ),
			            'enableMouseTracking'=> false
			        )

	            ),
			'credits' => array('enabled' => false),
			'title' => array('text' => 'To be determine'),
			'xAxis' => array(
				 //'categories' => 'name'
				 'categories' =>array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"),
			),
			'yAxis' => array(
	             'title' => array('text' => 'No. of Scholars'),
				 //'tickInterval'=>50,
				 //'endOnTick'=> false,
				 //'min'=> 0,
				 'maxPadding'=> 0.0,
				 'stackLabels'=> array(
	                    'enabled'=> true,
	                    'style'=> array(
	                        'fontWeight'=> 'bold',
	                        'color'=> 'gray'
	                    )
	                ),			 
				 
			),
			'series' =>array(array('name'=>"aaa",'data'=>array(20,30,40)),array('name'=>"aca",'data'=>array(15,23,32))),		 
	       )

	  
	    ));

		$this->endWidget();?> 
	</div>	
	<div class="span4">
			<?php
			$this->beginWidget('zii.widgets.CPortlet',
			 	array('title'=>"<i class='icon-bell'></i><strong>sample STOCKS chart</strong>"),
				array('class'=>'portletbold announcewindow'));
				
			$this->widget('ext.highcharts.HighchartsWidget', array(
		   //'dataProvider'=>$pieProgramDataProvider,
		   //'summaryText'=>false,
		   //'template'=>'{items}',
			'options'=> array(
						'chart' => array(
							'defaultSeriesType' => 'pie',
							'style' => array(
								'fontFamily' => 'Verdana, Arial, Helvetica, sans-serif',
						),
					),
			  'plotOptions'=> array(
	                'column'=>array(
	                    'stacking'=> 'false',
						//'minPointLength'=>1,
						//'treshold'=> 1,
	                    'dataLabels'=> array(
	                        //'enabled'=> true,
	                        //'color'=> (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
	                    ),
	                ),
	                'bar'=>array(
	                	'dataLabels'=>array(
	                		'enabled'=>true
	                		)
	                	),
	                'line'=> array(
			            'dataLabels'=> array(
			                'enabled'=> true
			            ),
			            'enableMouseTracking'=> false
			        )

	            ),
			'credits' => array('enabled' => false),
			'title' => array('text' => 'To be determine'),
			'xAxis' => array(
				 //'categories' => 'name'
				 'categories' =>array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"),
			),
			'yAxis' => array(
	             'title' => array('text' => 'No. of Scholars'),
				 //'tickInterval'=>50,
				 //'endOnTick'=> false,
				 //'min'=> 0,
				 'maxPadding'=> 0.0,
				 'stackLabels'=> array(
	                    'enabled'=> true,
	                    'style'=> array(
	                        'fontWeight'=> 'bold',
	                        'color'=> 'gray'
	                    )
	                ),			 
				 
			),
			'series' =>array(array('name'=>"aca",'data'=>array(15,23,32))),		 
	       )

	  
	    ));

		$this->endWidget();?> 
	</div>	
</div>
<div class="row-fluid">
	<div class="span6">
		<h1>Supplies - Priority Ranking</h1>

		
		<?php
		
		$i=0;
		foreach ($rankItems as $item) {
			 echo"<i>". ++$i.". <strong>$item->stockID </strong></i><br>";
			echo "<h3>".$item->stocks->name." - ". $item->qty."</h3>";
		}
		?>
	</div>
	<div class="span6">
	<?php
		$this->beginWidget('zii.widgets.CPortlet',
		 	array('title'=>"<i class='icon-bell'></i><strong>REORDER POINT</strong>"),
			array('class'=>'portletbold announcewindow'));
		 
		//put cdetailview here for stocks 
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'stocks-grid',
			'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
			'dataProvider'=>$StocksReorder,
			'columns'=>array(
				// 'id',
				'stockCode',
				//'supplyID',
				'name',
				//'description',
				//'manufacturer',
				// 'unit',
				'quantity',
				// 'daterecieved',
				// 'dateopened',
				// 'expiry_date',
				// 'recieved_by',
				// 'threshold_limit',
				// 'location',
				// 'batch_number',
				// 'supplierID',
				// 'amount',
				// array(
				// 	'class'=>'CButtonColumn',
				// ),
			),
		)); 
		$this->endWidget();?> 

		<?php
		$this->beginWidget('zii.widgets.CPortlet',
		 	array('title'=>"<i class='icon-bell'></i><strong>EXPIRED AND NEARLY EXPIRED STOCKS</strong>"),
			array('class'=>'portletbold announcewindow'));
		 
		//put cdetailview here for stocks 
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'stocks-grid',
			'itemsCssClass'=>'table table-hover table-striped table-bordered table-condensed',
			'dataProvider'=>$StocksExpired,
			'columns'=>array(
				// 'id',
				'stockCode',
				//'supplyID',
				'name',
				//'description',
				//'manufacturer',
				
				// 'unit',
				// 'quantity',
				// 'daterecieved',
				// 'dateopened',
				'expiry_date',
				// 'recieved_by',
				// 'threshold_limit',
				// 'location',
				// 'batch_number',
				// 'supplierID',
				// 'amount',
				
				// array(
				// 	'class'=>'CButtonColumn',
				// ),
			),
		)); 
		$this->endWidget();?> 
	</div>
</div>
