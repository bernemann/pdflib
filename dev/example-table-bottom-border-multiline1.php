<?php

/**
 * Pdf Advanced Table - Bottom Border Multiline Example
 */
require_once __DIR__ . '/../autoload.php';

use EvoSys21\PdfLib\Fpdf\Pdf;
use EvoSys21\PdfLib\Table;

$pdf = new Pdf();
$pdf->SetAuthor('EvoSys21');
$pdf->SetMargins(20, 20, 20);
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->AddPage();

$table = new Table($pdf);
$table->setStyle('default', 10, '', [0, 0, 0], 'helvetica');
$table->setStyle('p');
$table->setStyle('b', null, 'B');

$table->initialize([
    18,
    75,
    77,
], [
    'TABLE' => [
        'TABLE_ALIGN' => 'L',
        'TABLE_LEFT_MARGIN' => 10,
        'BORDER_TYPE' => 0,
        'BORDER_SIZE' => 0.3,
        'BORDER_COLOR' => [0, 0, 0],
    ],
    'HEADER' => [
        'TEXT_COLOR' => [0, 0, 0],
        'TEXT_SIZE' => 10,
        'TEXT_FONT' => 'helvetica',
        'TEXT_ALIGN' => 'L',
        'VERTICAL_ALIGN' => 'M',
        'TEXT_TYPE' => 'B',
        'LINE_SIZE' => 5,
        'BACKGROUND_COLOR' => [255, 255, 255],
        'BORDER_COLOR' => [0, 0, 0],
        'BORDER_SIZE' => 0.3,
        'BORDER_TYPE' => 'B',
        'TEXT' => ' ',
        'PADDING_TOP' => 2,
        'PADDING_RIGHT' => 2,
        'PADDING_LEFT' => 2,
        'PADDING_BOTTOM' => 2,
    ],
    'ROW' => [
        'TEXT_COLOR' => [0, 0, 0],
        'TEXT_SIZE' => 10,
        'TEXT_FONT' => 'helvetica',
        'TEXT_ALIGN' => 'L',
        'VERTICAL_ALIGN' => 'T',
        'TEXT_TYPE' => '',
        'LINE_SIZE' => 5,
        'BACKGROUND_COLOR' => [255, 255, 255],
        'BORDER_COLOR' => [10, 220, 0],
        'BORDER_SIZE' => 3,
        'BORDER_TYPE' => 'BL',
        'TEXT' => ' ',
        'PADDING_TOP' => 2,
        'PADDING_RIGHT' => 2,
        'PADDING_LEFT' => 2,
        'PADDING_BOTTOM' => 2,
    ],
]);

$table->addHeader([
    ['TEXT' => 'ID'],
    ['TEXT' => 'Description'],
    ['TEXT' => 'Notes'],
]);

$table->addRow([
    ['TEXT' => '01', 'TEXT_ALIGN' => 'C'],
    ['BACKGROUND_COLOR' => [20, 20, 5],'TEXT' => 'This row demonstrates a wrapped description that spans multiple lines while keeping only the bottom border visible.', 'TEXT_ALIGN' => 'L'],
    ['TEXT' => 'Bottom border only', 'TEXT_ALIGN' => 'L'],
]);

$table->addRow([
    ['TEXT' => '02', 'TEXT_ALIGN' => 'C'],
    ['TEXT' => 'Short text on the left, but the middle cell is long enough to wrap naturally and prove the border stays at the bottom.', 'TEXT_ALIGN' => 'L'],
    ['TEXT' => "First line\nSecond line\nThird line", 'TEXT_ALIGN' => 'L'],
]);

$table->addRow([
    ['TEXT' => '03', 'TEXT_ALIGN' => 'C'],
    ['TEXT' => 'Another multiline example with enough words to create a tall row in the description column.', 'TEXT_ALIGN' => 'L'],
    ['TEXT' => 'The whole table uses only bottom borders for each cell.', 'TEXT_ALIGN' => 'L'],
]);

$table->close();

if (PHP_SAPI === 'cli') {
    $outputFile = __DIR__ . '/example-table-bottom-border-multiline.pdf';
    $pdf->Output('F', $outputFile);
    echo "PDF written to: {$outputFile}\n";
    return;
}

$pdf->Output();

