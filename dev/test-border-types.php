<?php

/**
 * Test all border types to ensure fix works correctly
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

// Test 1: Bottom border only
$pdf->Cell(0, 10, 'Test 1: Bottom Border Only', 0, 1);

$table = new Table($pdf);
$table->initialize([60, 60, 60], [
    'TABLE' => [
        'BORDER_TYPE' => 0,
        'BORDER_SIZE' => 0.5,
    ],
    'HEADER' => [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 0, 0],
        'PADDING' => 2,
    ],
    'ROW' => [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 0, 0],
        'PADDING' => 2,
    ],
]);

$table->addHeader([
    ['TEXT' => 'Column 1'],
    ['TEXT' => 'Column 2'],
    ['TEXT' => 'Column 3'],
]);

$table->addRow([
    ['TEXT' => 'Row 1'],
    ['TEXT' => 'Data'],
    ['TEXT' => 'More data'],
]);

$table->addRow([
    ['TEXT' => 'Row 2'],
    ['TEXT' => 'Multiline\ntext\nhere'],
    ['TEXT' => 'Test'],
]);

$table->close();

// Test 2: Top border only
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Test 2: Top Border Only', 0, 1);

$table = new Table($pdf);
$table->initialize([60, 60, 60], [
    'TABLE' => [
        'BORDER_TYPE' => 0,
        'BORDER_SIZE' => 0.5,
    ],
    'HEADER' => [
        'BORDER_TYPE' => 'T',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [255, 0, 0],
        'PADDING' => 2,
    ],
    'ROW' => [
        'BORDER_TYPE' => 'T',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [255, 0, 0],
        'PADDING' => 2,
    ],
]);

$table->addHeader([
    ['TEXT' => 'A'],
    ['TEXT' => 'B'],
    ['TEXT' => 'C'],
]);

$table->addRow([
    ['TEXT' => 'X'],
    ['TEXT' => 'Y'],
    ['TEXT' => 'Z'],
]);

$table->close();

// Test 3: Left border only
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Test 3: Left Border Only', 0, 1);

$table = new Table($pdf);
$table->initialize([60, 60, 60], [
    'TABLE' => [
        'BORDER_TYPE' => 0,
        'BORDER_SIZE' => 0.5,
    ],
    'HEADER' => [
        'BORDER_TYPE' => 'L',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 0, 255],
        'PADDING' => 2,
    ],
    'ROW' => [
        'BORDER_TYPE' => 'L',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 0, 255],
        'PADDING' => 2,
    ],
]);

$table->addHeader([
    ['TEXT' => 'Col 1'],
    ['TEXT' => 'Col 2'],
    ['TEXT' => 'Col 3'],
]);

$table->addRow([
    ['TEXT' => 'Data 1'],
    ['TEXT' => 'Data 2'],
    ['TEXT' => 'Data 3'],
]);

$table->close();

// Test 4: Right border only
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Test 4: Right Border Only', 0, 1);

$table = new Table($pdf);
$table->initialize([60, 60, 60], [
    'TABLE' => [
        'BORDER_TYPE' => 0,
        'BORDER_SIZE' => 0.5,
    ],
    'HEADER' => [
        'BORDER_TYPE' => 'R',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 255, 0],
        'PADDING' => 2,
    ],
    'ROW' => [
        'BORDER_TYPE' => 'R',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 255, 0],
        'PADDING' => 2,
    ],
]);

$table->addHeader([
    ['TEXT' => 'Header 1'],
    ['TEXT' => 'Header 2'],
    ['TEXT' => 'Header 3'],
]);

$table->addRow([
    ['TEXT' => 'Value 1'],
    ['TEXT' => 'Value 2'],
    ['TEXT' => 'Value 3'],
]);

$table->close();

// Test 5: All borders (ensure we didn't break standard borders)
$pdf->AddPage();
$pdf->Cell(0, 10, 'Test 5: All Borders (Standard)', 0, 1);

$table = new Table($pdf);
$table->initialize([60, 60, 60], [
    'TABLE' => [
        'BORDER_TYPE' => 0,
        'BORDER_SIZE' => 0.5,
    ],
    'HEADER' => [
        'BORDER_TYPE' => 1,
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 0, 0],
        'PADDING' => 2,
    ],
    'ROW' => [
        'BORDER_TYPE' => 1,
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 0, 0],
        'PADDING' => 2,
    ],
]);

$table->addHeader([
    ['TEXT' => 'All'],
    ['TEXT' => 'Borders'],
    ['TEXT' => 'Test'],
]);

$table->addRow([
    ['TEXT' => 'Should'],
    ['TEXT' => 'Have'],
    ['TEXT' => 'Box'],
]);

$table->close();

if (PHP_SAPI === 'cli') {
    $outputFile = __DIR__ . '/test-border-types.pdf';
    $pdf->Output('F', $outputFile);
    echo "PDF written to: {$outputFile}\n";
    return;
}

$pdf->Output();

