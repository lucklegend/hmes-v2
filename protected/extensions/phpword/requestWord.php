<?php

// Assuming PHPWord is installed in protected/vendors/PHPWord
// and bootstrap.php handles autoloading
require_once(Yii::getPathOfAlias('application.vendors.PHPWord') . '/bootstrap.php');

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\Style\Cell;

class requestWord {

    private $request;
    private $phpWord;

    public function __construct($request) {
        $this->request = $request;
        $this->phpWord = new PhpWord();
        $this->setDefaultStyles();
    }

    private function setDefaultStyles() {
        // Set default font and size (optional, but good practice)
        $this->phpWord->setDefaultFontName('Helvetica');
        $this->phpWord->setDefaultFontSize(9);
    }

    public function generateDocument() {
        $this->addHeaderContent();
        $this->addRequestDetailsTable();
        $this->addCustomerDetails();
        $this->addTestingOrCalibrationServiceSection();
        $this->addSampleAndAnalysisRows();
        $this->addTotals();
        $this->addBriefDescriptionSection();
        $this->addOtherServiceSection(); // Placeholder, as it's empty in requestPdf
        $this->addFooterContent();

        // Save and output the document
        $filename = $this->request->requestRefNum . '.docx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($this->phpWord, 'Word2007');
        $objWriter->save('php://output');
        Yii::app()->end();
    }

    private function addHeaderContent() {
        $section = $this->phpWord->addSection();

        // Agency Name, RSTL, Address, Contact Info - Centered
        $centeredParagraphStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
        $section->addText(Yii::app()->params['Agency']['name'], ['size' => 9], $centeredParagraphStyle);
        $section->addText(strtoupper(Yii::app()->params['Agency']['labName']), ['bold' => true, 'size' => 9], $centeredParagraphStyle);
        $section->addText(Yii::app()->params['Agency']['address'], ['size' => 9], $centeredParagraphStyle);
        $section->addText('Contact No. '.Yii::app()->params['Agency']['contacts'], ['size' => 9], $centeredParagraphStyle);

        $section->addTextBreak(2); // Simulating <br/><br/>
        $section->addText(Yii::app()->params['FormRequest']['title'], ['bold' => true, 'size' => 12], $centeredParagraphStyle);
        $section->addTextBreak(1);
    }

    private function addRequestDetailsTable() {
        $section = $this->phpWord->getLastSection();

        $tableStyle = new Table();
        $tableStyle->setBorderSize(6); // Corresponds to 0.5px approx (1pt = 1/72 inch, 6 twips = 6/20 pt)
        $tableStyle->setUnit(\PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT);
        $tableStyle->setWidth(46 * 100); // 46% width

        $table = $section->addTable($tableStyle);

        $cellStyle = ['valign' => 'center'];
        $labelStyle = ['bold' => false, 'size' => 9];
        $valueStyle = ['bold' => false, 'size' => 9];

        $table->addRow();
        $table->addCell(1500, $cellStyle)->addText('Req. Ref. No.:', $labelStyle); // Widths are in twips (1/20th of a point)
        $table->addCell(3500, $cellStyle)->addText($this->request->requestRefNum, $valueStyle);

        $table->addRow();
        $table->addCell(1500, $cellStyle)->addText('Date:', $labelStyle);
        $table->addCell(3500, $cellStyle)->addText($this->request->requestDate, $valueStyle);

        $table->addRow();
        $table->addCell(1500, $cellStyle)->addText('Time:', $labelStyle);
        $table->addCell(3500, $cellStyle)->addText($this->request->requestTime, $valueStyle);

        $section->addTextBreak(1);
    }

