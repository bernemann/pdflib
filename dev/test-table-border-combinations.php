<?php

/**
 * Test various border combinations to ensure no overlap/cutoff issues
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

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Border Combination Tests', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(5);

// Test 1: Bottom and Left borders (BL)
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 6, 'Test 1: BL (Bottom-Left) borders', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$table = new Table($pdf);
$table->initialize([60, 60, 60], [
    'TABLE' => [
        'TABLE_ALIGN' => 'L',
        'BORDER_TYPE' => 0,
    ],
    'HEADER' => [
        'BORDER_TYPE' => 'BL',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 0, 0],
        'BACKGROUND_COLOR' => [230, 230, 230],
        'PADDING_TOP' => 2,
        'PADDING_BOTTOM' => 2,
    ],
    'ROW' => [
        'BORDER_TYPE' => 'BL',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 0, 0],
        'BACKGROUND_COLOR' => [255, 255, 255],
        'PADDING_TOP' => 2,
        'PADDING_BOTTOM' => 2,
    ],
]);

$table->addHeader([
    ['TEXT' => 'Column 1'],
    ['TEXT' => 'Column 2'],
    ['TEXT' => 'Column 3'],
]);

$table->addRow([
    ['TEXT' => 'Row 1, Cell 1'],
    ['TEXT' => 'Row 1, Cell 2'],
    ['TEXT' => 'Row 1, Cell 3'],
]);

$table->addRow([
    ['TEXT' => 'Row 2, Cell 1'],
    ['TEXT' => 'Row 2, Cell 2'],
    ['TEXT' => 'Row 2, Cell 3'],
]);

$table->close();

$pdf->Ln(10);

// Test 2: Top and Right borders (TR)
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 6, 'Test 2: TR (Top-Right) borders', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$table = new Table($pdf);
$table->initialize([60, 60, 60], [
    'TABLE' => [
        'TABLE_ALIGN' => 'L',
        'BORDER_TYPE' => 0,
    ],
    'HEADER' => [
        'BORDER_TYPE' => 'TR',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [200, 0, 0],
        'BACKGROUND_COLOR' => [255, 230, 230],
        'PADDING_TOP' => 2,
        'PADDING_BOTTOM' => 2,
    ],
    'ROW' => [
        'BORDER_TYPE' => 'TR',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [200, 0, 0],
        'BACKGROUND_COLOR' => [255, 255, 255],
        'PADDING_TOP' => 2,
        'PADDING_BOTTOM' => 2,
    ],
]);

$table->addHeader([
    ['TEXT' => 'Column 1'],
    ['TEXT' => 'Column 2'],
    ['TEXT' => 'Column 3'],
]);

$table->addRow([
    ['TEXT' => 'Row 1, Cell 1'],
    ['TEXT' => 'Row 1, Cell 2'],
    ['TEXT' => 'Row 1, Cell 3'],
]);

$table->addRow([
    ['TEXT' => 'Row 2, Cell 1'],
    ['TEXT' => 'Row 2, Cell 2'],
    ['TEXT' => 'Row 2, Cell 3'],
]);

$table->close();

$pdf->Ln(10);

// Test 3: Bottom only (B) with multiline
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 6, 'Test 3: B (Bottom only) with multiline text', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$table = new Table($pdf);
$table->initialize([40, 80, 60], [
    'TABLE' => [
        'TABLE_ALIGN' => 'L',
        'BORDER_TYPE' => 0,
    ],
    'HEADER' => [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 100, 0],
        'BACKGROUND_COLOR' => [230, 255, 230],
        'PADDING_TOP' => 2,
        'PADDING_BOTTOM' => 2,
    ],
    'ROW' => [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 100, 0],
        'BACKGROUND_COLOR' => [255, 255, 255],
        'PADDING_TOP' => 2,
        'PADDING_BOTTOM' => 2,
    ],
]);

$table->addHeader([
    ['TEXT' => 'ID'],
    ['TEXT' => 'Description'],
    ['TEXT' => 'Status'],
]);

$table->addRow([
    ['TEXT' => '1'],
    ['TEXT' => 'This is a multiline description that should wrap and maintain proper bottom border'],
    ['TEXT' => 'Active'],
]);

$table->addRow([
    ['TEXT' => '2'],
    ['TEXT' => 'Another row with text that wraps to multiple lines'],
    ['TEXT' => 'Pending'],
]);

$table->close();

if (PHP_SAPI === 'cli') {
    $outputFile = __DIR__ . '/test-table-border-combinations.pdf';
    $pdf->Output('F', $outputFile);
    echo "PDF written to: {$outputFile}\n";
    return;
}

$pdf->Output();

