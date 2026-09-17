<?php

class TextileTapeWorksheetWord
{
	public function render($section, $phpWord, $request, $sample, $sampleCode)
	{
		$bordered = array('borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 60);
		$plain = array('borderSize' => 0, 'cellMargin' => 40);
		$phpWord->addTableStyle('TextileHeader', $plain);
		$phpWord->addTableStyle('TextileGrid', $bordered);
		$phpWord->addTableStyle('TextilePlain', $plain);
		$label = array('bold' => true, 'size' => 9);
		$small = array('size' => 9);
		$center = array('size' => 8, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);

		$header = $section->addTable('TextileHeader');
		$logo = Yii::getPathOfAlias('webroot') . '/images/hydro_logo.jpg';
		$header->addRow(700);
		$logoCell = $header->addCell(1800);
		if (file_exists($logo)) {
			$logoCell->addImage($logo, array('width' => 105, 'height' => 30));
		}
		$titleCell = $header->addCell(7200);
		$titleCell->addText('CALIBRATION WORKSHEET OF TEXTILE TAPE MEASURE', array('bold' => true, 'size' => 13));
		$titleCell->addText('HME-CM-301-F01', array('size' => 10));
		$section->addTextBreak(1);

		$details = $section->addTable('TextilePlain');
		$details->addRow();
		$this->addCell($details, 1500, 'Company: ', $label);
		$this->addCell($details, 3000, $request->customer->customerName, $small, array('borderBottomSize' => 6));
		$this->addCell($details, 1500, 'Address: ', $label);
		$this->addCell($details, 3000, $request->customer->completeAddress, $small, array('borderBottomSize' => 6));
		$details->addRow();
		$this->addCell($details, 1500, 'Contact Person: ', $label);
		$this->addCell($details, 3000, $request->customer->head, $small, array('borderBottomSize' => 6));
		$this->addCell($details, 1500, 'Contact Information: ', $label);
		$contact = $request->contact_number;
		if ($request->customer->email != '') {
			$contact .= ' / ' . $request->customer->email;
		}
		$this->addCell($details, 3000, $contact, $small, array('borderBottomSize' => 6));

		$section->addTextBreak(1);
		$job = $section->addTable('TextilePlain');
		$job->addRow();
		$this->addCell($job, 1800, 'Type of Job:', $label);
		$this->addCell($job, 7200, '[  ] CALIBRATION    [  ] PARTIAL    [  ] On-site Calibration    [  ] Other __________', $small);

		$instrument = $section->addTable('TextilePlain');
		$instrument->addRow();
		$this->addCell($instrument, 1800, 'Instrument Description:', $label);
		$this->addCell($instrument, 2600, '[  ] Textile Tape Measure', $small);
		$this->addCell($instrument, 1100, '[  ] Others:', $small);
		$this->addCell($instrument, 2600, '', $small, array('borderBottomSize' => 6));
		$instrumentRows = array(
			array("Manufacturer's Name:", $sample->brand, 'Service Request No.:', $sample->requestId),
			array('Model No.:', $sample->model_no, 'Sample Code No.:', $sampleCode),
			array('Serial No.:', $sample->serial_no, 'Date Received:', date('d F Y', strtotime($request->requestDate))),
			array('Range:', $sample->capacity_range, 'Date Calibrated:', ''),
			array('Resolution:', $sample->resolution, 'Ambient Temperature:', ''),
			array('Location of Calibration:', '', 'Relative Humidity:', ''),
		);
		foreach ($instrumentRows as $row) {
			$instrument->addRow();
			$this->addCell($instrument, 1800, $row[0], $label);
			$this->addCell($instrument, 2600, $row[1], $small, array('borderBottomSize' => 6));
			$this->addCell($instrument, 1100, $row[2], $small);
			$this->addCell($instrument, 2600, $row[3], $small, array('borderBottomSize' => 6));
		}

		$section->addTextBreak(1);
		$section->addText('CALIBRATION METHOD:', $label);
		$section->addText('The instrument was calibrated in accordance with HME-CM-301, “Calibration Method of Textile Tape Measure” based on Japan Industrial Standard, JIS 7522:1993.', $small);
		$section->addTextBreak(1);
		$section->addText('STANDARD USED:', $label);
		$standards = $section->addTable('TextileGrid');
		$standards->addRow(350);
		foreach (array('Description', 'Serial No.', 'Equipment Code', 'Certificate No.', 'Traceable to') as $heading) {
			$this->addCell($standards, 1800, $heading, $center, array('valign' => 'center'));
		}
		for ($row = 0; $row < 3; $row++) {
			$standards->addRow(350);
			foreach (array(1800, 1400, 1500, 1500, 1800) as $width) {
				$this->addCell($standards, $width, '', $small);
			}
		}

		$section->addTextBreak(1);
		$section->addText('PRELIMINARY EVALUATION:', $label);
		$evaluation = $section->addTable('TextilePlain');
		$evaluation->addRow();
		$this->addCell($evaluation, 1800, 'Scale/Graduations', $small);
		$this->addCell($evaluation, 2600, '[  ] readable', $small);
		$this->addCell($evaluation, 2600, '[  ] unsatisfactory', $small);
		$evaluation->addRow();
		$this->addCell($evaluation, 1800, 'Missing / Broken parts', $small);
		$this->addCell($evaluation, 2600, '[  ] ____________________', $small);
		$this->addCell($evaluation, 2600, '[  ] none', $small);
		$evaluation->addRow();
		$this->addCell($evaluation, 7200, 'Visual inspection shows that the general condition and workmanship of the instrument were found ____________________________________________', $small);

		$section->addTextBreak(1);
		$section->addText('CALIBRATION RESULTS:', $label);
		$section->addText('Tolerances of Length                         (Temp. before measurement: ___________)', $small);
		$results = $section->addTable('TextileGrid');
		$results->addRow(350);
		$headers = array('Nominal Value, (mm)', 'Error Reading 1', 'Error Reading 2', 'Error Reading 3', 'Error Reading 4', 'Error Reading 5', 'Average (mm)', 'Error of Ref. Standards (mm)');
		foreach ($headers as $heading) {
			$this->addCell($results, 1100, $heading, $center, array('valign' => 'center'));
		}
		for ($row = 0; $row < 12; $row++) {
			$results->addRow(350);
			for ($column = 0; $column < 8; $column++) {
				$this->addCell($results, 1100, '', $small);
			}
		}
		$section->addText('                                           (Temp. after measurement: ___________)', $small);
		$section->addTextBreak(1);
		$signoff = $section->addTable('TextilePlain');
		$signoff->addRow();
		$this->addCell($signoff, 4500, 'Calibrated by: _______________________', $small);
		$this->addCell($signoff, 3000, 'Date: ____________', $small);
		$signoff->addRow();
		$this->addCell($signoff, 4500, 'Checked by: __________________________', $small);
		$this->addCell($signoff, 3000, 'Date: ____________', $small);
	}

	protected function addCell($table, $width, $text, $fontStyle, $cellStyle = array())
	{
		$cell = $table->addCell($width, $cellStyle);
		$cell->addText($text, $fontStyle);
		return $cell;
	}
}