    private function addCustomerDetails() {
        $section = $this->phpWord->getLastSection();

        $tableStyle = new Table();
        $tableStyle->setBorderSize(6);
        $tableStyle->setUnit(\PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT);
        $tableStyle->setWidth(100 * 100); // 100% width

        $table = $section->addTable($tableStyle);
        $cellStyle = ['valign' => 'center'];
        $labelStyle = ['size' => 9];
        $valueStyle = ['size' => 9];

        // Row 1
        $table->addRow();
        $table->addCell(1500, $cellStyle)->addText('CUSTOMER:', $labelStyle);
        $table->addCell(6000, $cellStyle)->addText($this->request->customer->customerName, $valueStyle);
        $table->addCell(1500, $cellStyle)->addText('TEL NO.:', $labelStyle);
        $table->addCell(2000, $cellStyle)->addText($this->request->customer->tel, $valueStyle);

        // Row 2
        $table->addRow();
        $table->addCell(1500, $cellStyle)->addText('ADDRESS:', $labelStyle);
        $table->addCell(6000, $cellStyle)->addText($this->request->customer->address, $valueStyle);
        $table->addCell(1500, $cellStyle)->addText('FAX NO.:', $labelStyle);
        $table->addCell(2000, $cellStyle)->addText($this->request->customer->fax, $valueStyle);

        $section->addTextBreak(1);
    }

    private function addTestingOrCalibrationServiceSection() {
        $section = $this->phpWord->getLastSection();
        $section->addText('1. TESTING OR CALIBRATION SERVICE', ['bold' => true, 'size' => 9]);

        $headerTableStyle = new Table();
        $headerTableStyle->setBorderSize(6);
        $headerTableStyle->setUnit(\PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT);
        $headerTableStyle->setWidth(97.5 * 100); // 97.5% width

        $table = $section->addTable($headerTableStyle);

        $headerCellStyle = ['valign' => 'center', 'borderSize' => 6, 'borderColor' => '000000'];
        $headerFontStyle = ['bold' => true, 'size' => 8, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
        $centeredParagraphStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];

        $table->addRow();
        $table->addCell(2800, $headerCellStyle)->addText('SAMPLE', $headerFontStyle, $centeredParagraphStyle); // Width in twips
        $table->addCell(900, $headerCellStyle)->addText('SAMPLE CODE', $headerFontStyle, $centeredParagraphStyle);
        $table->addCell(2280, $headerCellStyle)->addText('TEST/CALIBRATION REQUESTED', $headerFontStyle, $centeredParagraphStyle);
        $table->addCell(2240, $headerCellStyle)->addText('TEST METHOD', $headerFontStyle, $centeredParagraphStyle);
        $table->addCell(1040, $headerCellStyle)->addText('NO. OF SAMPLES/UNITS', $headerFontStyle, $centeredParagraphStyle);
        $table->addCell(1040, $headerCellStyle)->addText('UNIT COST', $headerFontStyle, $centeredParagraphStyle);
        $table->addCell(1040, $headerCellStyle)->addText('TOTAL', $headerFontStyle, $centeredParagraphStyle);
    }

