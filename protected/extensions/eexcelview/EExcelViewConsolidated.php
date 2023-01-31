<?php
	Yii::import('zii.widgets.grid.CGridView');
	/**
	* @author Nikola Kostadinov
	* @license MIT License
	* @version 0.3
	*/	
	class EExcelViewConsolidated extends CGridView
	{
		//Document properties
		//public $creator = 'Nikola Kostadinov';
		public $creator = 'Unified Laboratory Information Management System';
		public $title = null;
		public $subject = 'Subject';
		public $description = '';
		public $category = '';
		
		//the PHPExcel object
		public $objPHPExcel = null;
		public $libPath = 'ext.phpexcel.Classes.PHPExcel'; //the path to the PHP excel lib

		//config
		public $autoWidth = true;
		//public $exportType = 'Excel5';
		public $exportType = 'Excel2007';
		public $disablePaging = true;
		public $filename = null; //export FileName
		public $stream = true; //stream to browser
		public $grid_mode = 'grid'; //Whether to display grid ot export it to selected format. Possible values(grid, export)
		public $grid_mode_var = 'grid_mode'; //GET var for the grid mode
		
		//modified:: patterned from GroupGridView extension
		public $mergeColumns = array();
		//list of columns on which change extrarow will be triggered
		public $extraRowColumns = array(); //used for group header
		//list of columns on which change extrarow header will be triggered
		public $extraRowColumnsGroupHeader = array();
		//list of columns on which change extrarow footer will be triggered
		public $extraRowColumnsGroupFooter = array();	
		//expression to get value shown in extrarow group header
		public $extraRowExpressionGroupHeader;
		//expression to get value shown in extrarow group footer
		public $extraRowExpressionGroupFooter;	
		//expression to get value shown in extrarow
		public $extraRowExpression;
		//position of extraRow relative to group: 'above' | 'below' 
		public $extraRowPos = 'above';
		//totals expression: function($data, $row, &$totals)
		public $extraRowTotals;
		//number of rows already inserted;
		public $numRowsInserted=0;
		//modified by RBG
		public $extraRowColSpan;
		public $extraRowHeaderColSpan;
		public $extraRowFooterColSpan;
		public $hiddenColumns; // in case we have a hidden column, subtract total number of colspan

		//array with groups
		private $_groups = array();
	
		//array with groups header
		private $_groupsHeader = array();
	
		//array with groups header
		private $_groupsFooter = array();		
		//determine row where header will change
		public $rowChanges=array();
		//determine row where headerRows will change
		public $rowHeaderChanges=array();
		//determine row where footerRows will change
		public $rowFooterChanges=array();		
		//additional document config
		public $semester;
		public $region;
		public $scholarshipProgram;
		
		//buttons config
		public $exportButtonsCSS = 'summary';
		public $exportButtons = array('Excel2007');
		public $exportText = 'Export to: ';

		//callbacks
		public $onRenderHeaderCell = null;
		public $onRenderDataCell = null;
		public $onRenderFooterCell = null;
		
		//mime types used for streaming
		public $mimeTypes = array(
			'Excel5'	=> array(
				'Content-type'=>'application/vnd.ms-excel',
				'extension'=>'xls',
				'caption'=>'Excel(*.xls)',
			),
			'Excel2007'	=> array(
				'Content-type'=>'application/vnd.ms-excel',
				'extension'=>'xlsx',
				'caption'=>'Excel(*.xlsx)',				
			),
			'PDF'		=>array(
				'Content-type'=>'application/pdf',
				'extension'=>'pdf',
				'caption'=>'PDF(*.pdf)',								
			),
			'HTML'		=>array(
				'Content-type'=>'text/html',
				'extension'=>'html',
				'caption'=>'HTML(*.html)',												
			),
			'CSV'		=>array(
				'Content-type'=>'application/csv',			
				'extension'=>'csv',
				'caption'=>'CSV(*.csv)',												
			)
		);

		public function init()
		{
			if(isset($_GET[$this->grid_mode_var]))
				$this->grid_mode = $_GET[$this->grid_mode_var];
			if(isset($_GET['exportType']))
				$this->exportType = $_GET['exportType'];
				
			$lib = Yii::getPathOfAlias($this->libPath).'.php';
			if($this->grid_mode == 'export' and !file_exists($lib)) {
				$this->grid_mode = 'grid';
				Yii::log("PHP Excel lib not found($lib). Export disabled !", CLogger::LEVEL_WARNING, 'EExcelview');
			}
				
			if($this->grid_mode == 'export')
			{			
				$this->title = $this->title ? $this->title : Yii::app()->getController()->getPageTitle();
				$this->initColumns();
				//parent::init();
				//Autoload fix
				spl_autoload_unregister(array('YiiBase','autoload'));             
				Yii::import($this->libPath, true);
				$this->objPHPExcel = new PHPExcel();
				//$this->activeSheet = $this->objPHPExcel->getActiveSheet();
				
				spl_autoload_register(array('YiiBase','autoload'));  
				// Creating a workbook
				$this->objPHPExcel->getProperties()->setCreator($this->creator);
				$this->objPHPExcel->getProperties()->setTitle($this->title);
				$this->objPHPExcel->getProperties()->setSubject($this->subject);
				$this->objPHPExcel->getProperties()->setDescription($this->description);
				$this->objPHPExcel->getProperties()->setCategory($this->category);
			} else
				parent::init();
		}

		public function renderHeader()
		{
			$a=0;
			foreach($this->columns as $column)
			{
				$a=$a+1;
				if($column instanceof CButtonColumn)
					$head = $column->header;
				elseif($column->header===null && $column->name!==null)
				{
					if($column->grid->dataProvider instanceof CActiveDataProvider)
						$head = $column->grid->dataProvider->model->getAttributeLabel($column->name);
					else
						$head = $column->name;
				} else
					$head =trim($column->header)!=='' ? $column->header : $column->grid->blankDisplay;
				
				//$this->objPHPExcel->getActiveSheet()->setCellValue( "A1" , "SUMMARY OF ACCOMPLISHMENT : ");
				$this->objPHPExcel->getActiveSheet()->mergeCells("A1:N1");
				$this->objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->objPHPExcel->getActiveSheet()->setCellValue( "A1" , Yii::app()->Controller->showRstl(Yii::app()->Controller->getRstlId())." Summary of Accomplishments");
				//$this->objPHPExcel->getActiveSheet()->setCellValue( "A2" , "PERIOD COVERED : ");
				$this->objPHPExcel->getActiveSheet()->mergeCells("A2:N2");
				$this->objPHPExcel->getActiveSheet()->getStyle('A2:N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->objPHPExcel->getActiveSheet()->setCellValue( "A2" , Yii::app()->Controller->getFromDate()." - ".Yii::app()->Controller->getToDate());
				$this->objPHPExcel->getActiveSheet()->mergeCells("A3:N3");
				$this->objPHPExcel->getActiveSheet()->getStyle('A3:N3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$this->objPHPExcel->getActiveSheet()->setCellValue( "A3" , Yii::app()->Controller->getLab());
				$this->objPHPExcel->getActiveSheet()->getStyle('A1:N3')->getFont()->setBold(true);
				$this->objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->setSize(12.5);
				$this->objPHPExcel->getActiveSheet()->getStyle('A2:N3')->getFont()->setSize(11.5);
				
				//set style: alignment 
				$this->objPHPExcel->getActiveSheet()->getStyle('A5:N6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
				$this->objPHPExcel->getActiveSheet()->getStyle('A5:N6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$this->objPHPExcel->getActiveSheet()->getStyle('A5:N6')->getFont()->setBold(true);
				$this->objPHPExcel->getActiveSheet()->getStyle('A5:N6')->getFont()->setSize(9);
				
				//set style: border
				$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a)."5:".$this->columnName($a)."6")->applyFromArray($this->borderArray());
				
				//$cell = $this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a)."1" ,$head, true);
				//modified
				$cell = $this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a)."5" ,$head, true);
				$cell = $this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a)."6" ,$head, true);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension($this->columnName($a))->setWidth(10.75);
				$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a)."5:".$this->columnName($a)."6")->getAlignment()->setWrapText(true);				
				
				if(is_callable($this->onRenderHeaderCell))
					call_user_func_array($this->onRenderHeaderCell, array($cell, $head));				
			}			
		}

		public function renderBody()
		{
			if($this->disablePaging) //if needed disable paging to export all data
				$this->dataProvider->pagination = false;

			$data=$this->dataProvider->getData();
			$n=count($data);

			if(!empty($this->mergeColumns) || !empty($this->extraRowColumnsGroupHeader)) {
				$this->groupByColumnsHeader();			
			}
				
			if(!empty($this->mergeColumns) || !empty($this->extraRowColumns)) {
				$this->groupByColumns();
			}
			
			if(!empty($this->mergeColumns) || !empty($this->extraRowColumnsGroupFooter)) {
				$this->groupByColumnsFooter();			
			}		

			if($n>0)
			{
				for($row=0;$row<$n;++$row){
					$this->renderRow($row);
					//modified here
					/*foreach($this->extraRowColumns as $key=>$colName){
						$currentValue = CHtml::encode(CHtml::value($data[$row], $colName));
						$rowChange=$this->rowChangeOccured($row-1, $colName, $currentValue);
					}
					//array_push($this->rowChanges, $rowChange);
					if(!empty($rowChange)){
						array_push($this->rowChanges, $rowChange);
					}*/

				}
			}
            return $n;
		}

		public function renderRow($row)
		{
			/*
			modified by RBG
			*/
			
			//$extraRowFooterEdge = null;
			$extraRowHeaderEdge = null;
			//try this extraRowColumnsGroupHeader
			if(count($this->extraRowColumnsGroupHeader)) {
				$headerName = $this->extraRowColumnsGroupHeader[0];
				$extraRowHeaderEdge = $this->isGroupEdgeHeader($headerName, $row);
				if($this->extraRowPos == 'above' && isset($extraRowHeaderEdge['start'])) {
					$extraRowHeaderChange=$this->renderExtraRowHeader($row, $extraRowHeaderEdge['group']['totals']); 
					if(!empty($extraRowHeaderChange)){
						$this->numRowsInserted++;
						$extraRowHeaderChange['numRowsInserted']=$this->numRowsInserted;
						array_push($this->rowHeaderChanges, $extraRowHeaderChange);
					}
				}
	
			
				
				//for SUBTOTAL
				if(count($this->extraRowColumnsGroupFooter)){
					$footerColName = $this->extraRowColumnsGroupFooter[0];
					$extraRowFooterEdge = $this->isGroupEdgeFooter($footerColName, $row);
					if(isset($extraRowFooterEdge['end'])) {
						$extraRowFooterChange=$this->renderExtraRowFooter($row, $extraRowFooterEdge['group']['totals']);
							if(!empty($extraRowFooterChange)){
								$this->numRowsInserted++;
								$extraRowFooterChange['numRowsInserted']=$this->numRowsInserted;
								array_push($this->rowFooterChanges, $extraRowFooterChange);
							}										
					}
				}
				
			}
			
			
			//$data=$this->dataProvider->getData();			
			$data=$this->dataProvider->data;

			$a=0;
			foreach($this->columns as $n=>$column)
			{
				if($column instanceof CLinkColumn)
				{
					if($column->labelExpression!==null)
						$value=$column->evaluateExpression($column->labelExpression,array('data'=>$data[$row],'row'=>$row));
					else
						$value=$column->label;
				} elseif($column instanceof CButtonColumn)
					$value = ""; //Dont know what to do with buttons
				elseif($column->value!==null) 
					$value=$this->evaluateExpression($column->value ,array('data'=>$data[$row],'row'=>$row,'dataColumn'=>$column));
					//$value=$this->evaluateExpression($column->value ,array('data'=>$data[$row],'row'=>$row, 'dataColumn'=>1));
					//$value=$column->renderDataCellContent($row,$data);
					//$value=$data[$row];
				elseif($column->name!==null) { 
					//$value=$data[$row][$column->name];
					$value= CHtml::value($data[$row], $column->name);
				    $value=$value===null ? "" : $column->grid->getFormatter()->format($value,'raw');
                }

				$a++;
				//$cell = $this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a).($row+2) , strip_tags($value), true);
				//modified
				$cell = $this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a).($row+7) , strip_tags($value), true);
				
				//set first two columns alignment CENTER
				$this->objPHPExcel->getActiveSheet()->getStyle('A'.($row+7))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
				$this->objPHPExcel->getActiveSheet()->getStyle('B'.($row+7))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);			
				
				//number formats:: set comma separated with two decimals
				//except first two columns						
				$this->objPHPExcel->getActiveSheet()->getStyle("C".($a+7).":H".($row+7))->getNumberFormat()->setFormatCode("#,##0;(#,##0)");
				$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a+8).($row+7))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a+7).($row+7))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a+2).($row+7))->getFont()->setSize(10);
				
				//apply borders::LEFT AND RIGHT
				$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a).($row+7))->applyFromArray($this->borderArray());

				if(is_callable($this->onRenderDataCell))
					call_user_func_array($this->onRenderDataCell, array($cell, $data[$row], $value));
			}
			
			$this->objPHPExcel->getActiveSheet()->getStyle('A'.($row+7))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
			//$this->objPHPExcel->getActiveSheet()->getStyle('A'.($row+7).':N'.($row+7))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		}

		public function renderFooter($row)
		{
			$a=0;
			foreach($this->columns as $n=>$column)
			{
				$a=$a+1;
                if($column->footer OR $column->footer==0)
				//to make sure 0 values of footer will not be evaluated as FALSE
                {
					//$footer =trim($column->footer)!=='' ? $column->footer : $column->grid->blankDisplay;
					//$footer =trim($column->footer)!=='' ? $column->footer : $column->grid->blankDisplay;
					$footer =trim($column->footer)!=='' ? $column->footer : '';

				    //$cell = $this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a).($row+2) ,$footer, true);
					//modified
					$cell = $this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a).($row+7) ,$footer, true);
					
					$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a).($row+7))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a).($row+7))->getFont()->setBold(true);
					//number formats:: set comma separated with two decimals
					//except first merged column
					$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a+1).($row+7))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a+1).($row+7))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					
					//apply borders
					$this->objPHPExcel->getActiveSheet()->getStyle($this->columnName($a).($row+6))->applyFromArray($this->borderArray());
					
				    if(is_callable($this->onRenderFooterCell))
					    call_user_func_array($this->onRenderFooterCell, array($cell, $footer));				
                }
			}
			
			//merge columns
			//$this->objPHPExcel->getActiveSheet()->mergeCells("A".($row+8).":B".($row+8));
			//$this->objPHPExcel->getActiveSheet()->getStyle("A".($row+8).":B".($row+8))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
			//overwrite value for merged cells
			//$this->objPHPExcel->getActiveSheet()->setCellValue("A".($row+7) ,"GRAND TOTAL");
			$this->objPHPExcel->getActiveSheet()->getStyle("A".($row+7))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);	
			$this->objPHPExcel->getActiveSheet()->getStyle("A".($row+7))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->objPHPExcel->getActiveSheet()->getStyle("A".($row+7))->getFont()->setBold(true);
			$this->objPHPExcel->getActiveSheet()->getStyle("A".($row+7))->getFont()->setSize(9);
			$this->objPHPExcel->getActiveSheet()->getRowDimension($row+7)->setRowHeight(20);
			//grand total fill
			$this->objPHPExcel->getActiveSheet()->getStyle("A".($row+7).":N".($row+7))->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('3e3e3e'); //b7c6ff 99aeff
			$this->objPHPExcel->getActiveSheet()->getStyle("A".($row+7).":N".($row+7))->applyFromArray($this->borderArray());
			$this->objPHPExcel->getActiveSheet()->getStyle("A".($row+7).":N".($row+7))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);	
			//format for not amount
			//$this->objPHPExcel->getActiveSheet()->getStyle("C".($row+7).":H".($row+7))->getNumberFormat()->setFormatCode("#,##0");
			$this->objPHPExcel->getActiveSheet()->getStyle("C".($row+7).":H".($row+7))->getNumberFormat()->setFormatCode("#,##0;(#,##0)");
			$this->objPHPExcel->getActiveSheet()->getStyle("I".($row+7).":N".($row+7))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$this->objPHPExcel->getActiveSheet()->mergeCells("C".($row+7).":D".($row+7));
			$this->objPHPExcel->getActiveSheet()->mergeCells("E".($row+7).":F".($row+7));
			$this->objPHPExcel->getActiveSheet()->mergeCells("G".($row+7).":H".($row+7));
			$this->objPHPExcel->getActiveSheet()->mergeCells("I".($row+7).":J".($row+7));
			$this->objPHPExcel->getActiveSheet()->mergeCells("K".($row+7).":L".($row+7));
			
			$this->objPHPExcel->getActiveSheet()->getStyle("C".($row+7).":N".($row+7))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
			$this->objPHPExcel->getActiveSheet()->getStyle("C".($row+7).":N".($row+7))->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->objPHPExcel->getActiveSheet()->getStyle("C".($row+7).":N".($row+7))->getFont()->setBold(true);
			$this->objPHPExcel->getActiveSheet()->getStyle("C".($row+7).":N".($row+7))->getFont()->setSize(11);
			
		}		

		public function run()
		{
			if($this->grid_mode == 'export')
			{
				$this->renderHeader();
				$row = $this->renderBody();
				$this->renderFooter($row);
				
					$countRow=0;//initialize number of rows inserted
					foreach($this->rowChanges as $change)
					{
						$rowNum=$change['rowNum'];
						$rowValue=$change['value'];
						//$this->objPHPExcel->getActiveSheet()->setCellValue("N".$rowNum, $rowValue);
						$currentRow=$rowNum+$countRow+6; //+6 since it will start at row 8
						$this->objPHPExcel->getActiveSheet()->insertNewRowBefore($currentRow-1, 1);
						$this->objPHPExcel->getActiveSheet()->setCellValue('A'.($currentRow-1),strip_tags($rowValue));
						
						//merge columns for rowChange
						$this->objPHPExcel->getActiveSheet()->mergeCells("A".($currentRow-1).":N".($currentRow-1));
						//apply fill for merged
						//$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentRow-1))->getFill()
						//->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('b8cce4');
						//->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('85DE89');
						
						//$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentRow-1))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);			
						//$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentRow-1))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);			
						//set style: alignment 
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentRow-1).":N".($currentRow-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);	
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentRow-1).":N".($currentRow-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentRow-1).":N".($currentRow-1))->getFont()->setBold(true);					
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentRow-1).":N".($currentRow-1))->getFont()->setSize(12);						
						$countRow++;// increment number of rows already inserted
					}
					$headerInsert=0;
					foreach($this->rowHeaderChanges as $headerChange)
					{						
						$rowHeaderNum=$headerChange['rowNum'];
						$rowHeaderValue=$headerChange['value'];
						$numRowsInserted=$headerChange['numRowsInserted'];
						//$this->objPHPExcel->getActiveSheet()->setCellValue("N".$rowNum, $rowValue);
						//$currentRow=$rowNum+$countRow+8; //+7 since we started at row no. 8
						//originally +8, but considering numRowsInserted,2 rows (as header) already initially inserted
						//6+2 == 8;
						//check num of header rows inserted
						//minus 1 for succeeding headers
						$currentHeaderRow=$rowHeaderNum+5-$headerInsert;
						$headerInsert++;
						$this->objPHPExcel->getActiveSheet()->insertNewRowBefore($currentHeaderRow+$numRowsInserted, 1);
						$this->objPHPExcel->getActiveSheet()->getRowDimension($currentHeaderRow+$numRowsInserted)->setRowHeight(20);
						$this->objPHPExcel->getActiveSheet()->setCellValue('A'.($currentHeaderRow+$numRowsInserted),strip_tags($rowHeaderValue));
						//$this->objPHPExcel->getActiveSheet()->setCellValue('N'.($currentHeaderRow+$numRowsInserted),$currentHeaderRow+$numRowsInserted);
						//merge columns for rowChange
						$this->objPHPExcel->getActiveSheet()->mergeCells("A".($currentHeaderRow+$numRowsInserted).":N".($currentHeaderRow+$numRowsInserted));
						//apply fill for merged
						//per year fill
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentHeaderRow+$numRowsInserted))->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('6c6c6c'); //85DE89 b7c6ff 76ec86
						//$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentHeaderRow+$numRowsInserted))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);		
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentHeaderRow+$numRowsInserted).":N".($currentHeaderRow+$numRowsInserted))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
						//set style: alignment 
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentHeaderRow+$numRowsInserted).":N".($currentHeaderRow+$numRowsInserted))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);	
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentHeaderRow+$numRowsInserted).":N".($currentHeaderRow+$numRowsInserted))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentHeaderRow+$numRowsInserted).":N".($currentHeaderRow+$numRowsInserted))->getFont()->setBold(true);
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentHeaderRow+$numRowsInserted).":N".($currentHeaderRow+$numRowsInserted))->getFont()->setSize(12);
					}

					foreach($this->rowFooterChanges as $footerChange)
					{
						$rowFooterNum=$footerChange['rowNum'];
						$rowFooterValue=$footerChange['value'];
						$numRowsInserted=$footerChange['numRowsInserted'];
						//$this->objPHPExcel->getActiveSheet()->setCellValue("N".$rowNum, $rowValue);
						//$currentRow=$rowNum+$countRow+8; //+7 since we started at row no. 8
						$currentFooterRow=$rowFooterNum+7; 
						$this->objPHPExcel->getActiveSheet()->insertNewRowBefore($currentFooterRow+$numRowsInserted, 1);
						$this->objPHPExcel->getActiveSheet()->getRowDimension($currentFooterRow+$numRowsInserted)->setRowHeight(18);
						if(is_array($rowFooterValue)){
							foreach($rowFooterValue as $footerValue){
								$a=$footerValue['col'];
								$this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a).($currentFooterRow+$numRowsInserted),strip_tags($footerValue['val']));	
							}
						}else{
							$this->objPHPExcel->getActiveSheet()->setCellValue('A'.($currentFooterRow+$numRowsInserted),strip_tags($rowFooterValue));
						}
						//merge columns for rowChange
						//$this->objPHPExcel->getActiveSheet()->mergeCells("A".($currentFooterRow+$numRowsInserted).":L".($currentFooterRow+$numRowsInserted));
						//apply fill for merged
						/*$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentFooterRow+$numRowsInserted).":N".($currentFooterRow+$numRowsInserted))->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ffffcc');*/
						
						//sub-total fill
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentFooterRow+$numRowsInserted).":N".($currentFooterRow+$numRowsInserted))->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('b2b2b2');
						
						//sub-total color
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentFooterRow+$numRowsInserted))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);			
						//set style: alignment 
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentFooterRow+$numRowsInserted).":B".($currentFooterRow+$numRowsInserted))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);	
						$this->objPHPExcel->getActiveSheet()->getStyle("C".($currentFooterRow+$numRowsInserted).":N".($currentFooterRow+$numRowsInserted))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentFooterRow+$numRowsInserted).":N".($currentFooterRow+$numRowsInserted))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentFooterRow+$numRowsInserted).":N".($currentFooterRow+$numRowsInserted))->getFont()->setBold(true);
						$this->objPHPExcel->getActiveSheet()->getStyle("A".($currentFooterRow+$numRowsInserted).":N".($currentFooterRow+$numRowsInserted))->getFont()->setSize(10);
					}
								
				//set row height of header
				$this->objPHPExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(20);
				
				//# column::set width
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(5);
				//Fullname column::set width
				//$this->objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(17);
				//$this->objPHPExcel->getActiveSheet()->getColumnDimension("B")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(6);
				//Remarks column::set width
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(10);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(9);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(10);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(9);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(10);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(9);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(12);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(10);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(12);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("L")->setWidth(10);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("M")->setWidth(12);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(13);
				
				//$objPHPExcel->getActiveSheet()->getStyle('A6:G6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				//$objPHPExcel->getActiveSheet()->getStyle('A6:G6')->getFill()->getStartColor()->setARGB('FFFF00');
				$this->objPHPExcel->getActiveSheet()->mergeCells("A5:A6");
				$this->objPHPExcel->getActiveSheet()->mergeCells("B5:B6");
				
				$this->objPHPExcel->getActiveSheet()->mergeCells("C5:D5");
				$this->objPHPExcel->getActiveSheet()->getStyle("C5:D5")->applyFromArray($this->borderArrayOutline());
				$this->objPHPExcel->getActiveSheet()->setCellValue( "C5" , "No. of Sample");
				
				$this->objPHPExcel->getActiveSheet()->mergeCells("E5:F5");
				$this->objPHPExcel->getActiveSheet()->getStyle("E5:F5")->applyFromArray($this->borderArrayOutline());
				$this->objPHPExcel->getActiveSheet()->setCellValue( "E5" , "No. of Test");
				
				$this->objPHPExcel->getActiveSheet()->mergeCells("G5:H5");
				$this->objPHPExcel->getActiveSheet()->getStyle("G5:H5")->applyFromArray($this->borderArrayOutline());
				$this->objPHPExcel->getActiveSheet()->setCellValue( "G5" , "No. of Customer");
				
				$this->objPHPExcel->getActiveSheet()->mergeCells("I5:J5");
				$this->objPHPExcel->getActiveSheet()->getStyle("I5:J5")->applyFromArray($this->borderArrayOutline());
				$this->objPHPExcel->getActiveSheet()->getStyle("I5")->getAlignment()->setWrapText(false);
				$this->objPHPExcel->getActiveSheet()->getStyle("I5")->getFont()->setSize(8);
				$this->objPHPExcel->getActiveSheet()->setCellValue( "I5" , "Income (Actual Fees Collected)");
				
				/*$this->objPHPExcel->getActiveSheet()->mergeCells("K5:L5");
				$this->objPHPExcel->getActiveSheet()->getStyle("L5:L5")->applyFromArray($this->borderArrayOutline());
				$this->objPHPExcel->getActiveSheet()->getStyle("L5")->getAlignment()->setWrapText(true);
				$this->objPHPExcel->getActiveSheet()->getStyle("L5")->getFont()->setSize(9);
				$this->objPHPExcel->getActiveSheet()->setCellValue( "L5" , "Gratis");*/
				
				$this->objPHPExcel->getActiveSheet()->mergeCells("K5:L5");
				$this->objPHPExcel->getActiveSheet()->getStyle("L5:L5")->applyFromArray($this->borderArrayOutline());
				$this->objPHPExcel->getActiveSheet()->setCellValue( "K5" , "Gratis");
				
				$this->objPHPExcel->getActiveSheet()->mergeCells("G6:G6");
				$this->objPHPExcel->getActiveSheet()->mergeCells("H6:H6");
				$this->objPHPExcel->getActiveSheet()->mergeCells("I6:I6");
				$this->objPHPExcel->getActiveSheet()->mergeCells("J6:J6");
				$this->objPHPExcel->getActiveSheet()->mergeCells("K6:K6");
				$this->objPHPExcel->getActiveSheet()->mergeCells("L6:L6");
				$this->objPHPExcel->getActiveSheet()->mergeCells("M5:M6");
				$this->objPHPExcel->getActiveSheet()->mergeCells("N5:N6");
				//$this->objPHPExcel->getActiveSheet()->mergeCells("L7:L8");
				/*
				$this->objPHPExcel->getActiveSheet()->getStyle('A5:N6')->getFill()
				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('c4bd97');
				*/
				//header fill
				$this->objPHPExcel->getActiveSheet()->getStyle('A5:N6')->getFill()
				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('222222');
				$this->objPHPExcel->getActiveSheet()->getStyle('A5:N6')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
				
				//$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$this->objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5,6);
				
				$this->objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
				$this->objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
				$this->objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
				$this->objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.3);
				$this->objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.3);
				
				$this->objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&8ULIMS/&D '. '&R&8'.$this->objPHPExcel->getProperties()->getTitle().' Page &P of &N');
				$this->objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&8&D ' . $this->objPHPExcel->getProperties()->getTitle() . '&R&8Page &P of &N');
				
				//set auto width
				if($this->autoWidth)
					foreach($this->columns as $n=>$column)
						$this->objPHPExcel->getActiveSheet()->getColumnDimension($this->columnName($n+1))->setAutoSize(true);
				//create writer for saving
				$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, $this->exportType);
				if(!$this->stream)
					$objWriter->save($this->filename);
				else //output to browser
				{
					if(!$this->filename)
						$this->filename = $this->title;
					$this->cleanOutput();
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-type: '.$this->mimeTypes[$this->exportType]['Content-type']);
					header('Content-Disposition: attachment; filename="'.$this->filename.'.'.$this->mimeTypes[$this->exportType]['extension'].'"');
					header('Cache-Control: max-age=0');				
					$objWriter->save('php://output');			
					Yii::app()->end();
				}
			} else
				parent::run();
		}
		
		public function borderArrayOutline()
		{
			//Set borders around a rectangular selection of a certain range
			$styleArrayOutline = array(
				'borders' => array(
					'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => 'D9D9D9'),
					),							
				),
			);
			return $styleArrayOutline;
		}

		public function borderArrayLeftRight()
		{
			//Set borders around a rectangular selection of a certain range::LEFT&RIGHT
			$styleArrayLeftRight = array(
				'borders' => array(
					'left' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('argb' => 'D9D9D9'),
					),
					'right' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('argb' => 'D9D9D9'),
					),																	
				),
			);
			return $styleArrayLeftRight;
		}		
		
		public function borderArray()
		{
			//Set borders around each cell if given a range
			$styleArray = array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('argb' => 'D9D9D9'),
						),							
				),
			);
			return $styleArray;
		}
		/**
		* Returns the coresponding excel column.(Abdul Rehman from yii forum)
		* 
		* @param int $index
		* @return string
		*/
		public function columnName($index)
		{
			--$index;
			if($index >= 0 && $index < 26)
				return chr(ord('A') + $index);
			else if ($index > 25)
				return ($this->columnName($index / 26)).($this->columnName($index%26 + 1));
				else
					throw new Exception("Invalid Column # ".($index + 1));
		}		
		
		public function renderExportButtons()
		{
			foreach($this->exportButtons as $key=>$button)
			{
				$item = is_array($button) ? CMap::mergeArray($this->mimeTypes[$key], $button) : $this->mimeTypes[$button];
				$type = is_array($button) ? $key : $button;
				$url = parse_url(Yii::app()->request->requestUri);
				//$content[] = CHtml::link($item['caption'], '?'.$url['query'].'exportType='.$type.'&'.$this->grid_mode_var.'=export');
				if (key_exists('query', $url))
				    $content[] = CHtml::link($item['caption'], '?'.$url['query'].'&exportType='.$type.'&'.$this->grid_mode_var.'=export');          
				else
				    $content[] = CHtml::link($item['caption'], '?exportType='.$type.'&'.$this->grid_mode_var.'=export');				
			}
			if($content)
				echo CHtml::tag('div', array('class'=>$this->exportButtonsCSS), $this->exportText.implode(', ',$content));	

		}			
		
		/**
		* Performs cleaning on mutliple levels.
		* 
		* From le_top @ yiiframework.com
		* 
		*/
		private static function cleanOutput() 
		{
            for($level=ob_get_level();$level>0;--$level)
            {
                @ob_end_clean();
            }
        }
		/*
		additional function by RBG
		*/	
		public function rowChangeOccured($prevRow, $colName, $currentValue)
		{
			$data = $this->dataProvider->getData();
			$prevValue = CHtml::encode(CHtml::value($data[$prevRow], $colName));
			if($currentValue!=$prevValue){
				//row number where change occured and value
				//+8 since we started at row num 8
				//return array('rowNum'=>$prevRow+1+8, 'value'=>$currentValue); 
				return array('rowNum'=>$prevRow+1+7, 'value'=>$currentValue); 
			}
		}
    /**
    * renders extra row
    * 
    * @param mixed $row
    * @param mixed $change
    */
    private function renderExtraRow($row, $totals)
    {
        $data = $this->dataProvider->data[$row]; 
        if($this->extraRowExpression) { //user defined expression, use it!
            $value = $this->evaluateExpression($this->extraRowExpression, array('data'=>$data, 'row'=>$row, 'totals' => $totals));
        } else {  //generate value
            $values = array();
            foreach($this->extraRowColumns as $colName) {
                $values[] = CHtml::encode(CHtml::value($data, $colName));
            }
            $value = implode(' :: ', $values);  
        }
		
		//modified by RBG
		$totalColumns = count($this->columns);
		if($this->hiddenColumns) $totalColumns=$totalColumns-$this->hiddenColumns;
		if($this->extraRowColSpan){ //user defined number of colspan, use it!
			$colspan = $this->extraRowColSpan;
			//create td for columns not covered by colspan
			//if colspan defined is less than 1 or greater than total columns
			//return total columns
			if($colspan<1 OR $colspan>$totalColumns){
				$colspan=$totalColumns;
			}else{
				$colRemain = $totalColumns - $colspan;				
			}
			/*echo '<tr>';
			echo '<td class="extrarow" colspan="'.$colspan.'">'.$content.'</td>';
			echo '</tr>';*/
		}else{//count total number of columns
			$colspan = $totalColumns;
			/*echo '<tr>';
			echo '<td class="extrarow" colspan="'.$colspan.'">'.$content.'</td>';
			echo '</tr>';*/
		}
		$insertNewRow=array('rowNum'=>$row+1, 'value'=>$value);
		return $insertNewRow;
		
    }		

    private function renderExtraRowHeader($row, $totals)
    {
        $data = $this->dataProvider->data[$row]; 
        if($this->extraRowExpressionGroupHeader) { //user defined expression, use it!
            $value = $this->evaluateExpression($this->extraRowExpressionGroupHeader, array('data'=>$data, 'row'=>$row, 'totals' => $totals));
        } else {  //generate value
            $values = array();
            foreach($this->extraRowColumnsGroupHeader as $colName) {
                $values[] = CHtml::value($data, $colName);
            }
            $value = implode(' :: ', $values);
        }
		
		//modified by RBG
		$insertNewHeader=array('rowNum'=>$row+1, 'value'=>$value);
		return $insertNewHeader;
    }	

    private function renderExtraRowFooter($row, $totals)
    {
        $data = $this->dataProvider->data[$row]; 
        if($this->extraRowExpressionGroupFooter) { //user defined expression, use it!
			if(is_array($this->extraRowExpressionGroupFooter)){//check if expression is an array
				$values = array();
				foreach($this->extraRowExpressionGroupFooter as $groupFooter){
					$values[]=array('col'=>$groupFooter['col'],'val'=>$this->evaluateExpression($groupFooter['val'],array('data'=>$data)));
				}
				//$value=implode(',', $values);
				$value=$values;
			}else{
            	$value = $this->evaluateExpression($this->extraRowExpressionGroupFooter, array('data'=>$data, 'row'=>$row, 'totals' => $totals));
			}
        } else {  //generate value
            $values = array();
            foreach($this->extraRowColumnsGroupFooter as $colName) {
                $values[] = CHtml::encode(CHtml::value($data, $colName));
            }
            $value = implode(' :: ', $values);  
        }
		//modified by RBG
		if($this->hiddenColumns) $totalColumns=$totalColumns-$this->hiddenColumns;
		$insertNewFooter=array('rowNum'=>$row, 'value'=>$value);
		return $insertNewFooter;		
    }	
    /**
    * Is current row start or end of group in particular column 
    */
    private function isGroupEdge($colName, $row) 
    {
        $result = array();
        foreach($this->_groups[$colName] as $index => $v) {
           if($v['start'] == $row) {
               $result['start'] = $row;
               $result['group'] = $v;
           }
           if($v['end'] == $row) {
               $result['end'] = $row;
               $result['group'] = $v;
           } 
           if(count($result)) break;
        }
        return $result;
    }

    private function isGroupEdgeHeader($colName, $row) 
    {
        $result = array();
        foreach($this->_groupsHeader[$colName] as $index => $v) {
           if($v['start'] == $row) {
               $result['start'] = $row;
               $result['group'] = $v;
           }
           if($v['end'] == $row) {
               $result['end'] = $row;
               $result['group'] = $v;
           } 
           if(count($result)) break;
        }
        return $result;
    }

    private function isGroupEdgeFooter($colName, $row) 
    {
        $result = array();
        foreach($this->_groupsFooter[$colName] as $index => $v) {
           if($v['start'] == $row) {
               $result['start'] = $row;
               $result['group'] = $v;
           }
           if($v['end'] == $row) {
               $result['end'] = $row;
               $result['group'] = $v;
           } 
           if(count($result)) break;
        }
        return $result;
    }	

    /**
    * find and store changing of group columns
    * 
    * @param mixed $data
    */
    public function groupByColumns()
    {
        $data = $this->dataProvider->getData();
        if(count($data) == 0) return;

        if(!is_array($this->mergeColumns)) $this->mergeColumns = array($this->mergeColumns);
        if(!is_array($this->extraRowColumns)) $this->extraRowColumns = array($this->extraRowColumns);

        //store columns for group. Set object for existing columns in grid and string for attributes
        $groupColumns = array_unique(array_merge($this->mergeColumns, $this->extraRowColumns));
        foreach($groupColumns as $key => $colName) {
            foreach($this->columns as $column) {
                if(property_exists($column, 'name') && $column->name == $colName) {
                    $groupColumns[$key] = $column;
                    break;
                }
            }
        }

        //storage for groups in each column
        $groups = array();
          
        //values for first row
        $values = $this->getRowValues($groupColumns, $data[0], 0);
        foreach($values as $colName => $value) {
            $groups[$colName][] = array(
                'value'  => $value,
                'column' => $colName,
                'start'  => 0,
                //end - later
                //totals - later
            );
        }
        
        //calc totals for the first row
        $totals = array();
        if($this->extraRowTotals) {
            $this->evaluateExpression($this->extraRowTotals, array('data'=>$data[0], 'row'=>0, 'totals' => &$totals));
        }

        //iterate data 
        for($i = 1; $i < count($data); $i++) {
            //save row values in array
            $current = $this->getRowValues($groupColumns, $data[$i], $i);

            //define is change occured. Need this extra foreach for correctly proceed extraRows
            $changedColumns = array();
            foreach($current as $colName => $curValue) {
                $prev = end($groups[$colName]);  
                if($curValue != $prev['value']) {
                    $changedColumns[] = $colName;
                }
            }
            
            /*
             if this flag = true -> we will write change for all grouping columns.
             It's required when change of any column from extraRowColumns occurs
            */
            $extraRowColumnChanged = (count(array_intersect($changedColumns, $this->extraRowColumns)) > 0);
            
            /*
             this changeOccured related to foreach below. It is required only for mergeType == self::MERGE_NESTED, 
             to write change for all nested columns when change of previous column occured
            */
            $changeOccured = false;
            foreach($current as $colName => $curValue) {
                //value changed
                $valueChanged = in_array($colName, $changedColumns);
                //change already occured in this loop and mergeType set to MERGETYPE_NESTED
                $saveChange = $valueChanged || ($changeOccured && $this->mergeType == self::MERGE_NESTED);
                
                if($extraRowColumnChanged || $saveChange) { 
                    $changeOccured = true;
                    $lastIndex = count($groups[$colName]) - 1;
                 
                    //finalize prev group
                    $groups[$colName][$lastIndex]['end'] = $i - 1;
                    $groups[$colName][$lastIndex]['totals'] = $totals;
                   
                    //begin new group
                    $groups[$colName][] = array(
                      'start'   => $i,
                      'column'  => $colName,
                      'value'   => $curValue,
                    );
                } 
            }
            
            //if change in extrarowcolumn --> reset totals
            if($extraRowColumnChanged) {
                $totals = array();  
            }
            
            //calc totals for that row
            if($this->extraRowTotals) {
                $this->evaluateExpression($this->extraRowTotals, array('data'=>$data[$i], 'row'=>$i, 'totals' => &$totals));
            }  
        }

        //finalize group for last row
        foreach($groups as $colName => $v) {
            $lastIndex = count($groups[$colName]) - 1;
            $groups[$colName][$lastIndex]['end'] = count($data) - 1;
            $groups[$colName][$lastIndex]['totals'] = $totals;
        }
        
        $this->_groups = $groups;
    }

    public function groupByColumnsHeader()
    {
        $data = $this->dataProvider->getData();
        if(count($data) == 0) return;

        if(!is_array($this->mergeColumns)) $this->mergeColumns = array($this->mergeColumns);
        if(!is_array($this->extraRowColumnsGroupHeader)) $this->extraRowColumnsGroupHeader = array($this->extraRowColumnsGroupHeader);

        //store columns for group. Set object for existing columns in grid and string for attributes
        $groupColumns = array_unique(array_merge($this->mergeColumns, $this->extraRowColumnsGroupHeader));
        foreach($groupColumns as $key => $colName) {
            foreach($this->columns as $column) {
                if(property_exists($column, 'name') && $column->name == $colName) {
                    $groupColumns[$key] = $column;
                    break;
                }
            }
        }

        //storage for groups in each column
        $groups = array();
          
        //values for first row
        $values = $this->getRowValues($groupColumns, $data[0], 0);
        foreach($values as $colName => $value) {
            $groups[$colName][] = array(
                'value'  => $value,
                'column' => $colName,
                'start'  => 0,
                //end - later
                //totals - later
            );
        }
        
        //calc totals for the first row
        $totals = array();
        if($this->extraRowTotals) {
            $this->evaluateExpression($this->extraRowTotals, array('data'=>$data[0], 'row'=>0, 'totals' => &$totals));
        }

        //iterate data 
        for($i = 1; $i < count($data); $i++) {
            //save row values in array
            $current = $this->getRowValues($groupColumns, $data[$i], $i);

            //define is change occured. Need this extra foreach for correctly proceed extraRows
            $changedColumns = array();
            foreach($current as $colName => $curValue) {
                $prev = end($groups[$colName]);  
                if($curValue != $prev['value']) {
                    $changedColumns[] = $colName;
                }
            }
            
            /*
             if this flag = true -> we will write change for all grouping columns.
             It's required when change of any column from extraRowColumns occurs
            */
            $extraRowColumnChanged = (count(array_intersect($changedColumns, $this->extraRowColumns)) > 0);
            
            /*
             this changeOccured related to foreach below. It is required only for mergeType == self::MERGE_NESTED, 
             to write change for all nested columns when change of previous column occured
            */
            $changeOccured = false;
            foreach($current as $colName => $curValue) {
                //value changed
                $valueChanged = in_array($colName, $changedColumns);
                //change already occured in this loop and mergeType set to MERGETYPE_NESTED
                $saveChange = $valueChanged || ($changeOccured && $this->mergeType == self::MERGE_NESTED);
                
                if($extraRowColumnChanged || $saveChange) { 
                    $changeOccured = true;
                    $lastIndex = count($groups[$colName]) - 1;
                 
                    //finalize prev group
                    $groups[$colName][$lastIndex]['end'] = $i - 1;
                    $groups[$colName][$lastIndex]['totals'] = $totals;
                   
                    //begin new group
                    $groups[$colName][] = array(
                      'start'   => $i,
                      'column'  => $colName,
                      'value'   => $curValue,
                    );
                } 
            }
            
            //if change in extrarowcolumn --> reset totals
            if($extraRowColumnChanged) {
                $totals = array();  
            }
            
            //calc totals for that row
            if($this->extraRowTotals) {
                $this->evaluateExpression($this->extraRowTotals, array('data'=>$data[$i], 'row'=>$i, 'totals' => &$totals));
            }  
        }

        //finalize group for last row
        foreach($groups as $colName => $v) {
            $lastIndex = count($groups[$colName]) - 1;
            $groups[$colName][$lastIndex]['end'] = count($data) - 1;
            $groups[$colName][$lastIndex]['totals'] = $totals;
        }
        
        $this->_groupsHeader = $groups;
    }

    public function groupByColumnsFooter()
    {
        $data = $this->dataProvider->getData();
        if(count($data) == 0) return;

        if(!is_array($this->mergeColumns)) $this->mergeColumns = array($this->mergeColumns);
        if(!is_array($this->extraRowColumnsGroupFooter)) $this->extraRowColumnsGroupFooter = array($this->extraRowColumnsGroupFooter);

        //store columns for group. Set object for existing columns in grid and string for attributes
        $groupColumns = array_unique(array_merge($this->mergeColumns, $this->extraRowColumnsGroupFooter));
        foreach($groupColumns as $key => $colName) {
            foreach($this->columns as $column) {
                if(property_exists($column, 'name') && $column->name == $colName) {
                    $groupColumns[$key] = $column;
                    break;
                }
            }
        }

        //storage for groups in each column
        $groups = array();
          
        //values for first row
        $values = $this->getRowValues($groupColumns, $data[0], 0);
        foreach($values as $colName => $value) {
            $groups[$colName][] = array(
                'value'  => $value,
                'column' => $colName,
                'start'  => 0,
                //end - later
                //totals - later
            );
        }
        
        //calc totals for the first row
        $totals = array();
        if($this->extraRowTotals) {
            $this->evaluateExpression($this->extraRowTotals, array('data'=>$data[0], 'row'=>0, 'totals' => &$totals));
        }

        //iterate data 
        for($i = 1; $i < count($data); $i++) {
            //save row values in array
            $current = $this->getRowValues($groupColumns, $data[$i], $i);

            //define is change occured. Need this extra foreach for correctly proceed extraRows
            $changedColumns = array();
            foreach($current as $colName => $curValue) {
                $prev = end($groups[$colName]);  
                if($curValue != $prev['value']) {
                    $changedColumns[] = $colName;
                }
            }
            
            /*
             if this flag = true -> we will write change for all grouping columns.
             It's required when change of any column from extraRowColumns occurs
            */
            $extraRowColumnChanged = (count(array_intersect($changedColumns, $this->extraRowColumns)) > 0);
            
            /*
             this changeOccured related to foreach below. It is required only for mergeType == self::MERGE_NESTED, 
             to write change for all nested columns when change of previous column occured
            */
            $changeOccured = false;
            foreach($current as $colName => $curValue) {
                //value changed
                $valueChanged = in_array($colName, $changedColumns);
                //change already occured in this loop and mergeType set to MERGETYPE_NESTED
                $saveChange = $valueChanged || ($changeOccured && $this->mergeType == self::MERGE_NESTED);
                
                if($extraRowColumnChanged || $saveChange) { 
                    $changeOccured = true;
                    $lastIndex = count($groups[$colName]) - 1;
                 
                    //finalize prev group
                    $groups[$colName][$lastIndex]['end'] = $i - 1;
                    $groups[$colName][$lastIndex]['totals'] = $totals;
                   
                    //begin new group
                    $groups[$colName][] = array(
                      'start'   => $i,
                      'column'  => $colName,
                      'value'   => $curValue,
                    );
                } 
            }
            
            //if change in extrarowcolumn --> reset totals
            if($extraRowColumnChanged) {
                $totals = array();  
            }
            
            //calc totals for that row
            if($this->extraRowTotals) {
                $this->evaluateExpression($this->extraRowTotals, array('data'=>$data[$i], 'row'=>$i, 'totals' => &$totals));
            }  
        }

        //finalize group for last row
        foreach($groups as $colName => $v) {
            $lastIndex = count($groups[$colName]) - 1;
            $groups[$colName][$lastIndex]['end'] = count($data) - 1;
            $groups[$colName][$lastIndex]['totals'] = $totals;
        }
        
        $this->_groupsFooter = $groups;
    }

    /**
    * returns array of rendered column values (TD)
    * 
    * @param mixed $columns
    * @param mixed $rowIndex
    */
    private function getRowValues($columns, $data, $rowIndex)
    {
        foreach($columns as $column) {
            if($column instanceOf CGridColumn) {
                $result[$column->name] = $this->getDataCellContent($column, $data, $rowIndex);
            } elseif(is_string($column)) {
                if(is_array($data) && array_key_exists($column, $data)) {
                    $result[$column] = $data[$column];
                } elseif($data instanceOf CModel && $data->hasAttribute($column)) {
                    $result[$column] = $data->getAttribute($column);
                } else {
                    throw new CException('Column or attribute "'.$column.'" not found!');
                }
            }
        }

        return $result;
    }

    /**
    * need to rewrite this function as it is protected in CDataColumn: it is strange as all methods inside are public 
    * 
    * @param mixed $column
    * @param mixed $row
    * @param mixed $data
    */
    private function getDataCellContent($column, $data, $row)
    {
        if($column->value!==null)
            $value=$column->evaluateExpression($column->value, array('data'=>$data,'row'=>$row));
        else if($column->name!==null)
                $value=CHtml::value($data,$column->name);

            return $value===null ? $column->grid->nullDisplay : $column->grid->getFormatter()->format($value, $column->type);
    }
		
}