    private function addSampleAndAnalysisRows() {
        $section = $this->phpWord->getLastSection();

        $dataTableStyle = new Table();
        $dataTableStyle->setBorderSize(6); // Individual cell borders will handle appearance
        $dataTableStyle->setUnit(\PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT);
        $dataTableStyle->setWidth(97.5 * 100);

        $table = $section->addTable($dataTableStyle);

        $cellStyle = ['valign' => 'center', 'borderSize' => 6, 'borderColor' => '000000'];
        $textStyle = ['size' => 8];
        $rightAlignedStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT];
        $centerAlignedStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];

        $subTotal = 0;

        foreach ($this->request->samps as $sample) {
            $analysisCount = 0;
            foreach ($sample->analyses as $analysis) {
                $table->addRow();
                if ($analysisCount == 0) {
                    $table->addCell(2800, $cellStyle)->addText($sample->sampleName, $textStyle);
                    $table->addCell(900, $cellStyle)->addText($sample->sampleCode, $textStyle, $centerAlignedStyle);
                } else {
                    $table->addCell(2800, $cellStyle)->addText('', $textStyle); // Empty cells for subsequent analyses of the same sample
                    $table->addCell(900, $cellStyle)->addText('', $textStyle);
                }
                $table->addCell(2280, $cellStyle)->addText($analysis->testName, $textStyle);
                $table->addCell(2240, $cellStyle)->addText($analysis->method, $textStyle);
                $table->addCell(1040, $cellStyle)->addText('1', $textStyle, $centerAlignedStyle);
                $table->addCell(1040, $cellStyle)->addText(Yii::app()->format->formatNumber($analysis->fee), $textStyle, $rightAlignedStyle);
                $table->addCell(1040, $cellStyle)->addText(Yii::app()->format->formatNumber($analysis->fee), $textStyle, $rightAlignedStyle);

                $subTotal += $analysis->fee;
                $analysisCount++;
            }
        }
        $this->subTotal = $subTotal; // Store for later use in totals
    }

    private function addTotals() {
        $section = $this->phpWord->getLastSection();

        $table = $section->addTable(array('borderSize' => 6, 'borderColor' => '000000', 'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT, 'width' => 97.5 * 100));
        $textStyle = ['size' => 8];
        $boldTextStyle = ['size' => 8, 'bold' => true];
        $rightAlignedParagraph = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT];
        $cellStyle = ['valign' => 'center', 'borderSize' => 6, 'borderColor' => '000000'];

        // Empty row for spacing if needed, or adjust margins
        $table->addRow();
        $table->addCell(2800, $cellStyle); $table->addCell(900, $cellStyle); $table->addCell(2280, $cellStyle); $table->addCell(2240, $cellStyle); $table->addCell(1040, $cellStyle);
        $table->addCell(1040, $cellStyle); $table->addCell(1040, $cellStyle);

        // Sub-Total
        $table->addRow();
        $table->addCell(2800, $cellStyle); $table->addCell(900, $cellStyle); $table->addCell(2280, $cellStyle); $table->addCell(2240, $cellStyle);
        $cell = $table->addCell(1040 + 1040, array_merge($cellStyle, ['gridSpan' => 2]));
        $cell->addText('Sub-Total', $textStyle, $rightAlignedParagraph);
        $table->addCell(1040, $cellStyle)->addText(Yii::app()->format->formatNumber($this->subTotal), $textStyle, $rightAlignedParagraph);

        $discountAmount = $this->subTotal * $this->request->disc->rate / 100;
        $inplantcharge = $this->request->inplant_charge;
        $additional = $this->request->additional;
        $total = ($inplantcharge + $additional) + ($this->subTotal - $discountAmount);

        // Discount
        $table->addRow();
        $table->addCell(2800, $cellStyle); $table->addCell(900, $cellStyle); $table->addCell(2280, $cellStyle); $table->addCell(2240, $cellStyle);
        $cell = $table->addCell(1040 + 1040, array_merge($cellStyle, ['gridSpan' => 2]));
        $cell->addText('Discount', $textStyle, $rightAlignedParagraph);
        $table->addCell(1040, $cellStyle)->addText('- ' . Yii::app()->format->formatNumber($discountAmount), $textStyle, $rightAlignedParagraph);

        // In Plant Charge
        $table->addRow();
        $table->addCell(2800, $cellStyle); $table->addCell(900, $cellStyle); $table->addCell(2280, $cellStyle); $table->addCell(2240, $cellStyle);
        $cell = $table->addCell(1040 + 1040, array_merge($cellStyle, ['gridSpan' => 2]));
        $cell->addText('In Plant Charge', $textStyle, $rightAlignedParagraph);
        $table->addCell(1040, $cellStyle)->addText(Yii::app()->format->formatNumber($inplantcharge), $textStyle, $rightAlignedParagraph);

        // Additional
        $table->addRow();
        $table->addCell(2800, $cellStyle); $table->addCell(900, $cellStyle); $table->addCell(2280, $cellStyle); $table->addCell(2240, $cellStyle);
        $cell = $table->addCell(1040 + 1040, array_merge($cellStyle, ['gridSpan' => 2]));
        $cell->addText('Additional', $textStyle, $rightAlignedParagraph);
        $table->addCell(1040, $cellStyle)->addText(Yii::app()->format->formatNumber($additional), $textStyle, $rightAlignedParagraph);

        // TOTAL
        $table->addRow();
        $table->addCell(2800, $cellStyle); $table->addCell(900, $cellStyle); $table->addCell(2280, $cellStyle); $table->addCell(2240, $cellStyle);
        $cell = $table->addCell(1040 + 1040, array_merge($cellStyle, ['gridSpan' => 2]));
        $cell->addText('TOTAL', $boldTextStyle, $rightAlignedParagraph);
        $table->addCell(1040, $cellStyle)->addText(Yii::app()->format->formatNumber($total), $boldTextStyle, $rightAlignedParagraph);

        $section->addTextBreak(1);
    }

    private function addBriefDescriptionSection() {
        $section = $this->phpWord->getLastSection();
        $section->addText('2. BRIEF DESCRIPTION OF SAMPLE/REMARKS', ['bold' => true, 'size' => 9]);

        $tableStyle = new Table();
        $tableStyle->setBorderSize(6);
        $tableStyle->setUnit(\PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT);
        $tableStyle->setWidth(100 * 100);
        $table = $section->addTable($tableStyle);
        $cellStyle = ['valign' => 'center', 'borderSize' => 6, 'borderColor' => '000000'];
        $textStyle = ['size' => 8];

        $table->addRow()->addCell(null, $cellStyle); // Empty row for spacing before

        foreach ($this->request->samps as $sample) {
            $table->addRow();
            $table->addCell(null, $cellStyle)->addText($sample->sampleCode . ': ' . $sample->description, $textStyle);
        }
        $table->addRow()->addCell(null, $cellStyle); // Empty row for spacing after
        $section->addTextBreak(1);
    }

    private function addOtherServiceSection() {
        $section = $this->phpWord->getLastSection();
        $section->addText('3. OTHER SERVICE', ['bold' => true, 'size' => 9]);
        // Content for this section is empty in the PDF, so keeping it minimal
        $tableStyle = new Table();
        $tableStyle->setBorderSize(6);
        $tableStyle->setUnit(\PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT);
        $tableStyle->setWidth(100 * 100);
        $table = $section->addTable($tableStyle);
        $cellStyle = ['valign' => 'center', 'borderSize' => 6, 'borderColor' => '000000'];
        $table->addRow()->addCell(null, $cellStyle); // Empty row for spacing
        foreach($this->request->samps as $sample)
            {
		$table->addRow()->addCell(null, $cellStyle);
            }
        $table->addRow()->addCell(null, $cellStyle);
        $section->addTextBreak(1);
    }

    private function addFooterContent() {
        // Footer content is complex and involves precise positioning.
        // This will be a simplified version. For precise layout,
        // using Word template processing features of PHPWord might be better,
        // or more intricate table structures.

        $section = $this->phpWord->getLastSection();

        // OR Details Table
        $footerTableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT, 'width' => 97.5 * 100];
        $orTable = $section->addTable($footerTableStyle);
        $cellStyle = ['valign' => 'center', 'borderSize' => 6, 'borderColor' => '000000'];
        $textStyle = ['size' => 9];

        $orTable->addRow();
        $orTable->addCell(2500, $cellStyle)->addText('OR. NO.:', $textStyle);
        $orTable->addCell(5000, $cellStyle)->addText('', $textStyle); // Placeholder
        $orTable->addCell(3000, $cellStyle)->addText('AMOUNT RECEIVED:', $textStyle);
        $orTable->addCell(3000, $cellStyle)->addText('', $textStyle); // Placeholder

        $orTable->addRow();
        $orTable->addCell(2500, $cellStyle)->addText('DATE:', $textStyle);
        $orTable->addCell(5000, $cellStyle)->addText('', $textStyle); // Placeholder
        $orTable->addCell(3000, $cellStyle)->addText('UNPAID BALANCE:', $textStyle);
        $orTable->addCell(3000, $cellStyle)->addText('', $textStyle); // Placeholder
        $section->addTextBreak(1);

        // Report Due Table
        $reportDueTable = $section->addTable($footerTableStyle);
        $reportDueTable->addRow();
        $reportDueTable->addCell(3000, $cellStyle)->addText('REPORT DUE ON:', $textStyle);
        $reportDueTable->addCell(10500, $cellStyle)->addText($this->request->reportDue, $textStyle);
        $section->addTextBreak(1);

        // Signatories Table
        $labManager = isset($this->request->laboratory->manager->user) ? $this->request->laboratory->manager->user->getFullname() : '';
        $signatoriesTable = $section->addTable($footerTableStyle);
        $centerAlignment = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];

        $signatoriesTable->addRow();
        $signatoriesTable->addCell(null, array_merge($cellStyle, ['gridSpan' => 3]))->addText('DISCUSSED WITH CUSTOMER', $textStyle);

        $signatoriesTable->addRow();
        $signatoriesTable->addCell(4500, $cellStyle)->addText('CONFORME:', $textStyle);
        $signatoriesTable->addCell(4500, $cellStyle); // Empty
        $signatoriesTable->addCell(4500, $cellStyle); // Empty

        $signatoriesTable->addRow(); // Empty row for spacing signatures
        $signatoriesTable->addCell(4500, array_merge($cellStyle, ['borderBottomSize' => 0]));
        $signatoriesTable->addCell(4500, array_merge($cellStyle, ['borderBottomSize' => 0]));
        $signatoriesTable->addCell(4500, array_merge($cellStyle, ['borderBottomSize' => 0]));

        $signatoriesTable->addRow();
        $signatoriesTable->addCell(4500, $cellStyle)->addText($this->request->conforme, $textStyle, $centerAlignment);
        $signatoriesTable->addCell(4500, $cellStyle)->addText($this->request->receivedBy, $textStyle, $centerAlignment);
        $signatoriesTable->addCell(4500, $cellStyle)->addText($labManager, $textStyle, $centerAlignment);

        $signatoriesTable->addRow();
        $signatoriesTable->addCell(4500, $cellStyle)->addText('Customer/Authorized Representative', $textStyle, $centerAlignment);
        $signatoriesTable->addCell(4500, $cellStyle)->addText('Sample/s Received by:', $textStyle, $centerAlignment);
        $signatoriesTable->addCell(4500, $cellStyle)->addText('Sample/s Reviewed by:', $textStyle, $centerAlignment);

        $signatoriesTable->addRow();
        $signatoriesTable->addCell(null, array_merge($cellStyle,['gridSpan' => 3]))->addText('REPORT NO.:', $textStyle);


        // Page number and Form details - PHPWord handles page numbers differently (usually in actual header/footer elements)
        // For simplicity, adding as text. A more robust solution would use PHPWord's header/footer elements.
        $section->addTextBreak(1);
        $smallText = ['size' => 7];
        $rightAlignedSmallText = ['size' => 7, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT];

        // This is a simplified way to add page number text.
        // True page numbers are added to headers/footers.
        // $section->addText('Page X of Y', $smallText); // Placeholder

        $section->addText(Yii::app()->params["FormRequest"]["number"], $smallText, $rightAlignedSmallText);
        $section->addText('Rev. '.Yii::app()->params["FormRequest"]["revNum"].' | '.Yii::app()->params['FormRequest']['revDate'], $smallText, $rightAlignedSmallText);
    }
}
?>
